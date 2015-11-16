<?php

namespace tbn\JsonAnnotationBundle\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Translation\TranslatorInterface;

/**
 */
class AjaxAuthenticationListener
{
	protected $translator = null;

	/**
	 * @param TranslatorInterface $translator
	 */
	public function __construct(TranslatorInterface $translator)
	{
		$this->translator = $translator;
	}

    /**
     * Handles security related exceptions.
     *
     * @param GetResponseForExceptionEvent $event An GetResponseForExceptionEvent instance
     */
    public function onCoreException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $request = $event->getRequest();

        //is the request an ajax
        if ($request->isXmlHttpRequest() || $event->getRequest()->attributes->get('_json')) {
            //only authentication throw an error
            if ($exception instanceof AuthenticationException || $exception instanceof AccessDeniedException) {
                $response = $this->getErrorResponse();

                $event->setResponse($response);
            }
        }
    }

    /**
     * Get a response error
     * @return Response
     */
    protected function getErrorResponse()
    {
    	$message = $this->translator->trans('authentication.exception', [], 'JsonAnnotation');

        $jsonData = array(
            'sucess' => false,
            'message' => $message,
        );

        //encode json
        $json = json_encode($jsonData);

        $headers = array();
        $headers['Content-Type'] = 'application/json; charset=utf-8';

        $response = new Response($json, 403, $headers);
        $response->headers->set('X-Status-Code', 403);

        return $response;
    }
}
