<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts';

    protected $fillable = [
        'name', 'email', 'message', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(WpUser::class, 'user_id', 'ID');
    }

}
