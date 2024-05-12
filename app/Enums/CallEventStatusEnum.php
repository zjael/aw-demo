<?php

namespace App\Enums;

enum CallEventStatusEnum: string {
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
}
