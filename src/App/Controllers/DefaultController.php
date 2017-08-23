<?php
namespace App\Controllers;

use Silex\Controller;
use Sunra\PhpSimple\HtmlDomParser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class DefaultController
{

    protected $domSelectors =
        [
            '',
        ];


    protected $soccerMatches = [
       1 => [
            "id" => 1,
            "home-team" => "Liverpool",
            "visiting-team" => "Arsenal",
            "date" => "09-04-2018",
            "score" => "0-2",
        ],
        2 => [
            "id" => 2,
            "home-team" => "Manchester",
            "visiting-team" => "Arsenal",
            "date" => "09-02-2018",
            "score" => "0-2",
        ],
    ];


    /**
     * Returns all the soccer matches!
     *
     * @return JsonResponse
     */
    public function getSoccerMatches()
    {
        $html = HtmlDomParser::file_get_html(__DIR__.'/../../../web/src_website/index.html');

        foreach($html->find('TD') as $element)
            echo $element->src . '<br>';

        die;

//
//        var_dump($html);
//        die;
        return new JsonResponse($this->soccerMatches, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * Returns a specific soccer match
     *
     * @param $id
     * @return JsonResponse
     */
    public function getSoccerMatch($id)
    {

        if (isset($this->soccerMatches[$id]))
        {
            return new JsonResponse(
                $this->soccerMatches[$id], 200, ['Content-Type' => 'application/json']
            );
        }

        return new JsonResponse(['Resource with ID: ' . $id. ' not found'], 404, ['Content-Type' => 'application/json']);
    }

}