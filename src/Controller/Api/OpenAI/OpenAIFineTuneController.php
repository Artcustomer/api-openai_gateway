<?php

namespace App\Controller\Api\OpenAI;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Api\AbstractApiController;

/**
 * @Route("/fine-tunes")
 * 
 * @author David
 */
class OpenAIFineTuneController extends AbstractApiController {

    /**
     * @Route("/create_one", name="openai_finetunes_createone", methods={"POST"})
     * 
     * @return Response
     */
    public function createOne(Request $request): Response {
        $content = $request->getContent();
        $params = [];

        if (!empty($content)) {
            $params = json_decode($content, TRUE);
        }

        $response = $this->openAIService->getApiGateway()->getFineTuneConnector()->create($params);

        return $this->responseProxy($response);
    }

    /**
     * @Route("/", name="openai_finetunes_getall", methods={"GET"})
     * 
     * @return Response
     */
    public function getAll(Request $request): Response {
        $response = $this->openAIService->getApiGateway()->getFineTuneConnector()->list();

        return $this->responseProxy($response);
    }

    /**
     * @Route("/{finetuneid}", name="openai_finetunes_getone", requirements={"finetuneid"="[a-z0-9]+"}, methods={"GET"})
     * 
     * @return Response
     */
    public function getOne(string $finetuneid, Request $request): Response {
        $response = $this->openAIService->getApiGateway()->getFineTuneConnector()->get($finetuneid);

        return $this->responseProxy($response);
    }

    /**
     * @Route("/{finetuneid}/cancel", name="openai_finetunes_cancelone", requirements={"finetuneid"="[a-z0-9]+"}, methods={"GET"})
     * 
     * @return Response
     */
    public function cancelOne(string $finetuneid, Request $request): Response {
        $response = $this->openAIService->getApiGateway()->getFineTuneConnector()->cancel($finetuneid);

        return $this->responseProxy($response);
    }

    /**
     * @Route("/{finetuneid}/events", name="openai_finetunes_getoneevents", requirements={"finetuneid"="[a-z0-9]+"}, methods={"GET"})
     * 
     * @return Response
     */
    public function getOneEvents(string $finetuneid, Request $request): Response {
        $response = $this->openAIService->getApiGateway()->getFineTuneConnector()->listEvents($finetuneid);

        return $this->responseProxy($response);
    }

    /**
     * @Route("/{finetuneid}", name="openai_finetunes_deleteone", requirements={"finetuneid"="[a-z0-9]+"}, methods={"DELETE"})
     * 
     * @return Response
     */
    public function deleteOne(string $finetuneid, Request $request): Response {
        $response = $this->openAIService->getApiGateway()->getFineTuneConnector()->get($finetuneid);

        return $this->responseProxy($response);
    }
}