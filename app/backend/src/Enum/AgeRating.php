<?php
declare(strict_types=1);

namespace App\Enum;

enum AgeRating: int
{
    case Six = 6;
    case Twelve = 12;
    case Sixteen = 16;
    case Eighteen = 18;

    public static function getRatingForSearch(int $ageRating): array
    {
        $info = [
            AgeRating::Eighteen->value => [AgeRating::Six->value, AgeRating::Twelve->value, AgeRating::Sixteen->value, AgeRating::Eighteen->value],
            AgeRating::Sixteen->value => [AgeRating::Six->value, AgeRating::Twelve->value, AgeRating::Sixteen->value],
            AgeRating::Twelve->value => [AgeRating::Six->value, AgeRating::Twelve->value],
            AgeRating::Six->value => [AgeRating::Six->value],
            ];

        return $info[$ageRating] ?? [];
    }
}
