<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CmsHomeHero extends Model
{
    use HasFactory;

    protected $table = 'cms_home_hero';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = true;

    protected $fillable = [
        'id',
        'image_url',
        'status',
        'badge_en',
        'badge_kh',
        'title_main_en',
        'title_main_kh',
        'title_highlight_en',
        'title_highlight_kh',
        'subtitle_en',
        'subtitle_kh',
    ];

    protected $casts = [
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}