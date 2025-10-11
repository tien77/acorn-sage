<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->index()->after('id');
            // (Tuỳ chọn) KHÔNG khuyến nghị bắt buộc: FK tới wp_users
            // $prefix = DB::getTablePrefix();
            // DB::statement("ALTER TABLE contacts
            //   ADD CONSTRAINT fk_contacts_user
            //   FOREIGN KEY (user_id) REFERENCES {$prefix}users(ID)
            //   ON DELETE SET NULL");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // nếu có FK ở trên thì drop trước:
            // DB::statement("ALTER TABLE contacts DROP FOREIGN KEY fk_contacts_user");
            $table->dropColumn('user_id');

        });
    }
};
