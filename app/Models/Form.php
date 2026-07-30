<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\Traits\BelongsToTenant;

class Form extends Model
{
    use HasFactory, LogsActivity, BelongsToTenant;

    protected $fillable = [
        'title',
        'description',
        'fields',
        'status',
        'user_id',
        'tenant_id',
    ];

    protected $casts = [
        'fields' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function submissions()
    {
        return $this->hasMany(FormSubmission::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'status', 'fields'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
