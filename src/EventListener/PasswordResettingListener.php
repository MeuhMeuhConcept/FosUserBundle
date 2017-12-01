<?php

namespace MMC\FosUserBundle\EventListener;

use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Listener responsible to change the redirection at the end of the password resetting.
 */
class PasswordResettingListener implements EventSubscriberInterface
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::RESETTING_RESET_SUCCESS => 'onPasswordResettingSuccess',
        ];
    }

    public function onPasswordResettingSuccess(FormEvent $event)
    {
        $event->setResponse(new RedirectResponse($this->url));
    }
}
