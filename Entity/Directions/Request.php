<?php

/*
 * Copyright (c) Steven Nance <steven@devtrw.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Devtrw\Bundle\GoogleMapsBundle\Entity\Directions;

use InvalidArgumentException;

/**
 * A request object for the Directions::getDirections() method
 *
 * @author Steven Nance <steven@devtrw.com>
 */
class Request
{
    /**
     * Use to set the travel mode to bicycling
     *
     * @var string
     */
    const MODE_BICYCLING = 'bicycling';

    /**
     * Use to set the travel mode to driving, this is the default.
     *
     * @var string
     */
    const MODE_DRIVING   = 'driving';

    /**
     * Use to set the travel mode to transit
     */
    const MODE_TRANSIT = 'transit';

    /**
     * Use to set the travel mode to walking
     *
     * @var string
     */
    const MODE_WALKING   = 'walking';

    /**
     * @var string
     */
    private $origin;

    /**
     * @var string
     */
    private $destination;

    /**
     * @var bool
     */
    private $hasSensor;

    /**
     * @var string
     */
    private $mode;

    /**
     * @var array
     */
    private $waypoints;

    /**
     * @var bool
     */
    private $optimizeWaypoints;

    /**
     * @param string $origin
     * @param string $destination
     * @param bool   $sensor
     * @param string $mode
     */
    public function __construct($origin, $destination, $sensor = false, $mode = self::MODE_DRIVING)
    {
        $this->setOrigin($origin);
        $this->setDestination($destination);
        $this->setHasSensor($sensor);
        $this->setMode($mode);
        $this->waypoints = [];
        $this->optimizeWaypoints(false);
    }

    /**
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @param string $origin
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;
    }

    /**
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param string $destination
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
    }

    /**
     * @return boolean
     */
    public function hasSensor()
    {
        return $this->hasSensor;
    }

    /**
     * @param boolean $hasSensor
     */
    public function setHasSensor($hasSensor = true)
    {
        $this->hasSensor = (bool) $hasSensor;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param string $mode
     *
     * @throws InvalidArgumentException
     *
     * @uses Directions::MODE_BICYCLING
     * @uses Directions::MODE_DRIVING
     * @uses Directions::MODE_TRANSIT
     * @uses Directions::MODE_WALKING
     */
    public function setMode($mode)
    {
        $validModes = [
            self::MODE_BICYCLING,
            self::MODE_DRIVING,
            self::MODE_TRANSIT,
            self::MODE_WALKING
        ];
        if (!in_array($mode, $validModes)) {
            throw new InvalidArgumentException();
        } elseif ($mode === self::MODE_TRANSIT) {
            throw new InvalidArgumentException('The transit mode is not yet supported.');
        }
        $this->mode = $mode;
    }

    /**
     * @return array
     */
    public function getWaypoints()
    {
        return $this->waypoints;
    }

    /**
     * @param array $waypoints
     */
    public function setWaypoints($waypoints)
    {
        $this->waypoints = $waypoints;
    }

    /**
     * @param string $waypoint
     */
    public function addWaypoint($waypoint)
    {
        $this->waypoints[] = $waypoint;
    }

    /**
     * Clears all waypoints for the request
     */
    public function clearWaypoints()
    {
        $this->waypoints = [];
    }

    /**
     * @param bool $optimize
     */
    public function optimizeWaypoints($optimize = true)
    {
        $this->optimizeWaypoints = (bool) $optimize;
    }

    /**
     * @return bool
     */
    public function hasOptimizeWaypoints()
    {
        return (bool) $this->optimizeWaypoints;
    }

    /**
     * Formats and returns the waypoints string
     *
     * @return string
     */
    public function getWaypointsString()
    {
        $waypoints = $this->getWaypoints();

        if ($this->hasOptimizeWaypoints() && !empty($waypoints)) {
            array_unshift($waypoints, 'optimize:true');
        }

        return implode($waypoints, '|');
    }
}
