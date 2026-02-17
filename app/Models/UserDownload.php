<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDownload extends Model
{
    protected $fillable = [
        'user_id',
        'template_id',
        'session_id',
        'watermark_removed',
        'ip_address',
    ];

    protected $casts = [
        'watermark_removed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }
}