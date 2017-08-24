<?php

namespace App\Controllers;

use App\Services\DatabaseServiceContainer;
use App\Services\WebsiteService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class RequestController
{

    /** @var WebsiteService */
    protected $databaseServiceContainer;

    public function __construct(DatabaseServiceContainer $databaseServiceContainer)
    {
        $this->databaseServiceContainer = $databaseServiceContainer;
    }


    public function create(Request $request)
    {
        $errors = $this->validateCreateRequest($request);
        if (!empty($errors))
        {
            return new JsonResponse($errors, 400);
        }

        $this->databaseServiceContainer->getConnection()->beginTransaction();
        try
        {
            $website['id']   = $this->databaseServiceContainer->getWebsiteService()->getUuid();
            $website['name'] = $request->get('website_name');
            $website['url']  = $request->get('website_url');
            $website['url_hash'] = md5($website['url']);


            $this->databaseServiceContainer->getWebsiteService()->save($website);

            foreach ($request->get('endpoints') as $end_point_request)
            {
                $end_point['id']          = $this->databaseServiceContainer->getEndPointService()->getUuid();
                $end_point['name']        = $end_point_request['name'];
                $end_point['website_id '] = $website['id'];

                $this->databaseServiceContainer->getEndPointService()->save($end_point);

                foreach ($end_point_request['selectors'] as $selector_request)
                {
                    $selector['id']       = $this->databaseServiceContainer->getSelectorService()->getUuid();
                    $selector['alias']    = $selector_request['alias'];
                    $selector['selector'] = $selector_request['selector'];
                    $selector['endpoint_id'] = $end_point['id'];
                    $this->databaseServiceContainer->getSelectorService()->save($selector);
                }
            }
            $this->databaseServiceContainer->getConnection()->commit();

        } catch (\Exception $e)
        {
            $this->databaseServiceContainer->getConnection()->rollBack();
            return new JsonResponse(['An error occurred while handling your request: '. $e->getMessage()], 200);

        }


        return new JsonResponse(['successfully created'], 200);
    }


    public function update(Request $request, $id)
    {

        $this->validateUpdateRequest($request);
        return new JsonResponse(['successfully updated call with id: '.$id], 200);
    }

    public function delete($id)
    {
        return new JsonResponse(['successfully deleted call with id: '.$id], 200);
    }

    protected function validateCreateRequest(Request $request)
    {
        $errors = [];

        if (empty($request->get('website_name')))
        {
           $errors['website_name'] = 'Should be specified';
        }

        if (empty($request->get('website_url'))){
            $errors['website_url'] = 'Should be specified';
        }

        if (empty($request->get('endpoints'))) {
            $errors['endpoints'] = 'Should specify atleast one endpoint';
        }

        foreach ($request->get('endpoints') as $key => $end_point_request)
        {
            if (empty($end_point_request['name']) && !is_string($end_point_request['string']))
            {
                $errors['endpoints'][$key]['name'] = 'Cannot be empty and should be string!';
            }

            if (empty($end_point_request['selectors']))
            {
                $errors['endpoints'][$key]['selectors'] = 'Should atleast specify one selector';
            }
            foreach ($end_point_request['selectors'] as $k => $selector_request)
            {
                if (empty($selector_request['alias']) && !is_string($selector_request['alias']))
                {
                    $errors['endpoints'][$key]['selectors'][$k] = 'Cannot be empty and should be string!';
                }
                if (empty($selector_request['selector']) && !is_string($selector_request['selector']))
                {
                    $errors['endpoints'][$key]['selectors'][$k] = 'Cannot be empty and should be string!';
                }
            }
        }

        return $errors;
    }

    protected function validateUpdateRequest($request)
    {

    }
}