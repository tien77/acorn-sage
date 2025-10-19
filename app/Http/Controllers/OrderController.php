<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Hiển thị trang checkout
     */
    public function checkout(): View
    {
        $userId = \get_current_user_id() ?: null;
        $sessionId = $userId ? null : session()->getId();
        
        $cartItems = Cart::getCartItems($userId, $sessionId);
        $cartTotal = Cart::getCartTotal($userId, $sessionId);
        $cartCount = Cart::getCartCount($userId, $sessionId);

        if ($cartItems->isEmpty()) {
            return redirect()->route('products.index')
                ->with('error', 'Giỏ hàng trống. Vui lòng thêm sản phẩm trước khi thanh toán.');
        }

        // Kiểm tra tồn kho
        if (Cart::hasOutOfStockItems($userId, $sessionId)) {
            return redirect()->route('cart.index')
                ->with('error', 'Có sản phẩm trong giỏ hàng đã hết hàng. Vui lòng kiểm tra lại.');
        }

        // Lấy thông tin user nếu đã đăng nhập
        $user = null;
        if ($userId) {
            $user = \wp_get_current_user();
        }

        $shippingFee = $this->calculateShippingFee($cartTotal);

        return view('pages.checkout', compact(
            'cartItems', 
            'cartTotal', 
            'cartCount', 
            'user', 
            'shippingFee'
        ));
    }

    /**
     * Xử lý đặt hàng
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'shipping_district' => 'required|string|max:100',
            'shipping_ward' => 'required|string|max:100',
            'payment_method' => 'required|in:cod,bank_transfer,momo,vnpay',
            'notes' => 'nullable|string|max:1000'
        ]);

        $userId = \get_current_user_id() ?: null;
        $sessionId = $userId ? null : session()->getId();
        
        $cartItems = Cart::getCartItems($userId, $sessionId);

        if ($cartItems->isEmpty()) {
            return redirect()->route('products.index')
                ->with('error', 'Giỏ hàng trống.');
        }

        // Kiểm tra tồn kho một lần nữa
        if (Cart::hasOutOfStockItems($userId, $sessionId)) {
            return redirect()->route('cart.index')
                ->with('error', 'Có sản phẩm trong giỏ hàng đã hết hàng.');
        }

        $cartTotal = Cart::getCartTotal($userId, $sessionId);
        $shippingFee = $this->calculateShippingFee($cartTotal);

        $customerData = [
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'shipping_address' => $request->shipping_address,
            'shipping_city' => $request->shipping_city,
            'shipping_district' => $request->shipping_district,
            'shipping_ward' => $request->shipping_ward,
            'shipping_postal_code' => $request->shipping_postal_code,
        ];

        $orderData = [
            'payment_method' => $request->payment_method,
            'shipping_fee' => $shippingFee,
            'tax_amount' => 0, // Có thể tính thuế sau
            'discount_amount' => 0, // Có thể áp dụng mã giảm giá sau
            'notes' => $request->notes,
            'status' => 'pending',
            'payment_status' => $request->payment_method === 'cod' ? 'pending' : 'pending'
        ];

        try {
            $order = Order::createFromCart($customerData, $orderData, $userId, $sessionId);

            // Redirect dựa trên phương thức thanh toán
            switch ($request->payment_method) {
                case 'bank_transfer':
                    return redirect()->route('orders.payment-info', $order->id)
                        ->with('success', 'Đặt hàng thành công! Vui lòng chuyển khoản để hoàn tất đơn hàng.');

                case 'momo':
                case 'vnpay':
                    // TODO: Tích hợp gateway thanh toán
                    return redirect()->route('orders.show', $order->id)
                        ->with('info', 'Chức năng thanh toán online đang được phát triển. Đơn hàng sẽ được xử lý thủ công.');

                default: // COD
                    return redirect()->route('orders.show', $order->id)
                        ->with('success', 'Đặt hàng thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất.');
            }

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi đặt hàng: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị chi tiết đơn hàng
     */
    public function show(Order $order): View
    {
        // Kiểm tra quyền xem đơn hàng
        $userId = \get_current_user_id();
        if ($order->user_id && $order->user_id !== $userId) {
            abort(403, 'Bạn không có quyền xem đơn hàng này.');
        }

        $order->load('items.product');

        return view('pages.order-detail', compact('order'));
    }

    /**
     * Hiển thị thông tin thanh toán (cho chuyển khoản)
     */
    public function paymentInfo(Order $order): View
    {
        // Kiểm tra quyền và phương thức thanh toán
        $userId = \get_current_user_id();
        if ($order->user_id && $order->user_id !== $userId) {
            abort(403, 'Bạn không có quyền xem đơn hàng này.');
        }

        if ($order->payment_method !== 'bank_transfer') {
            return redirect()->route('orders.show', $order->id);
        }

        return view('pages.payment-info', compact('order'));
    }

    /**
     * Danh sách đơn hàng của user
     */
    public function myOrders(): View
    {
        $userId = \get_current_user_id();
        
        if (!$userId) {
            return redirect()->route('login')
                ->with('error', 'Vui lòng đăng nhập để xem đơn hàng.');
        }

        $orders = Order::where('user_id', $userId)
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.my-orders', compact('orders'));
    }

    /**
     * Hủy đơn hàng
     */
    public function cancel(Order $order): RedirectResponse
    {
        $userId = \get_current_user_id();
        
        // Kiểm tra quyền hủy đơn hàng
        if ($order->user_id && $order->user_id !== $userId) {
            abort(403, 'Bạn không có quyền hủy đơn hàng này.');
        }

        if (!$order->canBeCancelled()) {
            return redirect()->back()
                ->with('error', 'Không thể hủy đơn hàng ở trạng thái hiện tại.');
        }

        try {
            $order->updateStatus('cancelled', 'Khách hàng yêu cầu hủy đơn hàng');

            return redirect()->back()
                ->with('success', 'Đã hủy đơn hàng thành công.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi hủy đơn hàng.');
        }
    }

    /**
     * Tính phí giao hàng
     */
    private function calculateShippingFee(float $cartTotal): float
    {
        // Logic tính phí giao hàng
        if ($cartTotal >= 500000) { // Miễn phí ship từ 500k
            return 0;
        }

        return 30000; // Phí ship cố định 30k
    }

    /**
     * API: Tính phí giao hàng
     */
    public function calculateShipping(Request $request): JsonResponse
    {
        $request->validate([
            'city' => 'required|string',
            'district' => 'required|string'
        ]);

        $userId = \get_current_user_id() ?: null;
        $sessionId = $userId ? null : session()->getId();
        $cartTotal = Cart::getCartTotal($userId, $sessionId);

        $shippingFee = $this->calculateShippingFee($cartTotal);

        // Có thể mở rộng logic tính phí theo khu vực
        $city = $request->city;
        $district = $request->district;

        // Tính phí theo khu vực (ví dụ)
        if (in_array($city, ['Hà Nội', 'TP.HCM'])) {
            // Nội thành
            if ($cartTotal >= 300000) {
                $shippingFee = 0;
            } else {
                $shippingFee = 25000;
            }
        } else {
            // Tỉnh khác
            if ($cartTotal >= 500000) {
                $shippingFee = 0;
            } else {
                $shippingFee = 50000;
            }
        }

        $finalTotal = $cartTotal + $shippingFee;

        return response()->json([
            'success' => true,
            'shipping_fee' => $shippingFee,
            'shipping_fee_formatted' => number_format($shippingFee, 0, ',', '.') . '₫',
            'cart_total' => $cartTotal,
            'final_total' => $finalTotal,
            'final_total_formatted' => number_format($finalTotal, 0, ',', '.') . '₫'
        ]);
    }
}