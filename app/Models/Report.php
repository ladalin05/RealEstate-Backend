<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserManagement\User;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'message',
        'severity', // Low | Medium | High
        'status',   // Pending | Resolved
        'date',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }
}