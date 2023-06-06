<?php

namespace App\Controller\Application;

use App\ApiConnector\OpenAI\Enum\ResponseFormat;
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
        $inputSize = '';
        $inputSize = '';
        $imageUrl = '';
        $imageUrls = [];
        $errorMessage = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $inputPrompt = $data['prompt'];
            $inputSize = $data['size'];
            $inputNumber = $data['number'];
            $params = [
                'prompt' => $inputPrompt,
                'n' => $inputNumber,
                'size' => $inputSize,
                'response_format' => ResponseFormat::URL
            ];
            $response = $this->openAIService->getApiGateway()->getImageConnector()->create($params);

            if ($response->getStatusCode() === 200) {
                $content = json_decode((string)$response->getContent());
                
                $contentData = $content->data;
                $imageUrls = array_map(
                    function ($item) {
                        return $item->url;
                    },
                    $contentData
                );
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

        return $this->render(
            'application/image/create.html.twig',
            [
                'form' => $form,
                'imageUrl' => $imageUrl,
                'imageUrls' => $imageUrls,
                'inputPrompt' => $inputPrompt,
                'errorMessage' => $errorMessage,
            ]
        );
    }

}
