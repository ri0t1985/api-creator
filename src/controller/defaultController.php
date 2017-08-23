<?php
namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class defaultController
{

    public function getResponse($id)
    {
        return new JsonResponse([]);
    }

}