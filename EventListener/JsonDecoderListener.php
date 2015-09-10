<?php

namespace tbn\JsonAnnotationBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * The JsonDecoderListener calls that have a content of json
 *
 * @author Thomas Beaujean
 */
class JsonDecoderListener implements EventSubscriberInterface
{
    /**
     *
     * @param GetResponseEvent $event
     *
     * @return Response The json response
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ('json' === $request->getContentType()) {
            $content = $request->getContent();
            if ($content !== null) {
                $json = json_decode($content, true);
                $request->request->add($json);
            }
        }
    }

    /**
     * List the subscribed events
     *
     * @return multitype:string multitype:string number
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => 'onKernelRequest',
        );
    }
}
