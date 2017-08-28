<?php

namespace App\Controllers;

use App\Entities\Website;
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

    /**
     * Creates a new API call, which can then be called by calling /api/v1/<website>/<endpoint>
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $errors = $this->validateCreateRequest($request);
        if (!empty($errors)) {
            return new JsonResponse($errors, 400);
        }

        $this->databaseServiceContainer->getConnection()->beginTransaction();
        try {
            $website = $this->databaseServiceContainer->getWebsiteService()->getOneByName($request->get('website_name'));

            if (empty($website)) {
                $website['id'] = $this->databaseServiceContainer->getWebsiteService()->getUuid();
                $website['name'] = $request->get('website_name');
                $website['url'] = $request->get('website_url');
                $website['url_hash'] = md5($website['url']);
                $this->databaseServiceContainer->getWebsiteService()->save($website);
            }

            foreach ($request->get('endpoints') as $end_point_request) {
                $end_point['id'] = $this->databaseServiceContainer->getEndPointService()->getUuid();
                $end_point['name'] = $end_point_request['name'];
                $end_point['website_id '] = $website['id'];

                $this->databaseServiceContainer->getEndPointService()->save($end_point);

                foreach ($end_point_request['selectors'] as $selector_request) {
                    $selector['id'] = $this->databaseServiceContainer->getSelectorService()->getUuid();
                    $selector['alias'] = $selector_request['alias'];
                    $selector['selector'] = $selector_request['selector'];
                    $selector['endpoint_id'] = $end_point['id'];
                    $this->databaseServiceContainer->getSelectorService()->save($selector);
                }
            }
            $this->databaseServiceContainer->getConnection()->commit();

        } catch (\Exception $e) {
            $this->databaseServiceContainer->getConnection()->rollBack();
            return new JsonResponse(['An error occurred while handling your request: ' . $e->getMessage()], 200);
        }

        return new JsonResponse(['successfully created route with name: ' . $website['name']] . '/' . $end_point['name'], 200);
    }


    /**
     * // TODO: implement this call.
     *
     * @param Request $request
     * @param string $id (uuid)
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $this->validateUpdateRequest($request);
        return new JsonResponse(['successfully updated route with id: ' . $id], 200);
    }

    /**
     * @param string $websiteName
     * @param string $endpointName
     *
     * @return JsonResponse
     */
    public function delete($websiteName, $endpointName)
    {
        $website = $this->databaseServiceContainer->getWebsiteService()->getOneByName($websiteName);

        if (empty($website)) {
            return new JsonResponse(['No endpoint found for route: ' . $websiteName . '/' . $endpointName], 404);
        }

        $endpoint = $this->databaseServiceContainer->getEndPointService()->getOneByName($endpointName);
        if (empty($endpoint)) {
            return new JsonResponse(['No endpoint found for route: ' . $websiteName . '/' . $endpointName], 404);
        }

        try {
            $this->databaseServiceContainer->getConnection()->beginTransaction();

            $selectors = $this->databaseServiceContainer->getSelectorService()->getAllByWebsiteIdAndEndpointId($website['id'], $endpoint['id']);
            foreach ($selectors as $selector) {
                $this->databaseServiceContainer->getSelectorService()->delete($selector['id']);
            }

            $this->databaseServiceContainer->getEndPointService()->delete($endpoint['id']);

            // if there are no endpoints left, delete the website.
            $endPoints = $this->databaseServiceContainer->getEndPointService()->getAllByWebsiteId($website['id']);
            if (empty($endPoints)) {
                $this->databaseServiceContainer->getWebsiteService()->delete($website['id']);
            }

            $this->databaseServiceContainer->getConnection()->commit();
        } catch (\Exception $e) {
            $this->databaseServiceContainer->getConnection()->rollBack();
            return new JsonResponse(['Failed to delete route with name: '.$websiteName .'/' . $endpointName], 500);
        }

        return new JsonResponse(['successfully deleted route with name: ' . $websiteName . '/' . $endpointName], 200);
    }

    /**
     * Attempts to check if the request is valid before it can be submitted.
     *
     * @param Request $request
     *
     * @return array A list of errors, empty if none are found
     */
    protected function validateCreateRequest(Request $request)
    {
        $errors = [];

        if (empty($request->get('website_name'))) {
            $errors['website_name'] = 'Should be specified';
        }

        if (empty($request->get('website_url'))) {
            $errors['website_url'] = 'Should be specified';
        }

        if (empty($request->get('endpoints'))) {
            $errors['endpoints'] = 'Should specify atleast one endpoint';
        }

        foreach ($request->get('endpoints') as $key => $end_point_request) {
            if (empty($end_point_request['name']) && !is_string($end_point_request['string'])) {
                $errors['endpoints'][$key]['name'] = 'Cannot be empty and should be string!';
            }

            // TODO: verify if end point does not exist, just for a website. Not just for any website.
            $end_point = $this->databaseServiceContainer->getEndPointService()->getOneByName($end_point_request['name']);
            if (!empty($end_point)) {
                $errors['endpoints'][$key]['selectors'] = 'End point already exists!';
                continue;
            }

            if (empty($end_point_request['selectors'])) {
                $errors['endpoints'][$key]['selectors'] = 'Should atleast specify one selector';
            }
            foreach ($end_point_request['selectors'] as $k => $selector_request) {
                if (empty($selector_request['alias']) && !is_string($selector_request['alias'])) {
                    $errors['endpoints'][$key]['selectors'][$k] = 'Cannot be empty and should be string!';
                }
                if (empty($selector_request['selector']) && !is_string($selector_request['selector'])) {
                    $errors['endpoints'][$key]['selectors'][$k] = 'Cannot be empty and should be string!';
                }
            }
        }

        return $errors;
    }

    protected function validateUpdateRequest($request)
    {
        // TODO: implement this.
    }
}