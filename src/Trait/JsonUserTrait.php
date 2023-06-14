<?php

namespace App\Trait;

trait JsonUserTrait
{

    protected function loadFileContent()
    {
        $content = null;

        try {
            $content = file_get_contents($this->getFilePath());
            $content = json_decode($content);
        } catch (\Exception $e) {

        }

        return $content;
    }

    protected function writeFileContent($content): bool
    {
        $status = false;

        try {
            file_put_contents($this->getFilePath(), json_encode($content));

            $status = true;
        } catch (\Exception $e) {

        }

        return $status;
    }

    private function getFilePath(): string
    {
        return '../data/users.json';
    }
}