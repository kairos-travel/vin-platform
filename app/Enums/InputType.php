<?php

namespace App\Enums;

enum InputType: string
{
    case VIN = 'vin';
    case GRZ = 'grz';
    case STS = 'sts';
}
