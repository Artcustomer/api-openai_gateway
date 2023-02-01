<?php

namespace App\Library\Artcustomer\ApiUnit\Validator;

abstract class AbstractRequestValidator implements IValidator {

    protected array $query = [];
    protected $body;

    private function addParameter() {

    }

    protected function addQueryParameter(string $name, bool $mandatory = false, $defaultValue = null) {
        if (!isset($this->query[$name])) {
            $this->query[$name] = [
                'mandatory' => $mandatory,
                'default' => $defaultValue
            ];
        }
    }

    public function validateQuery(array $data) {

    }

    public function validateBody() {
        
    }
}
