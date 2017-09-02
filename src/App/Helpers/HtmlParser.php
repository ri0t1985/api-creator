<?php

namespace App\Helpers;

use Sunra\PhpSimple\HtmlDomParser;
use App\Entities\Endpoint;
use App\Entities\Website;
use App\Entities\Selector;
use App\Parsers;

class HtmlParser
{
    /**
     * @param Selector[] $selectors
     * @param string $htmlSource
     * @return array
     * @throws \Exception
     */
    public function parse($selectors, $htmlSource)
    {
        // get the selectors with which we will be able to point out
        // relevant data on the target website

        $html = HtmlDomParser::str_get_html($htmlSource);

        if (false === $html)
        {
            throw new \Exception('Unable to parse HTML!');
        }

        $records = [];
        foreach ($selectors as $selector) {

            switch ($selector->getType())
            {
                case Selector::TYPE_XPATH:
                    $parser = new Parsers\Xpath($html);
                    break;
                case Selector::TYPE_REGEX:
                    $parser = new Parsers\Regex($htmlSource);
                    break;
                case Selector::TYPE_CSS:
                    $parser = new Parsers\Css($html);
                    break;
                default:
                    throw new \InvalidArgumentException('Invalid selector type supplied: ' . $selector->getType());
            }

            foreach ($parser->process($selector->getSelector()) as $key => $element) {

                if (isset($element->src) && !empty($element->src))
                {
                    $src = trim(strip_tags((string)$element->src));

                    $records[$key][$selector->getAlias()] = $src;
                }
                else {
                    $records[$key][$selector->getAlias()] = trim(strip_tags((string)$element));
                }
            }
        }

        return $records;
    }
}