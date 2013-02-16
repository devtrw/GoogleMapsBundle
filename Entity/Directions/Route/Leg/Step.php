<?php

/*
 * Copyright (c) Steven Nance <steven@devtrw.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Devtrw\Bundle\GoogleMapsBundle\Entity\Directions\Route\Leg;;


/**
 * Maps the "Step" part of the reply from the Google Maps Directions API
 *
 * @author Steven Nance <steven@devtrw.com>
 *
 * @url https://developers.google.com/maps/documentation/directions/#DirectionsResponses
 */
class Step
{
    /**
     * Formatted instructions for this step, presented as an HTML text string.
     *
     * @var string
     */
    private $htmlInstructions;

    /**
     * The distance covered by this step until the next step.
     *
     * This field may be undefined if the distance is unknown.
     *
     * @var array
     */
    private $distance;

    /**
     * The typical time required to perform the step, until the next step.
     *
     * This field may be undefined if the distance is unknown.
     *
     * @var array
     */
    private $duration;

    /**
     *  The location of the starting point of this step, as a single
     *  set of lat and lng fields.
     *
     * @var array
     */
    private $startLocation;

    /**
     *  The location of the starting point of this step, as a single
     *  set of lat and lng fields.
     *
     * @var array
     */
    private $endLocation;

    /**
     * An array of encoded points that represent an approximate
     * (smoothed) path of the resulting directions
     *
     * @var array
     */
    private $polyline;

    /**
     * Assembles a step from an array of values
     *
     * @param array $stepArray
     *
     * @return Step
     */
    public static function assembleFromArray($stepArray)
    {
        $step = new Step();

        $step->htmlInstructions = $stepArray['html_instructions'];
        $step->distance         = $stepArray['distance'];
        $step->duration         = $stepArray['duration'];
        $step->startLocation    = $stepArray['start_location'];
        $step->endLocation      = $stepArray['end_location'];
        $step->polyline         = (array) $stepArray['polyline'];

        return $step;
    }

    /**
     * @return string
     */
    public function getHtmlInstructions()
    {
        return $this->htmlInstructions;
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
     * @return array
     */
    public function getPolyline()
    {
        return $this->polyline;
    }
}
