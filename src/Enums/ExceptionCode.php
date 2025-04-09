<?php

namespace Barronhsu15\InterviewTintint\Enums;

enum ExceptionCode: int
{
    case DatabaseFailed = 1;

    case FormMissingField = 2;

    case FormInvalidFormat = 3;
}
