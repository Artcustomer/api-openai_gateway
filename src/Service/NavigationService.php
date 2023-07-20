<?php

namespace App\Service;

/**
 *
 * @author David
 */
class NavigationService
{

    private string $currentRoute;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->currentRoute = '';
    }

    /**
     * @param string $currentRoute
     * @return array
     */
    public function getNavigation(string $currentRoute): array
    {
        $this->currentRoute = $currentRoute;

        return $this->navigationItems();
    }

    /**
     * @param string $route
     * @return string
     */
    public function getTitle(string $route): string
    {
        $data = $this->navigationItems();

        return $this->search($data, $route);
    }

    /**
     * @param string $route
     * @return array
     */
    public function getPath(string $route): array
    {
        $data = $this->navigationItems();
        $path = [];
        $stack = [];
        $this->pathfind($data, $route, $stack);
        $count = count($stack);

        for ($i = 0; $i < $count; ++$i) {
            $path[] = array_pop($stack);
        }

        return $path;
    }

    /**
     * @param array $data
     * @param string $path
     * @param $stack
     * @return mixed|null
     */
    private function pathfind(array $data, string $path, &$stack = [])
    {
        foreach ($data as $key => $value) {
            if (!empty($value['childs'])) {
                $result = $this->pathfind($value['childs'], $path, $stack);

                if ($result !== null) {
                    $stack[] = [
                        'title' => $value['title'],
                        'path' => $value['path'] ?? null,
                    ];

                    return $result;
                }
            } else {
                if (isset($value['path']) && $value['path'] === $path) {
                    $stack[] = [
                        'title' => $value['title'],
                        'path' => $value['path'] ?? null,
                    ];

                    return $value['title'];
                }
            }
        }

        return null;
    }

    /**
     * @param array $data
     * @param string $path
     * @return mixed|null
     */
    private function search(array $data, string $path)
    {
        foreach ($data as $key => $value) {
            if (!empty($value['childs'])) {
                $result = $this->search($value['childs'], $path);

                if ($result !== null) {
                    return $result;
                }
            } else {
                if (isset($value['path']) && $value['path'] === $path) {
                    return $value['title'];
                }
            }
        }

        return null;
    }

    /**
     * @return array
     */
    private function navigationItems(): array
    {
        return array_merge(
            $this->navigationItemFactory('home', 'Home', 'bi-grid', 'application_home_index', []),
            $this->navigationItemFactory('chat-nav', 'Chat', 'bi-journal-text', null, array_merge(
                $this->navigationItemFactory('create_completion', 'Create completion', null, 'application_chat_create_completion'),
                $this->navigationItemFactory('converse', 'Converse', null, 'application_chat_converse')
            )),
            $this->navigationItemFactory('text-nav', 'Text', 'bi-journal-text', null, array_merge(
                $this->navigationItemFactory('prompt', 'Prompt', null, 'application_text_prompt'),
                $this->navigationItemFactory('translate', 'Translate', null, 'application_text_translate'),
            )),
            $this->navigationItemFactory('images-nav', 'Images', 'bi-gem', null, array_merge(
                $this->navigationItemFactory('create', 'Create', null, 'application_image_create')
            )),
            $this->navigationItemFactory('audio-nav', 'Audio', 'bi-speaker', null, array_merge(
                $this->navigationItemFactory('create_transcription', 'Create transcription', null, 'application_audio_create_transcription'),
                $this->navigationItemFactory('create_translation', 'Create translation', null, 'application_audio_create_translation'),
                //$this->navigationItemFactory('speak_to_text', 'Speak to text', null, 'application_audio_speak_to_text')
            )),
            $this->navigationItemFactory('tools-nav', 'Tools', 'bi-tools', null, array_merge(
                $this->navigationItemFactory('samples', 'Prompts samples', null, 'application_tools_promptssamples')
            ))
        );
    }

    /**
     * Navigation item factory
     *
     * @param string $key
     * @param string $title
     * @param string|null $icon
     * @param string|null $path
     * @param array $childs
     * @return array[]
     */
    private function navigationItemFactory(string $key, string $title, string $icon = null, string $path = null, array $childs = []): array
    {
        $data = [
            'title' => $title,
            'active' => false
        ];

        if ($icon !== null) {
            $data['icon'] = $icon;
        }

        if ($path !== null) {
            $data['path'] = $path;

            if ($this->currentRoute === $path) {
                $data['active'] = true;
            }
        }

        if (!empty($childs)) {
            $data['childs'] = $childs;

            foreach ($childs as $cKey => $cValue) {
                if ($cValue['active'] === true) {
                    $data['active'] = true;
                }
            }
        }

        return [
            $key => $data
        ];
    }
}

