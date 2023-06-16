<?php

namespace App\Trait;

trait JsonUserTrait
{

    private string $filePath = '';

    /**
     * @param string|null $sort
     * @return array|null
     */
    protected function loadFileContent(string $sort = null): array
    {
        $content = [];

        try {
            $content = file_get_contents($this->getFilePath());
            $content = json_decode($content);

            if ($sort !== null) {
                usort(
                    $content,
                    function ($a, $b) use ($sort) {
                        if ($a->$sort > $b->$sort) {
                            return 1;
                        } elseif ($a->$sort < $b->$sort) {
                            return -1;
                        }

                        return 0;
                    }
                );
            }
        } catch (\Exception $e) {
            // Log error
        }

        return $content;
    }

    /**
     * @param $content
     * @return bool
     */
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

    /**
     * @param string $filePath
     * @return void
     */
    public function setFilePath(string $filePath): void
    {
        $this->filePath = $filePath;
    }

    /**
     * @return string
     */
    private function getFilePath(): string
    {
        return $this->filePath;
    }
}
