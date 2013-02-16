<?php

/*
 * Copyright (c) Steven Nance <steven@devtrw.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Devtrw\Bundle\GoogleMapsBundle\Entity\Directions\Route;

use Devtrw\Bundle\GoogleMapsBundle\Entity\Directions\Route\Leg\Step;

/**
 * Maps the "Leg" part of the reply from the Google Maps Directions API
 *
 * @author Steven Nance <steven@devtrw.com>
 *
 * @link https://developers.google.com/maps/documentation/directions/#DirectionsResponses
 */
class Leg
{

    /**
     * An array of steps denoting information about each separate step of the
     * leg of the journey.
     *
     * @var Step[]
     */
    private $steps;

    /**
     * The total distance covered by this leg, as a field with the following elements:
     *
     *   - value: Indicates the distance in meters
     *   - text:  Contains a human-readable representation of the distance, displayed
     *            in units as used at the origin (or as overridden within the units
     *            parameter in the request). (For example, miles and feet will be
     *            used for any origin within the United States.) Note that regardless
     *            of what unit system is displayed as text, the distance.value field
     *            always contains a value expressed in meters.
     *
     * These fields may be absent if the distance is unknown.
     *
     * @var array
     */
    private $distance;

    /**
     * The total duration of this leg, as a field with the following elements:
     *   - value: Indicates the duration in seconds.
     *   - text:  Contains a human-readable representation of the duration.
     *
     * These fields may be absent if the duration is unknown.
     *
     * @var array
     */
    private $duration;

    /**
     *  The latitude/longitude coordinates of the origin of this leg. Because
     *  the Directions API calculates directions between locations by using
     *  the nearest transportation option (usually a road) at the start and end
     *  points, start_location may be different than the provided origin of this
     *  leg if, for example, a road is not near the origin.
     *
     * @var array
     */
    private $startLocation;

    /**
     * The latitude/longitude coordinates of the given destination of this leg.
     * Because the Directions API calculates directions between locations by
     * using the nearest transportation option (usually a road) at the start
     * and end points, end_location may be different than the provided destination
     * of this leg if, for example, a road is not near the destination.
     *
     * @var array
     */
    private $endLocation;

    /**
     * the human-readable address (typically a street address) reflecting
     * the start_location of this leg.
     *
     * @var string
     */
    private $startAddress;

    /**
     * The human-readable address (typically a street address) reflecting
     * the end_location of this leg.
     *
     * @var string
     */
    private $endAddress;

    /**
     * @param array $legArray
     *
     * @return Leg
     */
    public static function assembleFromArray($legArray)
    {
        $leg = new Leg();

        foreach ($legArray['steps'] as $step) {
            $leg->steps[] = Step::assembleFromArray($step);
        }

        $leg->distance      = $legArray['distance'];
        $leg->duration      = $legArray['duration'];
        $leg->startLocation = $legArray['start_location'];
        $leg->endLocation   = $legArray['end_location'];
        $leg->startAddress  = $legArray['start_address'];
        $leg->endAddress    = $legArray['end_address'];

        return $leg;
    }

    /**
     * @return Step[]
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * @return array
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * @return array
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @return array
     */
    public function getStartLocation()
    {
        return $this->startLocation;
    }

    /**
     * @return array
     */
    public function getEndLocation()
    {
        return $this->endLocation;
    }

    /**
     * @return string
     */
    public function getStartAddress()
    {
        return $this->startAddress;
    }

    /**
     * @return string
     */
    public function getEndAddress()
    {
        return $this->endAddress;
    }

}
