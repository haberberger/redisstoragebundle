<?php

namespace Haberberger\Bundle\RedisStorageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('HaberbergerRedisStorageBundle:Default:index.html.twig', array('name' => $name));
    }
}
