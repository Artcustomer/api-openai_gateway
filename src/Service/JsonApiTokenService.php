<?php

namespace App\Service;

use App\Factory\ApiTokenFactory;
use App\Security\ApiToken;
use App\Trait\JsonStorageTrait;

/**
 * @author David
 */
class JsonApiTokenService
{
    use JsonStorageTrait;

    public const FIELD_USERNAME = 'username';
    public const FIELD_TOKEN = 'token';

    protected ApiTokenFactory $apiTokenFactory;

    /**
     * Constructor
     *
     * @param ApiTokenFactory $apiTokenFactory
     * @param string $filePath
     */
    public function __construct(ApiTokenFactory $apiTokenFactory, string $filePath)
    {
        $this->apiTokenFactory = $apiTokenFactory;

        $this->setFilePath($filePath);
    }

    /**
     * @param string $username
     * @return ApiToken|null
     * @throws \Exception
     */
    public function createToken(string $username): ?ApiToken
    {
        $fileContent = $this->loadFileContent('username');
        $apiToken = null;

        if ($fileContent !== null) {
            // TODO : validate data
            $index = $this->searchByKey($fileContent, self::FIELD_USERNAME, $username);

            if ($index === null) {
                $data = new \stdClass();
                $data->username = $username;
                $data->enabled = true;
                $apiToken = $this->apiTokenFactory->create($data);

                $fileContent[] = $apiToken->toObject();

                $this->writeFileContent($fileContent);
            }
        }

        return $apiToken;
    }

    /**
     * @param string $username
     * @return mixed|null
     */
    public function getToken(string $username): mixed
    {
        $fileContent = $this->loadFileContent(self::FIELD_USERNAME);
        $result = null;

        if (!empty($fileContent)) {
            $index = $this->searchByKey($fileContent, self::FIELD_USERNAME, $username);

            if ($index !== null) {
                $result = $fileContent[$index];
            }
        }

        return $result;
    }

    /**
     * @param string $value
     * @param string $field
     * @return mixed
     */
    public function getApiToken(string $value, string $field = self::FIELD_TOKEN): mixed
    {
        if (!in_array($field, [self::FIELD_USERNAME, self::FIELD_TOKEN])) {
            return null;
        }

        $fileContent = $this->loadFileContent(self::FIELD_USERNAME);
        $result = null;

        if (!empty($fileContent)) {
            $index = $this->searchByKey($fileContent, $field, $value);

            if ($index !== null) {
                $result = $fileContent[$index];
            }
        }

        return $result;
    }

    /**
     * @param string $username
     * @return bool
     */
    public function revokeToken(string $username): bool
    {
        $fileContent = $this->loadFileContent('username');
        $status = false;

        if (!empty($fileContent)) {
            $index = $this->searchByKey($fileContent, self::FIELD_USERNAME, $username);

            if ($index !== null) {
                unset($fileContent[$index]);

                $this->writeFileContent($fileContent);

                $status = true;
            }
        }

        return $status;
    }

    /**
     * @return ApiTokenFactory
     */
    public function getFactory(): ApiTokenFactory
    {
        return $this->apiTokenFactory;
    }
}
