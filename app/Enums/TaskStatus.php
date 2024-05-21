<?php

namespace App\Enums;

enum TaskStatus: string
{
    case Open = 'open';
    case Working = 'working';
    case Stucked = 'stucked';
    case Completed = 'completed';
}
