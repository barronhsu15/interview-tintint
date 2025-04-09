<?php

namespace Barronhsu15\InterviewTintint\Enums;

enum ExceptionCode: int
{
    case DatabaseFailed = 1;

    case FormMissingField = 2;

    case FormInvalidFormat = 3;

    case OrderEmptyItem = 4;

    case OrderNegativeAmount = 5;

    case OrderItemNegativeAmount = 6;

    case OrderDatetimeMismatch = 7;

    case OrderAmountMismatch = 8;
}
