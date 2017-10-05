<?php

namespace App\Helpers;

use App\Entities\SelectorOption;
use Sunra\PhpSimple\HtmlDomParser;
use App\Entities\Selector;
use App\Parsers;

class HtmlParser
{
    protected $infoElements = [];

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


                $property = $selector->getOption(SelectorOption::OPTION_PROPERTY, false);
                if ($property)
                {
                    $value = (string)$element->$property;

                }
                else {
                    $value = (string)$element;
                }

                $stripHtml =  $selector->getOption(SelectorOption::OPTION_STRIP_HTML, true);
                if ($stripHtml)
                {
                    $value = strip_tags($value);
                }

                $trim =  $selector->getOption(SelectorOption::OPTION_TRIM, true);
                if ($trim)
                {
                    $value = trim($value);
                }

                if ($selector->getOption(SelectorOption::OPTION_INFO, false)) {
                    $this->infoElements[$key][$selector->getAlias()] = $value;

                } else {
                    $records[$key][$selector->getAlias()] = $value;
                }
            }
        }

        return $records;
    }

    public function getInfoElements()
    {
        return $this->infoElements;
    }
}