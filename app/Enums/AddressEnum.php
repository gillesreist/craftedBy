<?php
namespace App\Enums;

enum AddressEnum: int {
        case DELIVERY = 0;
        case BILLING = 1;
        case BOTH = 2;
}
