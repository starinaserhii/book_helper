<?php
declare(strict_types=1);

namespace App\Enum;

enum AgeRating: int
{
    case Zero = 0;
    case Six = 6;
    case Twelve = 12;
    case Sixteen = 16;
    case Eighteen = 18;
}
