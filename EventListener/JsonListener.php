<?php

namespace thomasbeaujean\JsonAnnotationBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * The TemplateListener class handles the @Template annotation.
 *
 * @author Thomas Beaujean
 */
class JsonListener implements EventSubscriberInterface
{
    /**
     * Renders the template and initializes a new response object with the
     * rendered template content.
     *
     * @param GetResponseForControllerResultEvent $event A GetResponseForControllerResultEvent instance
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();
        $parameters = $event->getControllerResult();

        if (null === $parameters) {
            if (!$vars = $request->attributes->get('_template_vars')) {
                if (!$vars = $request->attributes->get('_template_default_vars')) {
                    return;
                }
            }

            $parameters = array();
            foreach ($vars as $var) {
                $parameters[$var] = $request->attributes->get($var);
            }
        }

        $jsonData['success'] = true;
        $jsonData['data'] = $parameters;

        $json = json_encode($jsonData);

        $headers = array();
        $headers['Content-Type'] = 'application/json; charset=utf-8';

        $event->setResponse(new Response($json, 200, $headers));
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        //the controller set automatically an attribut _json if the request is a json
        if (!$template = $event->getRequest()->attributes->get('_json')) {
            return;
        }

        $jsonData =  array();
        $jsonData['success'] = false;

        $exception = $event->getException();
        $jsonData['message'] = $exception->getMessage();

        $json = json_encode($jsonData);

        $headers = array();
        $headers['Content-Type'] = 'application/json; charset=utf-8';

        $response = new Response($json, 200, $headers);
        $response->headers->set('X-Status-Code', 200 );//BUG sf2 https://github.com/symfony/symfony/pull/5043
        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => array('onKernelController', -128),
            KernelEvents::VIEW => 'onKernelView',
            KernelEvents::EXCEPTION => 'onKernelException'
        );
    }
}
