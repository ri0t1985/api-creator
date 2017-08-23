<?php
namespace App;

use Sunra\PhpSimple\HtmlDomParser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class Parser
{

    protected $dom_html;

    public function __construct($html)
    {
        $this->dom_html = $dom = HtmlDomParser::str_get_html($html);
    }


    public function parse($selectors = [])
    {
        $elements = [];
        foreach ($selectors as $selector)
        {
            $element = call_user_func( array( "simplehtmldom_1_5\\" . $selector, func_get_args() ) );
            var_dump($element);
            die;
        }

    }
}