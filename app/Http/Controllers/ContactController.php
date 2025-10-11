<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function index()
    {
        // hiển thị form
        // return view('pages.contact'); // form thuần HTML
		return view('pages.contact-livewire'); // form Livewire
    }

    public function submit(Request $request)
    {
        // validate như Laravel
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'message' => 'required|min:10',
        ], [
            'name.required'    => 'Vui lòng nhập họ tên.',
            'email.required'   => 'Vui lòng nhập email.',
            'email.email'      => 'Email không hợp lệ.',
            'message.required' => 'Vui lòng nhập nội dung.',
            'message.min'      => 'Nội dung tối thiểu 10 ký tự.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput(); // giữ lại dữ liệu nhập
        }

        // xử lý thành công (ví dụ: gửi mail hoặc log)
        // Mail::to(...)->send(...);
        // hoặc
        Log::info($request->all());

        return redirect()
            ->back()
            ->with('success', 'Cảm ơn bạn đã liên hệ!');
    }

	public function myContacts()
	{
		$uid = (int) get_current_user_id();
		abort_if(!$uid, 403, 'Bạn cần đăng nhập');

		$contacts = \App\Models\Contact::with('user')
			->where('user_id', $uid)
			->latest('id')
			->paginate(2);

		return view('pages.my-contacts', compact('contacts'));
	}

}
