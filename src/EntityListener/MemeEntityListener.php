<?php


namespace App\EntityListener;


use App\Entity\Meme;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class MemeEntityListener
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Meme $meme, LifecycleEventArgs $event){
        $meme->computeSlug($this->slugger);
    }

    public function preUpdate(Meme $meme, LifecycleEventArgs $event){
        $meme->computeSlug($this->slugger);
    }
}