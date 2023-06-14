<?php

namespace App\Controller\Application;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author David
 */
abstract class AbstractApplicationController extends AbstractController
{

    /**
     * Constructor
     */
    public function __construct()
    {
        
    }
}
