<?php

namespace App\ApiConnector\OpenAI\Http;

use App\ApiConnector\OpenAI\Utils\ApiEndpoints;

/**
 * @author David
 */
class EditRequest extends ApiRequest {


    /**
     * Constructor
     */
    public function __construct(array $data = []) {
        parent::__construct();

        $this->initParams();
        $this->hydrate($data);
        $this->extendParams();
    }

    /**
	 * Build Uri
	 */
	protected function buildUri(): void {
        $this->uri = sprintf('%s/%s', $this->uriBase, ApiEndpoints::EDITS);
    }

    /**
     * Init parameters
     */
    private function initParams() {
        $this->body = $this->body ?? [];
    }

    /**
     * Extend parameters
     */
    private function extendParams() {
        
    }

}
