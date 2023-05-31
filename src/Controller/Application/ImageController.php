<?php

namespace App\Controller\Application;

use App\Form\Type\ImageCreateType;
use App\Service\OpenAIService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/image")
 * 
 * @author David
 */
class ImageController extends AbstractController {

    protected OpenAIService $openAIService;

    /**
     * Constructor
     */
    public function __construct(OpenAIService $openAIService) {
        $this->openAIService = $openAIService;
    }

    /**
     * @Route("/create", name="application_image_create", methods={"GET","POST"})
     * 
     * @return Response
     */
    public function create(Request $request): Response {
        $form = $this->createForm(ImageCreateType::class);
        $form->handleRequest($request);

        $inputPrompt = '';
        $imageUrl = '';
        $errorMessage = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $inputPrompt = $data['prompt'];
            $params = [
                'prompt' => $inputPrompt,
                'n' => 1,
                'size' => '512x512',
                'response_format' => 'url'
            ];
            $response = $this->openAIService->getApiGateway()->getImageConnector()->create($params);

            if ($response->getStatusCode() === 200) {
                $content = json_decode((string)$response->getContent());
                $imageUrl = $content->data[0]->url;
            } else {
                $errorMessage = $response->getMessage();

                if (empty($errorMessage)) {
                    $content = $response->getContent();

                    if (!empty($content)) {
                        $content = json_decode((string)$content);
                        $errorMessage = $content->error->message ?? '';
                    }
                }

                $errorMessage = !empty($errorMessage) ? $errorMessage : 'An error occurred';
            }
        }

        //dump($errorMessage);
        //exit;

        return $this->render(
            'application/image/create.html.twig',
            [
                'form' => $form,
                'imageUrl' => $imageUrl,
                'inputPrompt' => $inputPrompt,
                'errorMessage' => $errorMessage,
            ]
        );
    }

}
