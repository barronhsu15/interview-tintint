<?php

namespace Barronhsu15\InterviewTintint\Enums;

enum ExceptionMessage: string
{
    case DatabaseFailed = 'The database query failed.';

    case FormMissingField = 'A required form field is missing.';

    case FormInvalidFormat = 'One or more form fields have an invalid format.';
}
