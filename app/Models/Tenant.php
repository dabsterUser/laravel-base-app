<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    /**
     * Set the tenant slug dynamically.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tenant) {
            if (empty($tenant->slug)) {
                $tenant->slug = Str::slug($tenant->name);
            }
        });
    }

    /**
     * Relationship to Users.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relationship to Forms.
     */
    public function forms()
    {
        return $this->hasMany(Form::class);
    }
}
