<?php

namespace EventSource\Models;

use EventSource\database\factories\EventSourceFactory;
use EventSource\Enums\ActionEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSource extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'action',
        'request_body',
    ];

    protected $casts = [
        'action' => ActionEnum::class,
        'request_body' => 'array',

    ];

    protected static function newFactory(): Factory
    {
        return EventSourceFactory::new();
    }
}
