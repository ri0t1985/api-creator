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
     * @return int
     */
    public function process($selector)
    {
       return preg_match_all('/'.$selector.'/ims', $this->html);
    }
}