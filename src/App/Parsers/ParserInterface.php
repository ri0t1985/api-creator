<?php

namespace App\Parsers;


interface ParserInterface
{

    public function process($selector);
}