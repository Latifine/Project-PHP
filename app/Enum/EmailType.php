<?php

namespace App\Enum;

enum EmailType: string
{
    case EMAIL_ADDRESS = 'admin@project-kvvrauw.be';
    case EMAIL_NAME = 'KVV Rauw Sport Mol';
    case TRAINING_CANCELLED = 'training_cancelled';
    case REGISTRATION_PAID = 'registration_paid';
    case REGISTRATION_PAID_REQUEST = 'registration_paid_request';

}
