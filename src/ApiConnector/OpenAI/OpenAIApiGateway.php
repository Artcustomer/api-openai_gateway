<?php

namespace App\ApiConnector\OpenAI;

use Artcustomer\ApiUnit\Gateway\AbstractApiGateway;
use Artcustomer\ApiUnit\Http\IApiResponse;
use App\ApiConnector\OpenAI\Client\ApiClient;
use App\ApiConnector\OpenAI\Connector\AudioConnector;
use App\ApiConnector\OpenAI\Connector\ChatConnector;
use App\ApiConnector\OpenAI\Connector\CompletionConnector;
use App\ApiConnector\OpenAI\Connector\EditConnector;
use App\ApiConnector\OpenAI\Connector\EmbeddingConnector;
use App\ApiConnector\OpenAI\Connector\EngineConnector;
use App\ApiConnector\OpenAI\Connector\FileConnector;
use App\ApiConnector\OpenAI\Connector\FineTuneConnector;
use App\ApiConnector\OpenAI\Connector\ImageConnector;
use App\ApiConnector\OpenAI\Connector\ModelConnector;
use App\ApiConnector\OpenAI\Connector\ModerationConnector;
use App\ApiConnector\OpenAI\Utils\ApiInfos;

/**
 * @author David
 */
class OpenAIApiGateway extends AbstractApiGateway {

    private ModelConnector $modelConnector;
    private CompletionConnector $completionConnector;
    private ChatConnector $chatConnector;
    private EditConnector $editConnector;
    private ImageConnector $imageConnector;
    private EmbeddingConnector $embeddingConnector;
    private AudioConnector $audioConnector;
    private FileConnector $fileConnector;
    private FineTuneConnector $fineTuneConnector;
    private ModerationConnector $moderationConnector;
    private EngineConnector $engineConnector;

    private string $apiKey;
    private string $organisation;
    private bool $availability;

    /**
     * Constructor
     */
    public function __construct(string $apiKey, string $organisation, bool $availability) {
        $this->apiKey = $apiKey;
        $this->organisation = $organisation;
        $this->availability = $availability;

        $this->defineParams();

        parent::__construct(ApiClient::class, [$this->params]);
    }

    /**
     * Initialize
     * 
     * @return void
     */
    public function initialize(): void {
        $this->setupConnectors();

        $this->client->initialize();
    }

    /**
     * Test API
     * 
     * @return IApiResponse
     */
    public function test(): IApiResponse {
        return $this->modelConnector->list();
    }

    /**
     * Get ModelConnector instance
     */
    public function getModelConnector(): ModelConnector {
        return $this->modelConnector;
    }

    /**
     * Get CompletionConnector instance
     */
    public function getCompletionConnector(): CompletionConnector {
        return $this->completionConnector;
    }

    /**
     * Get ChatConnector instance
     */
    public function getChatConnector(): ChatConnector {
        return $this->chatConnector;
    }

    /**
     * Get EditConnector instance
     */
    public function getEditConnector(): EditConnector {
        return $this->editConnector;
    }

    /**
     * Get ImageConnector instance
     */
    public function getImageConnector(): ImageConnector {
        return $this->imageConnector;
    }

    /**
     * Get EmbeddingConnector instance
     */
    public function getEmbeddingConnector(): EmbeddingConnector {
        return $this->embeddingConnector;
    }

    /**
     * Get AudioConnector instance
     */
    public function getAudioConnector(): AudioConnector {
        return $this->audioConnector;
    }

    /**
     * Get FileConnector instance
     */
    public function getFileConnector(): FileConnector {
        return $this->fileConnector;
    }

    /**
     * Get FineTuneConnector instance
     */
    public function getFineTuneConnector(): FineTuneConnector {
        return $this->fineTuneConnector;
    }

    /**
     * Get ModerationConnector instance
     */
    public function getModerationConnector(): ModerationConnector {
        return $this->moderationConnector;
    }

    /**
     * Get EngineConnector instance
     */
    public function getEngineConnector(): EngineConnector {
        return $this->engineConnector;
    }

    /**
     * Setup connectors
     */
    private function setupConnectors(): void {
        $this->modelConnector = new ModelConnector($this->client);
        $this->completionConnector = new CompletionConnector($this->client);
        $this->chatConnector = new ChatConnector($this->client);
        $this->editConnector = new EditConnector($this->client);
        $this->imageConnector = new ImageConnector($this->client);
        $this->embeddingConnector = new EmbeddingConnector($this->client);
        $this->audioConnector = new AudioConnector($this->client);
        $this->fileConnector = new FileConnector($this->client);
        $this->fineTuneConnector = new FineTuneConnector($this->client);
        $this->moderationConnector = new ModerationConnector($this->client);
        $this->engineConnector = new EngineConnector($this->client);
    }

    /**
     * Define parameters
     * 
     * @return void
     */
    private function defineParams(): void {
        $this->params['api_name'] = ApiInfos::API_NAME;
        $this->params['api_version'] = ApiInfos::API_VERSION;
        $this->params['protocol'] = ApiInfos::PROTOCOL;
        $this->params['host'] = ApiInfos::HOST;
        $this->params['version'] = ApiInfos::VERSION;
        $this->params['api_key'] = $this->apiKey;
        $this->params['organisation'] = $this->organisation;
        $this->params['availability'] = $this->availability;
    }
}