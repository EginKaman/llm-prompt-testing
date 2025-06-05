<?php

declare(strict_types=1);

namespace App\Services\Stemmer\Languages;

use Wamania\Snowball\Stemmer\Stem;

class NoStemmer extends Stem
{
    public function stem($word): string
    {
        return $word;
    }
}
