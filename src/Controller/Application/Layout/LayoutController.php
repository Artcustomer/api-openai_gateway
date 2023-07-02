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
            [
                'navigationData' => $this->getNavigationData()
            ]
        );
    }
    
    /**
     * Get navigation data
     *
     * @return array
     */
    private function getNavigationData(): array
    {
        $data = [
            'home' => [
                'title' => 'Home',
                'icon' => 'bi-grid',
                'path' => 'application_home_index'
            ],
            'chat-nav' => [
                'title' => 'Chat',
                'icon' => 'bi-journal-text',
                'childs' => [
                    'create_completion' => [
                        'title' => 'Create completion',
                        'path' => 'application_chat_create_completion'
                    ],
                    'converse' => [
                        'title' => 'Converse',
                        'path' => 'application_chat_converse'
                    ]
                ]
            ],
            'text-nav' => [
                'title' => 'Text',
                'icon' => 'bi-journal-text',
                'childs' => [
                    'prompt' => [
                        'title' => 'Prompt',
                        'path' => 'application_text_prompt'
                    ],
                    'translate' => [
                        'title' => 'Translate',
                        'path' => 'application_text_translate'
                    ]
                ]
            ],
            'images-nav' => [
                'title' => 'Images',
                'icon' => 'bi-gem',
                'childs' => [
                    'create' => [
                        'title' => 'Create',
                        'path' => 'application_image_create'
                    ]
                ]
            ],
            'tools-nav' => [
                'title' => 'Tools',
                'icon' => 'bi-tools',
                'childs' => [
                    'samples' => [
                        'title' => 'Prompts samples',
                        'path' => 'application_tools_promptssamples'
                    ]
                ]
            ]
        ];
        
        return $data;
    }
}
