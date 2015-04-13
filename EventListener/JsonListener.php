<?php

namespace tbn\JsonAnnotationBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use tbn\JsonAnnotationBundle\Event\JsonEvents;
use tbn\JsonAnnotationBundle\Event\JsonPreHookEvent;

/**
 * The JsonListener class handles the @Json annotation.
 *
 * @author Thomas Beaujean
 */
class JsonListener implements EventSubscriberInterface
{
    /**
     * Set the parameters for the response
     *
     * @param integer $exceptionCode
     * @param string $dataKey
     * @param string $exceptionMessageKey
     * @param string $successKey
     * @param string $postQueryBack
     * @param string $postQueryKey
     * @param string $kernelDebug
     */
    public function __construct($exceptionCode, $dataKey, $exceptionMessageKey, $successKey, $postQueryBack, $postQueryKey, $dispatcher, $kernelDebug)
    {
        $this->exceptionCode = $exceptionCode;
        $this->dataKey = $dataKey;
        $this->exceptionMessageKey = $exceptionMessageKey;
        $this->successKey = $successKey;
        $this->postQueryBack = $postQueryBack;
        $this->postQueryKey = $postQueryKey;
        $this->dispatcher = $dispatcher;
        $this->kernelDebug = $kernelDebug;
    }

    /**
     * Renders the template and initializes a new response object with the
     * rendered template content.
     *
     * @param GetResponseForControllerResultEvent $event A GetResponseForControllerResultEvent instance
     *
     * @return Response The json response
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();

        $parameters = $event->getControllerResult();

        //the controller set automatically an attribut _json if the request is a json
        if (!$template = $event->getRequest()->attributes->get('_json')) {
            return;
        }

        // send the prehook event
        $dispatcher = $this->dispatcher;
        $prehookEvent = new JsonPreHookEvent($event, $parameters);
        $dispatcher->dispatch(JsonEvents::JSON_PREHOOK, $prehookEvent);
        unset($prehookEvent);

        $jsonData[$this->successKey] = true;

        //the data is in an array
        if ($this->dataKey !== '') {
            $jsonData[$this->dataKey] = $parameters;
        } else {
            //the data is at the root
            $jsonData = array_merge($jsonData, $parameters);
        }

        //send back the post parameters
        if ($this->postQueryBack === true) {
            $postParametersRequest = $request->request->all();
            $jsonData[$this->postQueryKey] = $postParametersRequest;

            unset($postParametersRequest);
        }

        //encode json
        $json = json_encode($jsonData);

        $headers = $this->getJsonHeaders();

        $event->setResponse(new Response($json, 200, $headers));
    }

    /**
     * Get the headers of a json response
     *
     * @return multitype:string
     */
    protected function getJsonHeaders()
    {
        $headers = array();
        $headers['Content-Type'] = 'application/json; charset=utf-8';

        return $headers;
    }

    /**
     * On kernel exception, send a json response with a success to false
     *
     * @param GetResponseForExceptionEvent $event The event
     *
     * @return Response The json response
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        //the controller set automatically an attribut _json if the request is a json
        if (!$template = $event->getRequest()->attributes->get('_json')) {
            return;
        }

        //the request
        $request = $event->getRequest();

        $jsonData = array();
        $jsonData[$this->successKey] = false;

        //add exception
        $exception = $event->getException();
        $jsonData[$this->exceptionMessageKey] = $exception->getMessage();

        //do we debug the kernel
        if ($this->kernelDebug) {
            $jsonData['error_trace'] = $exception->getTrace();
        }

        $jsonData = $this->addPostParameters($jsonData, $request);

        $json = json_encode($jsonData);

        $headers = $this->getJsonHeaders();

        $response = new Response($json, $this->exceptionCode, $headers);
        $response->headers->set('X-Status-Code', $this->exceptionCode);//BUG sf2 https://github.com/symfony/symfony/pull/5043
        $event->setResponse($response);
    }

    /**
     * Add the post parameters if requested
     *
     * @param array $jsonData
     * @param Request $request
     */
    protected function addPostParameters($jsonData, Request $request)
    {
        //send back the post parameters
        if ($this->postQueryBack === true) {
            $postParametersRequest = $request->request->all();
            $jsonData[$this->postQueryKey] = $postParametersRequest;

            unset($postParametersRequest);
        }

        return $jsonData;
    }

    /**
     * List the subscribed events
     *
     * @return multitype:string multitype:string number
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::VIEW => 'onKernelView',
            KernelEvents::EXCEPTION => 'onKernelException'
        );
    }
}
