<?php

namespace App\Controller\Api\OpenAI;

use App\Controller\Api\AbstractApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/files")
 *
 * @author David
 */
class OpenAIFileController extends AbstractApiController
{

    /**
     * @Route("/", name="openai_files_getall", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function getAll(Request $request): Response
    {
        $response = $this->openAIService->getApiGateway()->getFileConnector()->list();

        return $this->responseProxy($response);
    }

    /**
     * @Route("/upload_one", name="openai_files_uploadone", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function uploadOne(Request $request): Response
    {
        $content = $request->getContent();
        $params = [];

        if (!empty($content)) {
            $params = json_decode($content, true);
        }

        $response = $this->openAIService->getApiGateway()->getFileConnector()->upload($params);

        return $this->responseProxy($response);
    }

    /**
     * @Route("/{fileid}", name="openai_files_getone", requirements={"fileid"="[a-z0-9]+"}, methods={"GET"})
     *
     * @param string $fileid
     * @param Request $request
     * @return Response
     */
    public function getOne(string $fileid, Request $request): Response
    {
        $response = $this->openAIService->getApiGateway()->getFileConnector()->get($fileid);

        return $this->responseProxy($response);
    }

    /**
     * @Route("/{fileid}/content", name="openai_files_getonecontent", requirements={"fileid"="[a-z0-9]+"}, methods={"GET"})
     *
     * @param string $fileid
     * @param Request $request
     * @return Response
     */
    public function getOneContent(string $fileid, Request $request): Response
    {
        $response = $this->openAIService->getApiGateway()->getFileConnector()->getContent($fileid);

        return $this->responseProxy($response);
    }

    /**
     * @Route("/{fileid}", name="openai_files_deleteone", requirements={"fileid"="[a-z0-9]+"}, methods={"DELETE"})
     *
     * @param string $fileid
     * @param Request $request
     * @return Response
     */
    public function deleteOne(string $fileid, Request $request): Response
    {
        $response = $this->openAIService->getApiGateway()->getFileConnector()->delete($fileid);

        return $this->responseProxy($response);
    }
}
