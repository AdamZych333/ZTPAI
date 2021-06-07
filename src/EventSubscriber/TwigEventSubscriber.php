<?php

namespace App\EventSubscriber;

use App\Repository\MemeRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
    private $twig;
    private $memeRepository;

    public function __construct(Environment $twig, MemeRepository $memeRepository)
    {
        $this->twig = $twig;
        $this->memeRepository = $memeRepository;
    }


    public function onControllerEvent(ControllerEvent $event)
    {
        $this->twig->addGlobal('meme', $this->memeRepository->findAll());
    }

    public static function getSubscribedEvents()
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}
