<?php

namespace App\Models\Interaction;

use Illuminate\Database\Eloquent\Model;

class RequestMessage extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'request_info_id',
        'sender',
        'message',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function requestInfo()
    {
        return $this->belongsTo(RequestInfo::class);
    }
}