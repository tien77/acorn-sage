@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-10">
  <h1 class="text-2xl font-bold mb-6">Liên hệ</h1>

  <form
      x-data="contactForm()"
      @submit.prevent="submit"
      class="space-y-5 border p-6 rounded-lg shadow-sm bg-white">

    <div>
      <label for="name" class="block font-medium text-gray-700">
        Họ tên <span class="text-red-500">*</span>
      </label>
      <input 
        type="text" 
        id="name" 
        x-model="form.name"
        @blur="validateField('name')"
        @input.debounce.300ms="validateField('name')"
        :class="{
          'border-red-500 focus:ring-red-200': errors.name,
          'border-green-500 focus:ring-green-200': !errors.name && form.name && touched.name,
          'border-gray-300 focus:ring-blue-200': !touched.name
        }"
        class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring transition-colors"
        placeholder="Nhập họ tên (tối thiểu 2 ký tự)">
      <template x-if="errors.name">
        <p class="text-red-600 text-sm mt-1 flex items-center">
          <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
          </svg>
          <span x-text="errors.name"></span>
        </p>
      </template>
      <template x-if="!errors.name && form.name && touched.name">
        <p class="text-green-600 text-sm mt-1 flex items-center">
          <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
          </svg>
          <span>Hợp lệ</span>
        </p>
      </template>
    </div>

    <div>
      <label for="email" class="block font-medium text-gray-700">
        Email <span class="text-red-500">*</span>
      </label>
      <input 
        type="email" 
        id="email" 
        x-model="form.email"
        @blur="validateField('email')"
        @input.debounce.500ms="validateField('email')"
        :class="{
          'border-red-500 focus:ring-red-200': errors.email,
          'border-green-500 focus:ring-green-200': !errors.email && form.email && touched.email,
          'border-gray-300 focus:ring-blue-200': !touched.email
        }"
        class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring transition-colors"
        placeholder="you@example.com">
      <template x-if="errors.email">
        <p class="text-red-600 text-sm mt-1 flex items-center">
          <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
          </svg>
          <span x-text="errors.email"></span>
        </p>
      </template>
      <template x-if="!errors.email && form.email && touched.email">
        <p class="text-green-600 text-sm mt-1 flex items-center">
          <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
          </svg>
          <span>Email hợp lệ</span>
        </p>
      </template>
    </div>

    <div>
      <label for="message" class="block font-medium text-gray-700">
        Nội dung <span class="text-red-500">*</span>
      </label>
      <textarea 
        id="message" 
        x-model="form.message" 
        rows="5"
        @blur="validateField('message')"
        @input.debounce.300ms="validateField('message')"
        :class="{
          'border-red-500 focus:ring-red-200': errors.message,
          'border-green-500 focus:ring-green-200': !errors.message && form.message && touched.message,
          'border-gray-300 focus:ring-blue-200': !touched.message
        }"
        class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring transition-colors"
        placeholder="Lời nhắn của bạn... (tối thiểu 10 ký tự)"></textarea>
      <div class="flex justify-between items-start mt-1">
        <div class="flex-1">
          <template x-if="errors.message">
            <p class="text-red-600 text-sm flex items-center">
              <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
              </svg>
              <span x-text="errors.message"></span>
            </p>
          </template>
          <template x-if="!errors.message && form.message && touched.message">
            <p class="text-green-600 text-sm flex items-center">
              <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
              </svg>
              <span>Nội dung hợp lệ</span>
            </p>
          </template>
        </div>
        <span 
          class="text-xs text-gray-500 ml-2"
          :class="form.message.length < 10 ? 'text-red-500' : 'text-gray-500'"
          x-text="`${form.message.length}/10`">
        </span>
      </div>
    </div>

    <button
      type="submit"
      :disabled="submitting || !isFormValid"
      :class="{
        'bg-blue-600 hover:bg-blue-700': isFormValid && !submitting,
        'bg-gray-400 cursor-not-allowed': !isFormValid || submitting
      }"
      class="text-white px-5 py-2 rounded-lg transition flex items-center justify-center"
      x-text="submitting ? 'Đang gửi...' : 'Gửi liên hệ'">
    </button>

    <p x-show="success" class="text-green-600 mt-2">Gửi thành công! 🎉</p>
  </form>

  <div class="pt-6">
    <a wire:navigate href="{{ route('alpine.test') }}" class="text-blue-600 underline">
      Đi tới trang test Alpine khác
    </a>
  </div>
</div>

<script>
function contactForm() {
  return {
    form: { name: '', email: '', message: '' },
    errors: {},
    touched: { name: false, email: false, message: false },
    submitting: false,
    success: false,

    // Getter để check form có hợp lệ không
    get isFormValid() {
      return this.form.name.trim().length >= 2 &&
             this.isValidEmail(this.form.email) &&
             this.form.message.trim().length >= 10 &&
             Object.keys(this.errors).length === 0;
    },

    // Validate một field cụ thể
    validateField(fieldName) {
      this.touched[fieldName] = true;
      
      switch (fieldName) {
        case 'name':
          if (!this.form.name.trim()) {
            this.errors.name = 'Vui lòng nhập họ tên.';
          } else if (this.form.name.trim().length < 2) {
            this.errors.name = 'Họ tên phải có ít nhất 2 ký tự.';
          } else {
            delete this.errors.name;
          }
          break;

        case 'email':
          if (!this.form.email.trim()) {
            this.errors.email = 'Vui lòng nhập email.';
          } else if (!this.isValidEmail(this.form.email)) {
            this.errors.email = 'Định dạng email không hợp lệ.';
          } else {
            delete this.errors.email;
          }
          break;

        case 'message':
          if (!this.form.message.trim()) {
            this.errors.message = 'Vui lòng nhập nội dung.';
          } else if (this.form.message.trim().length < 10) {
            this.errors.message = 'Nội dung phải có ít nhất 10 ký tự.';
          } else {
            delete this.errors.message;
          }
          break;
      }
    },

    // Validate tất cả fields
    validateAll() {
      this.validateField('name');
      this.validateField('email');
      this.validateField('message');
      return Object.keys(this.errors).length === 0;
    },

    // Kiểm tra email hợp lệ
    isValidEmail(email) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(email);
    },

    async submit() {
      // Mark tất cả field là touched để hiển thị lỗi
      this.touched = { name: true, email: true, message: true };
      
      if (!this.validateAll()) return;
      
      this.submitting = true;
      this.success = false;

      try {
        // Mô phỏng gửi request
        await new Promise(r => setTimeout(r, 1200));
        
        this.submitting = false;
        this.success = true;
        
        // Reset form sau khi thành công
        this.form = { name: '', email: '', message: '' };
        this.errors = {};
        this.touched = { name: false, email: false, message: false };
        
        // Tự động ẩn thông báo thành công sau 5s
        setTimeout(() => {
          this.success = false;
        }, 5000);
        
      } catch (error) {
        this.submitting = false;
        console.error('Error submitting form:', error);
      }
    }
  }
}
</script>
@endsection
