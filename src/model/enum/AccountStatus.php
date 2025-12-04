<?php

declare(strict_types=1);

enum AccountStatus : string
{
    case Active = 'Active';
    case Unverified = 'Unverified';
    case Banned = 'Banned';
}
