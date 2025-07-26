<?php

namespace App\Service;

/**
 * @author David
 */
class NavigationService
{

    private const NAV_TYPE_ITEM = 'item';
    private const NAV_TYPE_HEADING = 'heading';

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
            if (!empty($value['children'])) {
                $result = $this->pathfind($value['children'], $path, $stack);

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
            if (!empty($value['children'])) {
                $result = $this->search($value['children'], $path);

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
            $this->itemFactory(self::NAV_TYPE_ITEM, 'home', 'main_menu.home', 'bi-grid', 'application_home_index'),
            $this->itemFactory(self::NAV_TYPE_HEADING, 'openai-heading', 'main_menu.openai'),
            $this->itemFactory(self::NAV_TYPE_ITEM, 'openai-audio-completion', 'main_menu.feature.audio_completion', 'bi-soundwave', 'application_openai_feature_audio_completion'),
            $this->itemFactory(self::NAV_TYPE_ITEM, 'openai-chat-nav', 'main_menu.chat', 'bi-journal-text', null, array_merge(
                $this->itemFactory(self::NAV_TYPE_ITEM, 'openai-create_completion', 'main_menu.chat.create_completion', null, 'application_openai_chat_create_completion'),
                $this->itemFactory(self::NAV_TYPE_ITEM, 'openai-converse', 'main_menu.chat.converse', null, 'application_openai_chat_converse', [], true)
            )),
            $this->itemFactory(self::NAV_TYPE_ITEM, 'openai-text-nav', 'main_menu.text', 'bi-journal-text', null, array_merge(
                $this->itemFactory(self::NAV_TYPE_ITEM, 'openai-translate', 'main_menu.text.translate', null, 'application_openai_text_translate'),
                $this->itemFactory(self::NAV_TYPE_ITEM, 'openai-correct', 'main_menu.text.correct', null, 'application_openai_text_correct'),
            )),
            $this->itemFactory(self::NAV_TYPE_ITEM, 'openai-images-nav', 'main_menu.images', 'bi-card-image', null, array_merge(
                $this->itemFactory(self::NAV_TYPE_ITEM, 'openai-create', 'main_menu.images.create', null, 'application_openai_image_create'),
                $this->itemFactory(self::NAV_TYPE_ITEM, 'openai-analyze_image', 'main_menu.images.analyze', null, 'application_openai_image_analyze')
            )),
            $this->itemFactory(self::NAV_TYPE_ITEM, 'openai-audio-nav', 'main_menu.audio', 'bi-speaker', null, array_merge(
                $this->itemFactory(self::NAV_TYPE_ITEM, 'openai-create_completion', 'main_menu.audio.create_completion', null, 'application_openai_audio_create_completion'),
                $this->itemFactory(self::NAV_TYPE_ITEM, 'openai-create_transcription', 'main_menu.audio.create_transcription', null, 'application_openai_audio_create_transcription'),
                $this->itemFactory(self::NAV_TYPE_ITEM, 'openai-create_translation', 'main_menu.audio.create_translation', null, 'application_openai_audio_create_translation'),
                $this->itemFactory(self::NAV_TYPE_ITEM, 'openai-generate_audio', 'main_menu.audio.generate_audio', null, 'application_openai_audio_generate_audio'),
                $this->itemFactory(self::NAV_TYPE_ITEM, 'openai-text_to_speech', 'main_menu.audio.text_to_speech', null, 'application_openai_audio_text_to_speech'),
                $this->itemFactory(self::NAV_TYPE_ITEM, 'openai-speak_to_text', 'main_menu.audio.speak_to_text', null, 'application_openai_audio_speak_to_text')
            )),
            $this->itemFactory(self::NAV_TYPE_ITEM, 'openai-tools-nav', 'main_menu.tools', 'bi-tools', null, array_merge(
                $this->itemFactory(self::NAV_TYPE_ITEM, 'openai-list_models', 'main_menu.list_models', null, 'application_openai_tools_models_list')
            )),
            $this->itemFactory(self::NAV_TYPE_HEADING, 'edenai-heading', 'main_menu.edenai'),
            $this->itemFactory(self::NAV_TYPE_ITEM, 'edenai-audio-nav', 'main_menu.audio', 'bi-speaker', null, array_merge(
                $this->itemFactory(self::NAV_TYPE_ITEM, 'edenai-text_to_speech', 'main_menu.edenai.text_to_speech', null, 'application_edenai_audio_textospeech')
            )),
            $this->itemFactory(self::NAV_TYPE_HEADING, 'elevenlabs-heading', 'main_menu.elevenlabs'),
            $this->itemFactory(self::NAV_TYPE_ITEM, 'elevenlabs-audio-nav', 'main_menu.audio', 'bi-speaker', null, array_merge(
                $this->itemFactory(self::NAV_TYPE_ITEM, 'elevenlabs-text_to_speech', 'main_menu.elevenlabs.text_to_speech', null, 'application_elevenlabs_audio_textospeech')
            )),
            $this->itemFactory(self::NAV_TYPE_ITEM, 'elevenlabs-tools-nav', 'main_menu.tools', 'bi-tools', null, array_merge(
                $this->itemFactory(self::NAV_TYPE_ITEM, 'elevenlabs-list_models', 'main_menu.list_models', null, 'application_elevenlabs_tools_models_list')
            )),
            $this->itemFactory(self::NAV_TYPE_HEADING, 'mistralai-heading', 'main_menu.mistralai'),
            $this->itemFactory(self::NAV_TYPE_ITEM, 'mistralai-chat-nav', 'main_menu.chat', 'bi-journal-text', null, array_merge(
                $this->itemFactory(self::NAV_TYPE_ITEM, 'mistralai-create_completion', 'main_menu.chat.create_completion', null, 'application_mistralai_chat_create_completion')
            )),
            $this->itemFactory(self::NAV_TYPE_ITEM, 'mistralai-images-nav', 'main_menu.images', 'bi-card-image', null, array_merge(
                $this->itemFactory(self::NAV_TYPE_ITEM, 'mistralai-analyze_image', 'main_menu.images.analyze', null, 'application_mistralai_image_analyze')
            )),
            $this->itemFactory(self::NAV_TYPE_ITEM, 'mistralai-tools-nav', 'main_menu.tools', 'bi-tools', null, array_merge(
                $this->itemFactory(self::NAV_TYPE_ITEM, 'mistralai-list_models', 'main_menu.list_models', null, 'application_mistralai_tools_models_list')
            )),
            $this->itemFactory(self::NAV_TYPE_HEADING, 'xai-heading', 'main_menu.xai'),
            $this->itemFactory(self::NAV_TYPE_ITEM, 'xai-chat-nav', 'main_menu.chat', 'bi-journal-text', null, array_merge(
                $this->itemFactory(self::NAV_TYPE_ITEM, 'xai-create_chat_completion', 'main_menu.chat.create_completion', null, 'application_xai_chat_completion_create')
            )),
            $this->itemFactory(self::NAV_TYPE_ITEM, 'xai-tools-nav', 'main_menu.tools', 'bi-tools', null, array_merge(
                $this->itemFactory(self::NAV_TYPE_ITEM, 'xai-list_models', 'main_menu.list_models', null, 'application_xai_tools_models_list')
            )),
            $this->itemFactory(self::NAV_TYPE_HEADING, 'deepseek-heading', 'main_menu.deepseek'),
            $this->itemFactory(self::NAV_TYPE_ITEM, 'deepseek-chat-nav', 'main_menu.chat', 'bi-journal-text', null, array_merge(
                $this->itemFactory(self::NAV_TYPE_ITEM, 'deepseek-create_chat_completion', 'main_menu.chat.create_completion', null, 'application_deepseek_chat_completion_create')
            )),
            $this->itemFactory(self::NAV_TYPE_ITEM, 'deepseek-tools-nav', 'main_menu.tools', 'bi-tools', null, array_merge(
                $this->itemFactory(self::NAV_TYPE_ITEM, 'deepseek-list_models', 'main_menu.list_models', null, 'application_deepseek_tools_models_list')
            )),
            $this->itemFactory(self::NAV_TYPE_HEADING, 'gemini-heading', 'main_menu.gemini'),
            $this->itemFactory(self::NAV_TYPE_ITEM, 'gemini-text-nav', 'main_menu.text', 'bi-journal-text', null, array_merge(
                $this->itemFactory(self::NAV_TYPE_ITEM, 'gemini-text_generate', 'main_menu.text.generate', null, 'application_gemini_text_generate')
            )),
            $this->itemFactory(self::NAV_TYPE_ITEM, 'gemini-image-nav', 'main_menu.images', 'bi-card-image', null, array_merge(
                $this->itemFactory(self::NAV_TYPE_ITEM, 'gemini-image_generate', 'main_menu.images.generate', null, 'application_gemini_image_generate')
            )),
            $this->itemFactory(self::NAV_TYPE_ITEM, 'gemini-video-nav', 'main_menu.video', 'bi-camera-video', null, array_merge(
                $this->itemFactory(self::NAV_TYPE_ITEM, 'gemini-video_generate', 'main_menu.video.generate', null, 'application_gemini_video_generate'),
                $this->itemFactory(self::NAV_TYPE_ITEM, 'gemini-video_retrieve', 'main_menu.video.retrieve', null, 'application_gemini_video_retrieve')
            )),
            $this->itemFactory(self::NAV_TYPE_ITEM, 'gemini-audio-nav', 'main_menu.audio', 'bi-speaker', null, array_merge(
                $this->itemFactory(self::NAV_TYPE_ITEM, 'gemini-audio_generate_speech', 'main_menu.audio.generate_speech', null, 'application_gemini_audio_generate_speech', [], true),
                $this->itemFactory(self::NAV_TYPE_ITEM, 'gemini-audio_generate_music', 'main_menu.audio.generate_music', null, 'application_gemini_audio_generate_music', [], true)
            )),
            $this->itemFactory(self::NAV_TYPE_ITEM, 'gemini-tools-nav', 'main_menu.tools', 'bi-tools', null, array_merge(
                $this->itemFactory(self::NAV_TYPE_ITEM, 'gemini-list_models', 'main_menu.list_models', null, 'application_gemini_tools_models_list')
            )),
            $this->itemFactory(self::NAV_TYPE_HEADING, 'toolbox-heading', 'main_menu.toolbox'),
            $this->itemFactory(self::NAV_TYPE_ITEM, 'toolbox-tools-nav', 'main_menu.tools', 'bi-tools', null, array_merge(
                $this->itemFactory(self::NAV_TYPE_ITEM, 'toolbox-samples', 'main_menu.tools.prompt_samples', null, 'application_toolbox_tools_promptssamples')
            )),
            $this->itemFactory(self::NAV_TYPE_ITEM, 'toolbox-sandbox-nav', 'main_menu.sandbox', 'bi-tools', null, array_merge(
                $this->itemFactory(self::NAV_TYPE_ITEM, 'toolbox-testing', 'main_menu.sandbox.testing', null, 'application_toolbox_sandbox_testing')
            ))
        );
    }

