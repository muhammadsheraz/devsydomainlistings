<?php

namespace App\Enums;


enum DomainStatus: string
{
    const UPCOMING = 'Upcoming';
    const ACTIVE = 'Active';
    const SOLD = 'Sold';
    const CLOSED = 'Closed';
}
