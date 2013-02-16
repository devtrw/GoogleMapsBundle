<?php

/*
 * Copyright (c) Steven Nance <steven@devtrw.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Devtrw\Bundle\GoogleMapsBundle\Client;

use Devtrw\Bundle\GoogleMapsBundle\Entity\Directions\Request;
use Guzzle\Common\Collection;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * This is the guzzle client used for communication with the Google Maps API
 *
 * @author Steven Nance <steven@devtrw.com>
 */
class GoogleMapsClient extends Client
{
    /**
     * @var string
     */
    const DEFAULT_BASE_URL = '{scheme}://{endpoint}';

    /**
     * @var string
     */
    const DEFAULT_ENDPOINT = 'maps.googleapis.com';

    /**
     * @var string
     */
    const DEFAULT_FORMAT = 'json';

    /**
     * @var string
     */
    const DEFAULT_SCHEME = 'https';

    /**
     * Factory method to create a new GoogleMapsClient
     *
     * The following array keys and values are available options:
     * - endpoint: The endpoint for google maps api requests
     * - base_url: Base URL for google api, defaults to
     * - scheme:   URI scheme: http or https
     * - format:   The google api output format: json or xml
     *
     * @param array|Collection $config Configuration data
     *
     * @return self
     */
    public static function factory($config = array())
    {
        $default = [
            'base_url' => self::DEFAULT_BASE_URL,
            'endpoint' => self::DEFAULT_ENDPOINT,
            'format'   => self::DEFAULT_FORMAT,
            'scheme'   => self::DEFAULT_SCHEME
        ];
        $required = ['base_url', 'endpoint', 'format', 'scheme'];
        $config = Collection::fromConfig($config, $default, $required);

        $client = new self($config->get('base_url'), $config);

        $description = ServiceDescription::factory(__DIR__.'/client.json');
        $client->setDescription($description);

        return $client;
    }

    /**
     * @param Request $request
     *
     * @return array The response
     */
    public function getDirections(Request $request)
    {
        $params = [
            "origin"      => $request->getOrigin(),
            "destination" => $request->getDestination(),
            "format"      => $this->getConfig('format'),
            'sensor'      => $request->hasSensor() ? 'true' : 'false',
            'mode'        => $request->getMode()
        ];
        $waypointsString = $request->getWaypointsString();
        if (!empty($waypointsString)) {
            $params['waypoints'] = $waypointsString;
        }

        $command = $this->getCommand("GetDirections", $params);

        $response = $this->execute($command);

        return $response;
    }

    /**
     * Wraps the execute command and converts any response exceptions to symfony
     * http exceptions
     *
     * @param array|\Guzzle\Service\Command\CommandInterface $command
     *
     * @throws AccessDeniedHttpException
     * @throws HttpException
     * @throws NotFoundHttpException
     *
     * @return mixed
     */
    public function execute($command)
    {
        try {
            return parent::execute($command);
        } catch (BadResponseException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            switch ($statusCode) {
                case 403:
                    throw new AccessDeniedHttpException($e->getMessage(), $e);
                    break;

                case 404:
                    throw new NotFoundHttpException('The Google Maps API could not be reached.', $e);
                    break;

                default:
                    throw new HttpException($statusCode, $e->getMessage(), $e);
            }
        }
    }
}
