<?php

declare(strict_types=1);

namespace App\Services\Rake;

use DonatelloZa\RakePlus\RakePlus;

class RakeService extends RakePlus
{
    public function make(string $text): RakePlus
    {
        return self::create($text, self::locale(), 3);
    }

    public static function locale(): string
    {
        $locale = config('app.rake_locale');

        return match ($locale) {
            'uk_UA' => resource_path('rake/uk_UA.php'),
            default => $locale
        };
    }
}
