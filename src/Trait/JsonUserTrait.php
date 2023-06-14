<?php

namespace App\Trait;

trait JsonUserTrait
{

    protected function loadFileContent(string $sort = null): array
    {
        $content = null;

        try {
            $content = file_get_contents($this->getFilePath());
            $content = json_decode($content);

            if ($sort !== null) {
                usort(
                    $content,
                    function($a, $b) use ($sort) {
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