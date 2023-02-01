<?php

namespace App\Library\Artcustomer\ApiUnit\Client;

use App\Library\Artcustomer\ApiUnit\Http\CurlApiRequest;
use App\Library\Artcustomer\ApiUnit\Http\IApiResponse;
use App\Library\Artcustomer\ApiUnit\Http\IApiRequest;
use App\Library\Artcustomer\ApiUnit\Mock\CurlApiMock;
use App\Library\Artcustomer\ApiUnit\Mock\IApiMock;
use App\Library\Artcustomer\ApiUnit\Utils\ApiMethodTypes;
use App\Library\Artcustomer\ApiUnit\Utils\ApiEventTypes;
use App\Library\Artcustomer\ApiUnit\Utils\ApiListenerTypes;

class CurlApiClient extends AbstractApiClient {

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var array
     */
    protected $options = [];

    /**
     * CurlApiClient constructor.
     * @param array $params
     */
    public function __construct(array $params) {
        parent::__construct($params);

        $this->requestClassName = CurlApiRequest::class;
        $this->config = [
            'enableProxy' => FALSE
        ];
        $this->options = [
            CURLOPT_VERBOSE => 0
        ];
    }

    /**
     * Initialize Client
     */
    public function initialize(): void {
        
    }

    /**
     * Setup Curl Client
     */
    protected function setupClient(): void {
        
    }

    /**
     * Do sync request
     * @param IApiRequest $request
     * @return IApiResponse
     */
    protected function doRequest(IApiRequest $request): IApiResponse {
        $this->triggerEvent(ApiEventTypes::PRE_EXECUTE, $request);
        $this->triggerExternalEvent(ApiEventTypes::PRE_EXECUTE, $request);
        $this->triggerListener(ApiListenerTypes::PRE_EXECUTE, $request);

        /** @var CurlApiMock $mock */
        $mock = $this->applyMock($request);

        if (NULL !== $mock) {
            $result = [
                'status' => $mock->getStatus(),
                'result' => $mock->getContent(),
                'error' => FALSE,
                'message' => ''
            ];
        } else {
            $result = $this->executeCurl($request);
        }

        if (FALSE === $result['error']) {
            $statusCode = $result['status'] !== 0 ? $result['status'] : 500;
            $content = $result['result'] ?? NULL;
            $response = $this->responseFactory->create($statusCode, '', '', $content, $request->getCustomData());

            $this->applyNormalizer($request, $response);
            $this->triggerEvent(ApiEventTypes::EXECUTION_SUCCESS, $request, $response);
            $this->triggerExternalEvent(ApiEventTypes::N_SUCCESS, $request, $response);
        } else {
            $response = $this->responseFactory->create($result['status'], '', $result['message'], NULL, $request->getCustomData());

            $this->triggerEvent(ApiEventTypes::EXECUTION_ERROR, $request, $response);
            $this->triggerExternalEvent(ApiEventTypes::N_ERROR, $request, $response);
        }

        $this->triggerEvent(ApiEventTypes::POST_EXECUTE, $request, $response);
        $this->triggerExternalEvent(ApiEventTypes::POST_EXECUTE, $request, $response);
        $this->triggerListener(ApiListenerTypes::POST_EXECUTE, $request);

        return $response;
    }

    /**
     * Do async request
     * @param IApiRequest $request
     * @return IApiResponse
     */
    protected function doRequestAsync(IApiRequest $request): IApiResponse {
        // Not implemented yet...

        $response = $this->responseFactory->create(500);

        return $response;
    }

    /**
     * Apply mock if available
     * @param IApiRequest $request
     * @return null|IApiMock
     */
    protected function applyMock(IApiRequest $request): ?IApiMock {
        if (TRUE === $this->enableMocks) {
            /** @var IApiMock $mock */
            $mock = $this->getAvailableMock($request);

            if (NULL !== $mock && $mock instanceof CurlApiMock) {
                return $mock;
            }
        }

        return NULL;
    }

    /**
     * Execute curl
     * @param CurlApiRequest $request
     * @return array
     */
    private function executeCurl(CurlApiRequest $request) {
        $curlOptions = array_replace($this->options, $request->getOptions());
        $curlResult = [
            'status' => 500,
            'result' => NULL,
            'error' => TRUE,
            'message' => ''
        ];
        $result = FALSE;
        $ch = $request->getCurlResource();

        curl_setopt($ch, CURLOPT_URL, $request->getUri());
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request->getMethod());
        curl_setopt($ch, CURLOPT_HEADER, TRUE);

        switch ($request->getMethod()) {
            case ApiMethodTypes::GET:
                curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
                break;

            case ApiMethodTypes::POST:
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, ApiMethodTypes::POST);

                if (NULL !== $request->getBody()) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $request->getBody());
                }
                break;

            case ApiMethodTypes::PUT:
                if (NULL !== $request->getBody()) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $request->getBody());
                }
                break;

            default:
                break;
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->prepareHeader($request->getHeaders()));

        if (TRUE === $this->config['enableProxy']) {
            curl_setopt($ch, CURLOPT_PROXY, $this->config['proxy']);
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->config['proxyuserpwd']);
        }

        foreach ($curlOptions as $key => $value) {
            curl_setopt($ch, $key, $value);
        }

        try {
            $result = curl_exec($ch);
        } catch (\Exception $e) {
            $curlResult['status'] = 500;
            $curlResult['result'] = NULL;
            $curlResult['error'] = TRUE;
            $curlResult['message'] = $e->getMessage();
        }

        if (FALSE !== $result) {
            $curlResult['status'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlResult['result'] = $result;
            $curlResult['error'] = FALSE;
        } else {
            $curlError = curl_error($ch);
            $errorMessage = 'Unknown';

            if ('' !== $curlError) {
                $errorMessage = $curlError;
            }

            $curlResult['status'] = 500;
            $curlResult['result'] = NULL;
            $curlResult['error'] = TRUE;
            $curlResult['message'] = sprintf('Curl error : %s', $errorMessage);
        }

        $curlResult['info'] = curl_getinfo($ch);

        curl_close($ch);

        return $curlResult;
    }

    private function prepareHeader(array $headers): array {
        $header = [];

        foreach($headers as $key => $value) {
            $header[] = sprintf('%s:%s', $key, $value);
        }

        return $header;
    }

}
