🧩 Sage + Laravel + Livewire Integration Guide
# Tổng quan kiến trúc

Dự án là WordPress theme xây dựng trên nền Roots Sage, mở rộng thêm:

Laravel Acorn → Framework container, service provider, route, middleware, ORM.

Laravel Eloquent ORM → quản lý model, migration, quan hệ dữ liệu như Laravel.

Livewire v3 + Alpine.js → reactive component, SPA navigation (wire:navigate), realtime validation.

TailwindCSS + Vite → frontend build & style.

Cấu trúc cho phép dev dùng cú pháp Laravel hoàn chỉnh (route, controller, model, migration, blade) trong môi trường WordPress.

# Cấu trúc thư mục chính
theme/
├── app/
│   ├── Console/            # Artisan command
│   ├── Controllers/        # Controller Laravel-style
│   ├── Http/
│   │   ├── Middleware/     # Custom middleware
│   │   └── routes.php      # Laravel-style routes
│   ├── Livewire/           # Livewire components
│   ├── Models/             # Eloquent models (Contact, User, ...)
│   ├── Providers/
│   │   └── ThemeServiceProvider.php  # boot database, pagination, livewire, Middleware
│   └── Services/           # (tùy chọn) business logic
│
├── config/
│   └── database.php        # Dùng DB của WordPress
│
├── database/
│   └── migrations/         # Laravel-style migrations
│
├── resources/
│   ├── views/
│   │   ├── layouts/        # app.blade.php, base layout
│   │   ├── livewire/       # Livewire views
│   │   ├── pages/          # Blade page views (ví dụ: my-contacts.blade.php)
│   │   └── vendor/pagination/ # Custom pagination views
│   └── js/, css/           # assets build bằng Vite
│
├── routes/
│   └── web.php             # Router Laravel (dành cho SPA hoặc API nội bộ)
│
├── public/                 # build assets từ Vite
└── composer.json

# Luồng chạy tổng thể
1️⃣ WordPress boot → theme Sage load.
2️⃣ Acorn app (roots/acorn) boot Laravel container.
3️⃣ ThemeServiceProvider:
đăng ký DB (Eloquent),
đăng ký route, middleware,
load migration,
kích hoạt pagination.
4️⃣ middleware quản lý trong App\Providers\ThemeServiceProvider
5️⃣ Khi truy cập URL do Laravel route handle Laravel xử lý full stack.
6️⃣ Livewire render view reactive trong Blade.

# Middleware
boot trong ThemeServiceProvider.php

# Livewire setup
Gắn @livewireStyles trong <head>, @livewireScripts trước </body>.
Dùng <livewire:component-name /> trong view hoặc route → component class.
```html
<a wire:navigate href="/contact">Liên hệ</a> Không cần reload trang.
<livewire:contact-form />
```
Dùng <livewire:component /> cho form, CRUD, search UI.
Dùng wire:navigate để điều hướng SPA.
Mỗi component có 2 file:
PHP: app/Livewire/ComponentName.php
View: resources/views/livewire/component-name.blade.php

# Routing
Route Laravel nằm trong routes/web.php
Các page WP truyền thống vẫn hoạt động bình thường song song

# Eloquent Models & Migrations
## Migration

Lưu migration trong theme
Dùng wp acorn migrate để đồng bộ DB theo version.
### Tạo migration mới:
wp acorn make:migration create_contacts_table --create=contacts

## Model
app/Models/User.php là ví dụ model Eloquent cho bảng wp_users vẫn dùng session WordPress, không cần auth()

````php
class User extends Model
{
    protected $primaryKey = 'ID';
    public $timestamps = false; // wp_users không có created_at/updated_at
    protected $table = 'users';
    public function contacts()
    {
        return $this->hasMany(Contact::class, 'user_id', 'ID');
    }
}

$userId = (int) get_current_user_id();
$user = wp_get_current_user();
````

### Pagination fix (WordPress-safe)
Paginator::useTailwind(); // trong ThemeServiceProvider.php
Dùng {{ $items->links('vendor.pagination.wp-tailwind') }} trong Blade.

# Coding style
Tên class, namespace, folder giống Laravel chuẩn.
Không can thiệp core WP, chỉ hook (do_action, add_filter) khi cần.


