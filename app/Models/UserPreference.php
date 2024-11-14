<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    protected $fillable = ['user_id', 'sources', 'categories', 'authors'];

    protected $casts = [
        'sources' => 'string',
        'categories' => 'string',
        'authors' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
