<?php

namespace App\Controller\Api\OpenAI;

use App\Controller\Api\AbstractApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/fine-tuning")
 *
 * @author David
 */
class OpenAIFineTuningController extends AbstractApiController
{

    /**
     * @Route("/create", name="openai_finetuning_createone", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \ReflectionException
     */
    public function createOne(Request $request): Response
    {
        $content = $request->getContent();
        $params = [];

        if (!empty($content)) {
            $params = json_decode($content, true);
        }

        $response = $this->openAIService->getApiGateway()->getFineTuningConnector()->create($params);

        return $this->responseProxy($response);
    }

    /**
     * @Route("/", name="openai_finetuning_getall", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     * @throws \ReflectionException
     */
    public function getAll(Request $request): Response
    {
        $response = $this->openAIService->getApiGateway()->getFineTuningConnector()->listJobs();

        return $this->responseProxy($response);
    }

    /**
     * @Route("/{finetuningjobid}", name="openai_finetuning_getone", requirements={"finetuningjobid"="[a-z0-9]+"}, methods={"GET"})
     *
     * @param string $finetuningjobid
     * @param Request $request
     * @return Response
     * @throws \ReflectionException
     */
    public function getOne(string $finetuningjobid, Request $request): Response
    {
        $response = $this->openAIService->getApiGateway()->getFineTuningConnector()->get($finetuningjobid);

        return $this->responseProxy($response);
    }

    /**
     * @Route("/{finetuningjobid}/cancel", name="openai_finetuning_cancelone", requirements={"finetuningjobid"="[a-z0-9]+"}, methods={"post"})
     *
     * @param string $finetuningjobid
     * @param Request $request
     * @return Response
     * @throws \ReflectionException
     */
    public function cancelOne(string $finetuningjobid, Request $request): Response
    {
        $response = $this->openAIService->getApiGateway()->getFineTuningConnector()->cancel($finetuningjobid);

        return $this->responseProxy($response);
    }

    /**
     * @Route("/{finetuningjobid}/events", name="openai_finetuning_getoneevents", requirements={"finetuningjobid"="[a-z0-9]+"}, methods={"GET"})
     *
     * @param string $finetuningjobid
     * @param Request $request
     * @return Response
     * @throws \ReflectionException
     */
    public function getOneEvents(string $finetuningjobid, Request $request): Response
    {
        $response = $this->openAIService->getApiGateway()->getFineTuningConnector()->listEvents($finetuningjobid);

        return $this->responseProxy($response);
    }
}
