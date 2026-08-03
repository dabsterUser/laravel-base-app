<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Models\Traits\BelongsToTenant;

class Setting extends Model
{
    use LogsActivity, BelongsToTenant;

    protected $fillable = [
        'key',
        'value',
        'tenant_id',
    ];

    /**
     * Get setting value by key with optional default value.
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value by key.
     */
    public static function set(string $key, $value): self
    {
        $tenantId = auth()->check() ? auth()->user()->tenant_id : null;

        $setting = static::updateOrCreate(
            ['key' => $key, 'tenant_id' => $tenantId],
            ['value' => $value]
        );
        return $setting;
    }

    /**
     * Set multiple settings at once.
     */
    public static function setMany(array $settings): void
    {
        foreach ($settings as $key => $value) {
            static::set($key, $value);
        }
    }

    /**
     * Get the activity log options for the settings.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['key', 'value'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(function (string $eventName) {
                $maskedKey = e($this->key);
                return "Setting '{$maskedKey}' has been {$eventName}";
            });
    }
}
