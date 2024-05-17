<?php

namespace App\Enums;

enum TaskStatus: string
{
    case Open = 'open';
    case InProgress = 'in-progress';
    case Stucked = 'stucked';
    case Completed = 'completed';
}
