<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    protected $table = 'areas';

    protected $fillable = [
        'province_id',
        'district_id',
        'commune_id',
        'name',
        'slug',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Auto-generate slug when name is set, if slug isn't already provided.
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function ($area) {
            if (empty($area->slug) && !empty($area->name)) {
                $area->slug = str($area->name)->slug();
            }
        });
    }

    // ── Relationships ────────────────────────────────────

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function commune(): BelongsTo
    {
        return $this->belongsTo(Commune::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'area_id');
    }

    // ── Helpers ────────────────────────────────────

    /**
     * Returns 'province', 'district', or 'commune' based on which FK is filled.
     */
    public function getLevelAttribute(): ?string
    {
        if ($this->commune_id) return 'commune';
        if ($this->district_id) return 'district';
        if ($this->province_id) return 'province';
        return null;
    }

    /**
     * Scope to filter areas by level dynamically.
     */
    public function scopeLevel($query, string $level)
    {
        return match ($level) {
            'province' => $query->whereNotNull('province_id')->whereNull('district_id')->whereNull('commune_id'),
            'district' => $query->whereNotNull('district_id')->whereNull('commune_id'),
            'commune'  => $query->whereNotNull('commune_id'),
            default    => $query,
        };
    }

    /**
     * Only active areas.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}