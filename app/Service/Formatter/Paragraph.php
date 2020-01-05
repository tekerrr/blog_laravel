<?php


namespace App\Service\Formatter;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class Paragraph
{
    public function format(string $string): string
    {
        $paragraphs = $this->chunk($string);
        $paragraphs = $this->tag($paragraphs);

        return $paragraphs->implode(PHP_EOL);
    }

    protected function chunk(string $string): Collection
    {
        $string = str_replace(["\r\n", "\r", "\n"], PHP_EOL, trim($string));
        return collect(explode(PHP_EOL, $string));
    }

    protected function tag(Collection $paragraphs)
    {
        return $paragraphs->map(function ($paragraph) {
            $paragraph = Str::start($paragraph, '<p>');
            $paragraph = Str::finish($paragraph, '</p>');
            return $paragraph;
        });
    }
}
