<?php
namespace App\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class DefaultController
{
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
        return new JsonResponse($this->soccerMatches);
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
                $this->soccerMatches[$id]
            );
        }

        return new JsonResponse(['Resource with ID: ' . $id. ' not found'], 404);
    }

}