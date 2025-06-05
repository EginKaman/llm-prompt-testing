<?php

declare(strict_types=1);

namespace App\Services\Stemmer\Languages;

use Wamania\Snowball\Stemmer\Stem;

/**
 * Semple stemmer for ukrainian language.
 */
class Ukrainian extends Stem
{
    // http://uk.wikipedia.org/wiki/Голосний_звук
    // var $PERFECTIVEGROUND = '/((ив|ивши|ившись|ыв|ывши|ывшись((?<=[ая])(в|вши|вшись)))$/';
    private string $PERFECTIVEGROUND = '/(ив|ивши|ившись|ів|івши|івшись((?<=[ая|я])(в|вши|вшись)))$/u';
    private string $REFLEXIVE = '/(с[яьи])$/u'; // http://uk.wikipedia.org/wiki/Рефлексивне_дієслово
    private string $ADJECTIVE = '/(ими|ій|ий|а|е|ова|ове|ів|є|їй|єє|еє|я|ім|ем|им|ім|их|іх|ою|йми|іми|у|ю|ого|ому|ої)$/u'; // http://uk.wikipedia.org/wiki/Прикметник + http://wapedia.mobi/uk/Прикметник
    private string $PARTICIPLE = '/(ий|ого|ому|им|ім|а|ій|у|ою|ій|і|их|йми|их)$/u'; // http://uk.wikipedia.org/wiki/Дієприкметник
    private string $VERB = '/(сь|ся|ив|ать|ять|у|ю|ав|али|учи|ячи|вши|ши|е|ме|ати|яти|є)$/u'; // http://uk.wikipedia.org/wiki/Дієслово
    private string $NOUN = '/(а|ев|ов|е|ями|ами|еи|и|ей|ой|ий|й|иям|ям|ием|ем|ам|ом|о|у|ах|иях|ях|ы|ь|ию|ью|ю|ия|ья|я|і|ові|ї|ею|єю|ою|є|еві|ем|єм|ів|їв|\'ю)$/u'; // http://uk.wikipedia.org/wiki/Іменник
    private string $RVRE = '/^(.*?[аеиоуюяіїє])(.*)$/u';
    private string $DERIVATIONAL = '/[^аеиоуюяіїє][аеиоуюяіїє]+[^аеиоуюяіїє]+[аеиоуюяіїє].*(?<=о)сть?$/u';

    public function stem($word): string
    {
        $word = mb_strtolower($word);

        $stem = $word;

        do {
            if (! preg_match($this->RVRE, $word, $p)) {
                break;
            }

            [,$start, $RV] = $p;

            if ($RV === '' || $RV === '0') {
                break;
            }

            // Step 1
            if (! $this->s($RV, $this->PERFECTIVEGROUND, '')) {
                $this->s($RV, $this->REFLEXIVE, '');

                if ($this->s($RV, $this->ADJECTIVE, '')) {
                    $this->s($RV, $this->PARTICIPLE, '');
                } elseif (! $this->s($RV, $this->VERB, '')) {
                    $this->s($RV, $this->NOUN, '');
                }
            }

            // Step 2
            $this->s($RV, '/[и|i]$/u', '');

            // Step 3
            if ($this->m($RV, $this->DERIVATIONAL)) {
                $this->s($RV, '/сть?$/u', '');
            }

            // Step 4
            if (! $this->s($RV, '/ь$/u', '')) {
                $this->s($RV, '/ейше?/u', '');
                $this->s($RV, '/нн$/u', 'н');
            }

            $stem = $start.$RV;
            /** @phpstan-ignore-next-line */
        } while (false);

        return $stem;
    }

    private function s(?string &$s, string $re, string $to): bool
    {
        $orig = $s;
        $s = preg_replace($re, $to, $s);

        return $orig !== $s;
    }

    private function m(string $s, string $re): int|false
    {
        return preg_match($re, $s);
    }
}
