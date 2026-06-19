<?php

namespace App\Models\Admin;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use Notifiable, SoftDeletes, HasApiTokens;

    protected $table = 'admins';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'image',
        'phone',
        'address',
        'bio',
        'active',
        'remember_token',
        'last_login_at',
        'last_login_ip',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password'      => 'hashed',
            'last_login_at' => 'datetime',
            'active'        => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->id();
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id();
        });

        static::deleting(function ($model) {
            if (!$model->forceDeleting) {
                $model->save();
            }
        });
    }

    

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'admin_roles', 'admin_id', 'role_id');
    }

    // ─── Role helpers ────────────────────────────────────────────────────────

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super-admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['super-admin', 'admin']);
    }

    public function isCashier(): bool
    {
        return $this->role === 'cashier';
    }

    public function isActive(): bool
    {
        return (bool) $this->active;
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by')->withTrashed();
    }

    public function updater()
    {
        return $this->belongsTo(Admin::class, 'updated_by')->withTrashed();
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getCreatedAtAttribute($value): ?string
    {
        return $value ? date('d/m/Y h:i A', strtotime($value)) : null;
    }

    public function getUpdatedAtAttribute($value): ?string
    {
        return $value ? date('d/m/Y h:i A', strtotime($value)) : null;
    }

    public function getDeletedAtAttribute($value): ?string
    {
        return $value ? date('d/m/Y h:i A', strtotime($value)) : null;
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'super-admin' => 'Super Admin',
            'admin'       => 'Admin',
            'cashier'     => 'Cashier',
            default       => ucfirst($this->role ?? 'Unknown'),
        };
    }
}