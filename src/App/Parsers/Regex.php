<?php

namespace App\Parsers;

class Regex implements ParserInterface
{
    /**
     * @var string
     */
    protected $html;

    /**
     * Regex constructor.
     * @param string $html
     */
    public function __construct($html)
    {
        $this->html = $html;
    }

    /**
     * @param $selector
     * @return array
     */
    public function process($selector)
    {
       preg_match_all('/'.$selector.'/', $this->html, $matches);
       if (isset($matches[1]))
       {
           return $matches[1];
       }

       return [];
    }
}