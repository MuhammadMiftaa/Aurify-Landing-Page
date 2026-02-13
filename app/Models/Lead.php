<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Lead extends Model
{
    use LogsActivity;

    protected $fillable = [
        'nama',
        'whatsapp',
        'email',
        'lembaga',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama', 'whatsapp', 'email', 'lembaga'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Lead has been {$eventName}");
    }
}
