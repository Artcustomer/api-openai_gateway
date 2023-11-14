<?php

namespace App\Service;

/**
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
     * @param string $currentRoute
     * @return array
     */
    public function getUserMenu(string $currentRoute): array
    {
        $this->currentRoute = $currentRoute;

        return $this->userMenuItems();
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
            $this->itemFactory('home', 'main_menu.home', 'bi-grid', 'application_home_index', []),
            $this->itemFactory('chat-nav', 'main_menu.chat', 'bi-journal-text', null, array_merge(
                $this->itemFactory('create_completion', 'main_menu.chat.create_completion', null, 'application_chat_create_completion'),
                $this->itemFactory('converse', 'main_menu.chat.converse', null, 'application_chat_converse', [], true)
            )),
            $this->itemFactory('text-nav', 'main_menu.text', 'bi-journal-text', null, array_merge(
                $this->itemFactory('prompt', 'main_menu.text.prompt', null, 'application_text_prompt'),
                $this->itemFactory('translate', 'main_menu.text.translate', null, 'application_text_translate'),
            )),
            $this->itemFactory('images-nav', 'main_menu.images', 'bi-gem', null, array_merge(
                $this->itemFactory('create', 'main_menu.images.create', null, 'application_image_create')
            )),
            $this->itemFactory('audio-nav', 'main_menu.audio', 'bi-speaker', null, array_merge(
                $this->itemFactory('create_transcription', 'main_menu.audio.create_transcription', null, 'application_audio_create_transcription'),
                $this->itemFactory('create_translation', 'main_menu.audio.create_translation', null, 'application_audio_create_translation'),
                $this->itemFactory('speak_to_text', 'main_menu.audio.speak_to_text', null, 'application_audio_speak_to_text', [], true)
            )),
            $this->itemFactory('tools-nav', 'main_menu.tools', 'bi-tools', null, array_merge(
                $this->itemFactory('samples', 'main_menu.tools.prompt_samples', null, 'application_tools_promptssamples')
            )),
            $this->itemFactory('sandbox-nav', 'main_menu.sandbox', 'bi-tools', null, array_merge(
                $this->itemFactory('edenai_test', 'main_menu.sandbox.edenai_test', null, 'application_sandbox_edenai_test')
            ))
        );
    }

    /**
     * @return array
     */
    private function userMenuItems(): array
    {
        return array_merge(
            $this->itemFactory('profile', 'user_menu.profile', 'bi-person-fill', 'application_user_index', []),
            $this->itemFactory('api-settings', 'user_menu.api_settings', 'bi-toggles', 'application_user_apisettings', []),
            $this->itemFactory('usage', 'user_menu.usage', 'bi-graph-up', 'application_user_usage', []),
            $this->itemFactory('log-out', 'user_menu.sign_out', 'bi-box-arrow-right', 'app_logout', []),
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
     * @param bool $disabled
     * @return array[]
     */
    private function itemFactory(string $key, string $title, string $icon = null, string $path = null, array $childs = [], bool $disabled = false): array
    {
        $data = [
            'title' => $title,
            'disabled' => $disabled,
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

