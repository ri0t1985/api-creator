<?php

namespace App\Controllers;

use Sunra\PhpSimple\HtmlDomParser;
use Symfony\Component\HttpFoundation\JsonResponse;


class DefaultController
{

    protected $domSelectors =
        [
            // selector                    // alias
            'td._RGPO__Datum'          => 'date',
            'td._RGPO__Aanvang'        => 'match_start',
            'td._RGPO__TeamT'          => 'home_team',
            'td._RGPO__TeamU'          => 'visiting_team',
            'td._RGPO__Scheidsrechter' => 'visiting_team',
            'td._RGPO__Info'           => 'info',
        ];
    protected $soccerTeams;

    /**
     * Returns all the soccer matches!
     *
     * @return JsonResponse
     */
    public function getSoccerMatches()
    {
        if (null === $this->soccerTeams)
        {
            $this->parseHtml();
        }
        
        return new JsonResponse($this->soccerTeams, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * Returns a specific soccer match
     *
     * @param $id
     * @return JsonResponse
     */
    public function getSoccerMatch($id)
    {
        if (null === $this->soccerTeams)
        {
            $this->parseHtml();
        }

        if (isset($this->soccerTeams[$id])) {
            return new JsonResponse(
                $this->soccerTeams[$id], 200, ['Content-Type' => 'application/json']
            );
        }

        return new JsonResponse(['Resource with ID: ' . $id . ' not found'], 404, ['Content-Type' => 'application/json']);
    }

    public function searchSoccerMatches($key, $value)
    {
        if (null === $this->soccerTeams)
        {
            $this->parseHtml();
        }


        $response = [];
        foreach ($this->soccerTeams as $soccerMatchArray)
        {
            if (key_exists($key, $soccerMatchArray) && $soccerMatchArray[$key] === $value)
            {
                $response[] = $soccerMatchArray;
            }
        }

        return new JsonResponse($response[], 200, ['Content-Type' => 'application/json']);
    }

    /**
     * Parses the HTML into a dom document, and processes all the dom selectors.
     */
    protected function parseHtml()
    {
        $html = HtmlDomParser::file_get_html(__DIR__ . '/../../../web/src_website/index.html');

        foreach ($this->domSelectors as $selector => $alias) {
            foreach ($html->find($selector) as $key => $element) {
                $this->soccerTeams[$key][$alias] = trim(strip_tags((string)$element));
            }
        }
    }
}