    /**
     * @return array
     */
    private function userMenuItems(): array
    {
        return array_merge(
            $this->itemFactory(self::NAV_TYPE_ITEM, 'profile', 'user_menu.profile', 'bi-person-fill', 'application_user_index', []),
            $this->itemFactory(self::NAV_TYPE_ITEM, 'api-settings', 'user_menu.api_settings', 'bi-toggles', 'application_user_apisettings', []),
            $this->itemFactory(self::NAV_TYPE_ITEM, 'usage', 'user_menu.usage', 'bi-graph-up', 'application_user_usage', []),
            $this->itemFactory(self::NAV_TYPE_ITEM, 'status', 'user_menu.status', 'bi-shield-check', 'application_user_status', []),
            $this->itemFactory(self::NAV_TYPE_ITEM, 'log-out', 'user_menu.sign_out', 'bi-box-arrow-right', 'app_logout', []),
        );
    }

    /**
     * Navigation item factory
     *
     * @param string $type
     * @param string $key
     * @param string $title
     * @param string|null $icon
     * @param string|null $path
     * @param array $children
     * @param bool $disabled
     * @return array[]
     */
    private function itemFactory(string $type, string $key, string $title, string $icon = null, string $path = null, array $children = [], bool $disabled = false): array
    {
        $data = [
            'type' => $type,
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

        if (!empty($children)) {
            $data['children'] = $children;

            foreach ($children as $cKey => $cValue) {
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

