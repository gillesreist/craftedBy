<?php
namespace App\Enums;

enum OrderStatusEnum: int {
        case WAITINGFORPAYMENT = 0;
        case PAYMENTVALIDATED = 1;
        case INPREPARATION = 2;
        case SHIPPED = 3;
        case CANCELED = 4;
        case DELIVERED = 5;
        case RETURNED = 6;
        case INCRAFTING = 7;
}
