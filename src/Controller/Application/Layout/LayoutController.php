<?php

namespace App\Controller\Application\Layout;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author David
 */
class LayoutController extends AbstractController
{

    /**
     * @return Response
     */
    public function header(): Response
    {
        return $this->render(
            'application/header.html.twig',
            []
        );
    }

    /**
     * @return Response
     */
    public function footer(): Response
    {
        return $this->render(
            'application/footer.html.twig',
            []
        );
    }

    /**
     * @return Response
     */
    public function sidebar(): Response
    {
        return $this->render(
            'application/sidebar.html.twig',
            []
        );
    }

}
