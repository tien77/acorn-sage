<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

// WordPress functions sẽ được load khi theme chạy

class CartController extends Controller
{
    /**
     * Hiển thị giỏ hàng
     */
    public function index(): View
    {
        try {
            $userId = \get_current_user_id() ?: null;
            
            // Đảm bảo session được start
            if (!session()->isStarted()) {
                session()->start();
            }
            
            $sessionId = $userId ? null : session()->getId();
            
            $cartItems = Cart::getCartItems($userId, $sessionId);
            $cartTotal = Cart::getCartTotal($userId, $sessionId);
            $cartCount = Cart::getCartCount($userId, $sessionId);

            return view('pages.cart', compact('cartItems', 'cartTotal', 'cartCount'));
            
        } catch (\Exception $e) {
            // Debug: Hiển thị lỗi
            return view('pages.cart', [
                'cartItems' => collect(),
                'cartTotal' => 0,
                'cartCount' => 0,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'integer|min:1|max:99'
        ]);

        $productId = $request->product_id;
        $quantity = $request->quantity ?? 1;

        $product = Product::find($productId);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại với ID: ' . $productId
            ], 400);
        }

        if (!$product->is_published) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm chưa được publish: ' . $product->name
            ], 400);
        }

        if (!$product->is_in_stock) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm hết hàng: ' . $product->name . ' (stock_quantity: ' . $product->stock_quantity . ')'
            ], 400);
        }

        $userId = \get_current_user_id() ?: null;
        $sessionId = $userId ? null : session()->getId();

        try {
            $success = Cart::addItem($productId, $quantity, $userId, $sessionId);

            if ($success) {
                $cartCount = Cart::getCartCount($userId, $sessionId);
                $cartTotal = Cart::getCartTotal($userId, $sessionId);

                return response()->json([
                    'success' => true,
                    'message' => 'Đã thêm sản phẩm vào giỏ hàng!',
                    'cart_count' => $cartCount,
                    'cart_total' => number_format($cartTotal, 0, ',', '.') . '₫'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Không đủ hàng tồn kho'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật số lượng trong giỏ hàng
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1|max:99'
        ]);

        $productId = $request->product_id;
        $quantity = $request->quantity;

        $userId = \get_current_user_id() ?: null;
        $sessionId = $userId ? null : session()->getId();

        try {
            $success = Cart::updateQuantity($productId, $quantity, $userId, $sessionId);

            if ($success) {
                $cartCount = Cart::getCartCount($userId, $sessionId);
                $cartTotal = Cart::getCartTotal($userId, $sessionId);

                // Tính tổng tiền cho item này
                $cartItem = Cart::getCartItems($userId, $sessionId)
                    ->where('product_id', $productId)
                    ->first();

                $itemTotal = $cartItem ? $cartItem->total_price : 0;

                return response()->json([
                    'success' => true,
                    'message' => 'Đã cập nhật giỏ hàng!',
                    'cart_count' => $cartCount,
                    'cart_total' => number_format($cartTotal, 0, ',', '.') . '₫',
                    'item_total' => number_format($itemTotal, 0, ',', '.') . '₫'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Không đủ hàng tồn kho'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function remove(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id'
        ]);

        $productId = $request->product_id;
        $userId = \get_current_user_id() ?: null;
        $sessionId = $userId ? null : session()->getId();

        try {
            Cart::removeItem($productId, $userId, $sessionId);

            $cartCount = Cart::getCartCount($userId, $sessionId);
            $cartTotal = Cart::getCartTotal($userId, $sessionId);

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa sản phẩm khỏi giỏ hàng!',
                'cart_count' => $cartCount,
                'cart_total' => number_format($cartTotal, 0, ',', '.') . '₫'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear(): JsonResponse
    {
        $userId = \get_current_user_id() ?: null;
        $sessionId = $userId ? null : session()->getId();

        try {
            Cart::clearCart($userId, $sessionId);

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa toàn bộ giỏ hàng!',
                'cart_count' => 0,
                'cart_total' => '0₫'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy thông tin giỏ hàng (AJAX)
     */
    public function getCartInfo(): JsonResponse
    {
        $userId = \get_current_user_id() ?: null;
        $sessionId = $userId ? null : session()->getId();

        $cartItems = Cart::getCartItems($userId, $sessionId);
        $cartTotal = Cart::getCartTotal($userId, $sessionId);
        $cartCount = Cart::getCartCount($userId, $sessionId);

        return response()->json([
            'success' => true,
            'cart_count' => $cartCount,
            'cart_total' => number_format($cartTotal, 0, ',', '.') . '₫',
            'items' => $cartItems->map(function ($item) {
                return [
                    'id' => $item->product_id,
                    'name' => $item->product->name,
                    'image' => $item->product->featured_image,
                    'price' => number_format($item->price, 0, ',', '.') . '₫',
                    'quantity' => $item->quantity,
                    'total' => number_format($item->total_price, 0, ',', '.') . '₫'
                ];
            })
        ]);
    }

    /**
     * Merge cart khi user login
     */
    public function mergeCart(): JsonResponse
    {
        $userId = \get_current_user_id();
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Người dùng chưa đăng nhập'
            ], 401);
        }

        $sessionId = session()->getId();

        try {
            Cart::mergeSessionToUser($sessionId, $userId);

            $cartCount = Cart::getCartCount($userId, null);
            $cartTotal = Cart::getCartTotal($userId, null);

            return response()->json([
                'success' => true,
                'message' => 'Đã đồng bộ giỏ hàng!',
                'cart_count' => $cartCount,
                'cart_total' => number_format($cartTotal, 0, ',', '.') . '₫'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Quick add to cart (cho AJAX từ product listing)
     */
    public function quickAdd(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id'
        ]);

        $productId = $request->product_id;
        $product = Product::find($productId);

        if (!$product || !$product->is_published || !$product->is_in_stock) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không khả dụng'
            ], 400);
        }

        $userId = \get_current_user_id() ?: null;
        $sessionId = $userId ? null : session()->getId();

        try {
            $success = Cart::addItem($productId, 1, $userId, $sessionId);

            if ($success) {
                $cartCount = Cart::getCartCount($userId, $sessionId);

                return response()->json([
                    'success' => true,
                    'message' => "Đã thêm \"{$product->name}\" vào giỏ hàng!",
                    'cart_count' => $cartCount
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm đã hết hàng'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra'
            ], 500);
        }
    }
}