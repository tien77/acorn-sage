<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WpUser extends Model
{
    // Bảng users của WP có prefix (vd. wp_users), nên set động theo prefix kết nối:
    protected $primaryKey = 'ID';
    public $timestamps = false; // wp_users không có created_at/updated_at
    protected $table = 'users';

    public function contacts()
    {
        // user(ID) -> contacts(user_id)
        return $this->hasMany(Contact::class, 'user_id', 'ID');
    }
}
