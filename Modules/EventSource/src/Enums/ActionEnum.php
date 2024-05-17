<?php

namespace EventSource\Enums;

enum ActionEnum: string
{
    case STORE = 'store';
    case UPDATE = 'update';
    case DELETE = 'delete';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
