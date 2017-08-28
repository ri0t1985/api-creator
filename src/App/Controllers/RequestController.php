<?php

namespace App\Controllers;

use App\Entities\Endpoint;
use App\Entities\Selector;
use App\Entities\Website;
use App\Services\DatabaseServiceContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestController. Used to
 * @package App\Controllers
 */
class RequestController
{

    /** @var DatabaseServiceContainer */
    protected $databaseServiceContainer;

    /**
     * RequestController constructor.
     * @param DatabaseServiceContainer $databaseServiceContainer
     */
    public function __construct(DatabaseServiceContainer $databaseServiceContainer)
    {
        $this->databaseServiceContainer = $databaseServiceContainer;
    }

    /**
     * Creates a new API call, which can then be called by calling /api/v1/<website>/<endpoint>
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $errors = $this->validateCreateRequest($request);
        if (!empty($errors)) {
            return new JsonResponse($errors, 400);
        }

        $entityManager = $this->databaseServiceContainer->getWebsiteService()->getEntityManager();
        $this->databaseServiceContainer->getConnection()->beginTransaction();
        $routesCreated = [];
        try {
            $website = $this->databaseServiceContainer->getWebsiteService()->getOneByName($request->get('website_name'));

            if (false === ($website instanceof Website)) {

                $website = new Website();
                $website
                    ->setName($request->get('website_name'))
                    ->setUrl($request->get('website_url'))
                    ->setUrlHash(md5($website->getUrl()));

                $entityManager->persist($website);
            }

            foreach ($request->get('endpoints') as $end_point_request) {

                $end_point = new Endpoint();
                $end_point
                    ->setName($end_point_request['name'])
                    ->setWebsite($website);

                $entityManager->persist($end_point);

                foreach ($end_point_request['selectors'] as $selector_request) {

                    $selector = new Selector();
                    $selector
                        ->setAlias($selector_request['alias'])
                        ->setSelector($selector_request['selector'])
                        ->setEndpoint($end_point);

                    $entityManager->persist($selector);
                }
                $routesCreated[] = 'Route successfully created: ' . $website->getName() . '/' . $end_point->getName();

            }
            $entityManager->flush();
            $this->databaseServiceContainer->getConnection()->commit();

        } catch (\Exception $e) {
            $this->databaseServiceContainer->getConnection()->rollBack();
            return new JsonResponse(['An error occurred while handling your request: ' . $e->getMessage()], 200);
        }

        return new JsonResponse($routesCreated, 200);
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

        if (false === ($website instanceof Website)) {
            return new JsonResponse(['No endpoint found for route: ' . $websiteName . '/' . $endpointName], 404);
        }

        $endpoint = $this->databaseServiceContainer->getEndPointService()->getOneByName($endpointName);
        if (false === ($endpoint instanceof Endpoint)) {
            return new JsonResponse(['No endpoint found for route: ' . $websiteName . '/' . $endpointName], 404);
        }

        $entityManager = $this->databaseServiceContainer->getWebsiteService()->getEntityManager();
        try {
            $this->databaseServiceContainer->getConnection()->beginTransaction();

            foreach($endpoint->getSelectors() as $selector)
            {
                $entityManager->remove($selector);
            }

            $entityManager->remove($endpoint);
            $entityManager->flush($endpoint);

            $entityManager->refresh($website);
            $endPoints = $website->getEndpoints();

            if (count($endPoints) === 0) {
                $entityManager->remove($website);
                $entityManager->flush($website);
            }

            $this->databaseServiceContainer->getConnection()->commit();
        } catch (\Exception $e) {
            $this->databaseServiceContainer->getConnection()->rollBack();
            return new JsonResponse(['Failed to delete route with name: ' . $websiteName . '/' . $endpointName. ': ' . $e->getMessage() ], 500);
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