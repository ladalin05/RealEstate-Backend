<?php

namespace App\Models\UserManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInform extends Model
{
    use HasFactory;

    protected $table = 'user_inform'; // table name

    protected $fillable = [
        'user_id',
        'plan_id',
        'image',
        'contact_address',
        'contact_phone',
        'contact_email',
        'contact_location',
        'facebook',
        'twitter',
        'instagram',
        'youtube',
        'created_by',
        'updated_by',
    ];

    /**
     * Relationships
     */

    // User (belongsTo)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
