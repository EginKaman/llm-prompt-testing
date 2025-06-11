<?php

declare(strict_types=1);

namespace App\Services\Stemmer;

use App\Services\Stemmer\Contracts\StemmerFactory as StemmerFactoryContract;
use App\Services\Stemmer\Languages\NoStemmer;
use App\Services\Stemmer\Languages\Ukrainian;
use Illuminate\Support\Str;
use Wamania\Snowball\Stemmer\Catalan;
use Wamania\Snowball\Stemmer\Danish;
use Wamania\Snowball\Stemmer\Dutch;
use Wamania\Snowball\Stemmer\English;
use Wamania\Snowball\Stemmer\Finnish;
use Wamania\Snowball\Stemmer\French;
use Wamania\Snowball\Stemmer\German;
use Wamania\Snowball\Stemmer\Italian;
use Wamania\Snowball\Stemmer\Norwegian;
use Wamania\Snowball\Stemmer\Portuguese;
use Wamania\Snowball\Stemmer\Romanian;
use Wamania\Snowball\Stemmer\Russian;
use Wamania\Snowball\Stemmer\Spanish;
use Wamania\Snowball\Stemmer\Stemmer;
use Wamania\Snowball\Stemmer\Swedish;

class StemmerFactory implements StemmerFactoryContract
{
    public static function create(string $code): Stemmer
    {
        $code = Str::lower($code);

        $stemmerClass = self::getStemmerClassByLanguageCode($code);

        return new $stemmerClass();
    }

    public static function getStemmerClassByLanguageCode(string $code): string
    {
        return match ($code) {
            'ca', 'cat', 'catalan' => Catalan::class,
            'da', 'dan', 'danish' => Danish::class,
            'nl', 'dut', 'nld', 'dutch' => Dutch::class,
            'en', 'eng', 'english' => English::class,
            'fi', 'fin', 'finnish' => Finnish::class,
            'fr', 'fre', 'fra', 'french' => French::class,
            'de', 'deu', 'ger', 'german' => German::class,
            'it', 'ita', 'italian' => Italian::class,
            'no', 'nor', 'norwegian' => Norwegian::class,
            'pt', 'por', 'portuguese' => Portuguese::class,
            'ro', 'rum', 'ron', 'romanian' => Romanian::class,
            'ru', 'rus', 'russian' => Russian::class,
            'uk', 'ukr', 'ukrainian' => Ukrainian::class,
            'es', 'spa', 'spanish' => Spanish::class,
            'sv', 'swe', 'swedish' => Swedish::class,
            default => NoStemmer::class,
        };
    }
}