# Command hữu ích
Dùng wp acorn <command> để chạy Artisan command.

  Acorn 5.0.5 (Laravel 12.16.0)

  USAGE: wp acorn <command> [options] [arguments]

  about                                Display basic information about your application
  clear-compiled                       Remove the compiled class file
  completion                           Dump the shell completion script
  db                                   Start a new database CLI session
  env                                  Display the current framework environment
  help                                 Display help for a command
  list                                 List commands
  migrate                              Run the database migrations
  optimize                             Cache framework bootstrap, configuration, and metadata to increase performance

  acorn:init                           Initializes required paths in the base directory.
  acorn:install                        Install Acorn into the application

  cache:clear                          Flush the application cache
  cache:forget                         Remove an item from the cache

  config:cache                         Create a cache file for faster configuration loading
  config:clear                         Remove the configuration cache file

  db:seed                              Seed the database with records
  db:table                             Display information about the given database table
  db:wipe                              Drop all tables, views, and types

  key:generate                         Set the application key

  livewire:attribute                   Create a new Livewire attribute class
  livewire:configure-s3-upload-cleanup Configure temporary file upload s3 directory to automatically cleanup files older than 24hrs
  livewire:copy                        Copy a Livewire component
  livewire:delete                      Delete a Livewire component
  livewire:form                        Create a new Livewire form class
  livewire:layout                      Create a new app layout file
  livewire:make                        Create a new Livewire component
  livewire:move                        Move a Livewire component
  livewire:publish                     Publish Livewire configuration
  livewire:stubs                       Publish Livewire stubs
  livewire:upgrade                     Interactive upgrade helper to migrate from v2 to v3

  make:command                         Create a new Artisan command
  make:component                       Create a new view component class
  make:composer                        Create a new view composer class
  make:controller                      Create a new controller class
  make:job                             Create a new job class
  make:livewire                        Create a new Livewire component
  make:middleware                      Create a new HTTP middleware class
  make:migration                       Create a new migration file
  make:provider                        Create a new service provider class
  make:queue-batches-table             Create a migration for the batches database table
  make:queue-failed-table              Create a migration for the failed queue jobs database table
  make:queue-table                     Create a migration for the queue jobs database table
  make:seeder                          Create a new seeder class

  migrate:fresh                        Drop all tables and re-run all migrations
  migrate:install                      Create the migration repository
  migrate:refresh                      Reset and re-run all migrations
  migrate:reset                        Rollback all database migrations
  migrate:rollback                     Rollback the last database migration
  migrate:status                       Show the status of each migration

  optimize:clear                       Remove the cached bootstrap files

  package:discover                     Rebuild the cached package manifest

  queue:clear                          Delete all of the jobs from the specified queue
  queue:work                           Start processing jobs on the queue as a daemon

  route:cache                          Create a route cache file for faster route registration
  route:clear                          Remove the route cache file
  route:list                           List all registered routes

  schedule:interrupt                   Interrupt the current schedule run
  schedule:list                        List all scheduled tasks
  schedule:run                         Run the scheduled commands
  schedule:test                        Run a scheduled command
  schedule:work                        Start the schedule worker

  vendor:publish                       Publish any publishable assets from vendor packages

  view:cache                           Compile all of the application's Blade templates
  view:clear                           Clear all compiled view files


# JavaScript & SPA Navigation (wire:navigate)

## ⚠️ Vấn đề JavaScript với SPA Navigation

Khi sử dụng `wire:navigate` cho SPA experience, JavaScript chỉ chạy lần đầu trang load.
Khi navigate qua lại các trang, event listeners có thể bị mất.

### ❌ Sai - JavaScript chỉ chạy một lần:
```javascript
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.filter-btn');
    buttons.forEach(btn => {
        btn.addEventListener('click', handleClick); // Chỉ chạy lần đầu
    });
});
```

### ✅ Đúng - JavaScript hoạt động với SPA:
```javascript
// Tách thành function để gọi lại
function initializeComponents() {
    const buttons = document.querySelectorAll('.filter-btn');
    
    // Remove old listeners (tránh duplicate)
    buttons.forEach(btn => {
        btn.removeEventListener('click', handleClick);
    });
    
    // Add new listeners
    buttons.forEach(btn => {
        btn.addEventListener('click', handleClick);
    });
}

// Init lần đầu
document.addEventListener('DOMContentLoaded', initializeComponents);

// Reinit sau SPA navigation
document.addEventListener('livewire:navigated', function() {
    setTimeout(initializeComponents, 100); // Delay nhỏ để DOM ready
});
```

## Global Functions cho Modal/Components

Functions cần persist qua SPA navigation nên đặt global:

```javascript
// ✅ Global functions không bị mất khi navigate
window.openModal = function(data) { /* ... */ };
window.closeModal = function() { /* ... */ };

// ❌ Local functions bị mất khi SPA navigate
function openModal(data) { /* ... */ } // Sẽ bị undefined
```

## Event Listeners Toàn Cục

Keyboard events, click outside, v.v. chỉ nên add một lần:

```javascript
// ✅ Chỉ add listener một lần
if (!window.keyboardListenerAdded) {
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') window.closeModal();
    });
    window.keyboardListenerAdded = true;
}
```

## Layout Requirements

Layout phải có `@stack('scripts')` để nhận JS từ views:

```blade
<!-- resources/views/layouts/app.blade.php -->
@php(wp_footer())
@livewireScripts
@stack('scripts')  <!-- ✅ Quan trọng -->
</body>
```

## Livewire Events

Các Livewire navigation events hữu ích:
- `livewire:navigating` - Trước khi navigate
- `livewire:navigated` - Sau khi navigate xong
- `livewire:load` - Component đã load

# lưu ý
phản hồi bằng tiếng Việt