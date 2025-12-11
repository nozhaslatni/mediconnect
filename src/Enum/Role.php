<?php

namespace App\Enum;

enum Role: string
{
    case ROLE_ADMIN = 'ROLE_ADMIN';
    case ROLE_USER = 'ROLE_USER';
    case ROLE_DOCTOR = 'ROLE_DOCTOR';
}
