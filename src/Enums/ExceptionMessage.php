<?php

namespace Barronhsu15\InterviewTintint\Enums;

enum ExceptionMessage: string
{
    case DatabaseFailed = 'The database query failed.';

    case FormMissingField = 'A required form field is missing.';

    case FormInvalidFormat = 'One or more form fields have an invalid format.';

    case OrderEmptyItem = 'The order must contain at least one item.';

    case OrderNegativeAmount = 'The total order amount cannot be negative.';

    case OrderItemNegativeAmount = 'One or more order items have a negative amount.';

    case OrderDatetimeMismatch = 'Order and item datetime are invalid.';

    case OrderAmountMismatch = 'The total order amount does not match the sum of the item amounts.';
}
