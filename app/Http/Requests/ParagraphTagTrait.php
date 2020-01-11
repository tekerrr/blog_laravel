<?php

namespace App\Http\Requests;

use App\Service\Formatter\Paragraph;

trait ParagraphTagTrait
{
    public function tag(string $attribute) : string
    {
        return $this->has('paragraph_tag') ? app(Paragraph::class)->format($attribute) : $attribute;
    }
}
