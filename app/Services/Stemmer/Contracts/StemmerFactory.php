<?php

namespace App\Services\Stemmer\Contracts;

use Wamania\Snowball\Stemmer\Stemmer;

interface StemmerFactory
{
    public static function create(string $code): Stemmer;
    public static function getStemmerClassByLanguageCode(string $code): string;
}
