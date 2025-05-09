<?php

namespace App\Enums;

enum Status: string
{
    case Pending = 'pending';
    case Done = 'done';
    case Cancelled = 'cancelled';
}
