<?php
namespace tbn\JsonAnnotationBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

/**
 *
 * @author Thomas BEAUJEAN
 *
 */
class JsonPreHookEvent extends Event
{
    protected $event;
    protected $parameters;

    /**
     *
     * @param GetResponseForControllerResultEvent $event
     * @param array $parameters
     */
    public function __construct(GetResponseForControllerResultEvent $event, array $parameters)
    {
        $this->event = $event;
        $this->parameters = $parameters;
    }

    /**
     * Get the event
     *
     * @return GetResponseForControllerResultEvent
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Get the parameters returned by the controller
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}