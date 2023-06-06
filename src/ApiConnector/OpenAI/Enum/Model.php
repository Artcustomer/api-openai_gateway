<?php

namespace App\ApiConnector\OpenAI\Enum;

/**
 * @author David
 */
class Model {

    public const ADA = 'ada';
    public const BABBAGE = 'babbage';
    public const CODE_DAVINCI_EDIT_001 = 'code-davinci-edit-001';
    public const CURIE = 'curie';
    public const DAVINCI = 'davinci';
    public const GPT_3_5_TURBO = 'gpt-3.5-turbo';
    public const GPT_3_5_TURBO_0301 = 'gpt-3.5-turbo-0301';
    public const GPT_4 = 'gpt-4';
    public const GPT_4_0314 = 'gpt-4-0314';
    public const GPT_4_32K = 'gpt-4-32k';
    public const GPT_4_32K_0314 = 'gpt-4-32k-0314';
    public const WHISPER_1 = 'whisper-1';
    public const TEXT_ADA_001 = 'text-ada-001';
    public const TEXT_BABBAGE_001 = 'text-babbage-001';
    public const TEXT_CURIE_001 = 'text-curie-001';
    public const TEXT_DAVINCI_002 = 'text-davinci-002';
    public const TEXT_DAVINCI_003 = 'text-davinci-003';
    public const TEXT_DAVINCI_EDIT_001 = 'text-davinci-edit-001';
    public const TEXT_EMBEDDING_ADA_002 = 'text-embedding-ada-002';
    public const TEXT_MODERATION_LATEST = 'text-moderation-latest';
    public const TEXT_MODERATION_STABLE = 'text-moderation-stable';
    public const TEXT_SEARCH_ADA_DOC_001 = 'text-search-ada-doc-001';

    /**
     * Get models for chat completions
     */
    public static function chatCompletions(): array
    {
        return [
            self::GPT_4,
            self::GPT_4_0314,
            self::GPT_4_32K,
            self::GPT_4_32K_0314,
            self::GPT_3_5_TURBO,
            self::GPT_3_5_TURBO_0301
        ];
    }

    /**
     * Get models for completions
     */
    public static function completions(): array
    {
        return [
            self::TEXT_DAVINCI_003,
            self::TEXT_DAVINCI_002,
            self::TEXT_CURIE_001,
            self::TEXT_BABBAGE_001,
            self::TEXT_ADA_001
        ];
    }

    /**
     * Get models for edits
     */
    public static function edits(): array
    {
        return [
            self::TEXT_DAVINCI_EDIT_001,
            self::CODE_DAVINCI_EDIT_001
        ];
    }

    /**
     * Get models for audio transcriptions
     */
    public static function audioTranscriptions(): array
    {
        return [
            self::WHISPER_1
        ];
    }

    /**
     * Get models for audio translations
     */
    public static function audioTranslations(): array
    {
        return [
            self::WHISPER_1
        ];
    }

    /**
     * Get models for fine-tunes
     */
    public static function fineTunes(): array
    {
        return [
            self::DAVINCI,
            self::CURIE,
            self::BABBAGE,
            self::ADA
        ];
    }

    /**
     * Get models for embeddings
     */
    public static function embeddings(): array
    {
        return [
            self::TEXT_EMBEDDING_ADA_002,
            self::TEXT_SEARCH_ADA_DOC_001
        ];
    }

    /**
     * Get models for moderations
     */
    public static function moderations(): array
    {
        return [
            self::TEXT_MODERATION_STABLE,
            self::TEXT_MODERATION_LATEST
        ];
    }

}
