<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Mail; // ví dụ nếu muốn gửi mail
// use Livewire\Attributes\Layout;
use App\Models\Contact;

// #[Layout('layouts.app')]   // <= cách khác để đặt layout mặc định cho component Livewire (đã đặt trong ThemeServiceProvider rồi nên không cần nữa)
class ContactForm extends Component
{
    public string $name = '';
    public string $email = '';
    public string $message = '';
    public ?string $success = null;

    // Quy tắc & thông điệp lỗi
    protected function rules(): array
    {
        return [
            'name'    => ['required','string','max:255'],
            'email'   => ['required','email'],
            'message' => ['required','min:10'],
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required'    => 'Vui lòng nhập họ tên.',
            'email.required'   => 'Vui lòng nhập email.',
            'email.email'      => 'Email không hợp lệ.',
            'message.required' => 'Vui lòng nhập nội dung.',
            'message.min'      => 'Nội dung tối thiểu 10 ký tự.',
        ];
    }

    // Realtime: mỗi khi 1 field đổi, chỉ validate field đó
    public function updated(string $property): void
    {
        $this->validateOnly($property);
    }

    // Submit
    public function submit(): void
    {
        $validated = $this->validate();

        // Lấy user hiện tại của WordPress
        $userId = function_exists('get_current_user_id') ? (int) get_current_user_id() : 0;
        $validated['user_id'] = $userId ?: null; // null nếu khách
        
        Contact::create($validated);
        
        // TODO: xử lý (gửi mail / lưu DB)
        // Mail::to('you@example.com')->send(new ContactMailable($validated));

        // Reset + báo thành công
        $this->reset(['name','email','message']);
        $this->success = 'Cảm ơn bạn đã liên hệ!';
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
