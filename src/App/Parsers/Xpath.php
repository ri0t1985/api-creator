<?php

namespace App\Parsers;

use simplehtmldom_1_5\simple_html_dom;

class Xpath implements ParserInterface
{
    /**
     * @var simple_html_dom
     */
    protected $html;

    /**
     * Regex constructor.
     * @param simple_html_dom $html
     */
    public function __construct(simple_html_dom $html)
    {
        $this->html = $html;
    }

    public function process($selector)
    {
        return $this->html->find($selector);
    }
}