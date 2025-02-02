<?php

namespace App\Service;

use Symfony\Component\Routing\RouterInterface;

/**
 * @author David
 */
class PromptService
{

    private RouterInterface $router;

    /**
     * Constructor
     *
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Get samples
     *
     * @return array
     */
    public function getSamples(): array
    {
        $data = [];
        $data['learning'] = [
            'title' => 'Learning',
            'prompts' => [
                $this->sampleFactory('Focus on the most important', 'Act as an expert in [TOPIC]. MY goal is to learn [SKILL] as quickly as possible. Make a list of the most important things I need to know that will allow me to master this topic.', 'application_openai_chat_create_completion', ['max_tokens' => 1024]),
                $this->sampleFactory('Define and understand a concept', 'Define [TERM] and provide an example that can be used in everyday life. The definition should be complete but easy to understand, explaining any complicated words if necessary.', 'application_openai_chat_create_completion', ['max_tokens' => 1024]),
                $this->sampleFactory('Relate multiple topics', 'Describe and explain in simple words the relationship between [CONCEPT 1] and [CONCEPT 2].', 'application_openai_chat_create_completion', ['max_tokens' => 1024]),
                $this->sampleFactory('Consult an expert and ask questions', 'I want you to act as an expert in [TOPIC] and give me recommendations for [SPECIFIC QUESTION].', 'application_openai_chat_create_completion', ['max_tokens' => 1024]),
                $this->sampleFactory('Learn the most important terms', 'What are some key terms I should know about [TOPIC]? Make a list what a short, simple definition of each term.', 'application_openai_chat_create_completion', ['max_tokens' => 1024]),
                $this->sampleFactory('Create a learning plan', 'I want to learn [TOPIC]. Give me step-by-step instructions on how to learn [SKILL]. Start with the basics and move on to the more difficult ones. Keep in mind that i am a beginner', 'application_openai_chat_create_completion', ['max_tokens' => 1024]),
                $this->sampleFactory('Easily understand anything', 'Can you explain the concept of [TOPIC] in simple terms? Summarize the main principles and illustrate with examples to make it easier to understand.', 'application_openai_chat_create_completion', ['max_tokens' => 1024]),
                $this->sampleFactory('Increase your productivity', 'Enhance team productivity in [BUSINESS] by implementing the [SCRUM] methodology.', 'application_openai_chat_create_completion', ['max_tokens' => 1024]),
            ]
        ];
        $data['teaching'] = [
            'title' => 'Teaching',
            'prompts' => [
                $this->sampleFactory('Understand a concept at a basic level or when explaining it to someone with limited knowledge of the subject', 'Explain the concept of [SUBJECT CONCEPT] in simple terms suitable for a [GRADE LEVEL] student', 'application_openai_chat_create_completion', ['max_tokens' => 1024]),
                $this->sampleFactory('Get a detailed chronological account of a significant historical event', 'As a historian, describe the key events of the [HISTORICAL EVENT]', 'application_openai_chat_create_completion', ['max_tokens' => 1024]),
                $this->sampleFactory('Simplify complex scientific theories and make them more engaging', 'Summarize the theory of [SCIENTIFIC THEORY] in a fun and engaging way for [TARGET AUDIENCE]', 'application_openai_chat_create_completion', ['max_tokens' => 1024]),
                $this->sampleFactory('Combine science with creativity. It could be useful for making learning more fun or for a unique school project', 'Compose a sonnet about the [SCIENTIFIC PROCESS OR NATURAL PHENOMENON]', 'application_openai_chat_create_completion', ['max_tokens' => 1024]),
                $this->sampleFactory('Understanding financial concepts and how they apply to real-life situations. It\'s especially useful for financial education', 'In a friendly and approachable tone, explain the basics of [FINANCIAL CONCEPT] and how it applies to [REAL-LIFE SITUATION]', 'application_openai_chat_create_completion', ['max_tokens' => 1024]),
                $this->sampleFactory('Analyze and compare two different artworks or artists, which can be helpful in understanding art history or criticism', 'Draw a comparison between [ARTWORK OR ARTIST 1] and [ARTWORK OR ARTIST 2] in terms of style, technique, and cultural context', 'application_openai_chat_create_completion', ['max_tokens' => 1024]),
                $this->sampleFactory('Gain a deeper understanding of a literary work by focusing on its themes', 'Summarize the plot of [LITERARY WORK] in a way that highlights its main thematic elements', 'application_openai_chat_create_completion', ['max_tokens' => 1024]),
                $this->sampleFactory('Developing debate skills, as it encourages understanding and articulating different viewpoints on a controversial topic', 'Write a debate argument supporting the idea that [CONTROVERSIAL TOPIC] from the perspective of [SPECIFIC VIEWPOINT]', 'application_openai_chat_create_completion', ['max_tokens' => 1024]),
                $this->sampleFactory('Understand how to solve specific types of math problems', 'Provide a step-by-step solution to this [TYPE OF MATH PROBLEM]: [MATH PROBLEM]', 'application_openai_chat_create_completion', ['max_tokens' => 1024]),
                $this->sampleFactory('Gather unique perspectives', '[TOPIC] write multiple perspectives from a group with different viewpoints. For each perspective, write in their own voice using phrases that person would use', 'application_openai_chat_create_completion', ['max_tokens' => 1024])
            ]
        ];
        $data['images'] = [
            'title' => 'Images',
            'prompts' => [
                $this->sampleFactory('Close-up black & white', 'A close-up, black and white studio photographic portrait of SUBJECT, dramatic backlighting, 1973 photo from Life Magazine', 'application_openai_image_create'),
                $this->sampleFactory('Vibrant photograph', 'A vibrant photograph of SUBJECT, wide shot, outdoors, sunset photo at golden hour, wide-angle lens, soft focus, shot on iPhone6, on Flickr in 2007', 'application_openai_image_create')
            ]
        ];
        $data['marketing'] = [
            'title' => 'Marketing',
            'prompts' => [
                $this->sampleFactory('Simulating valuable conversations between experts', 'Simulate a conversation between Steve Jobs and Seth Godin on marketing', 'application_openai_chat_create_completion', ['max_tokens' => 1024]),
                $this->sampleFactory('Generate the perfect marketing plan', 'I want you to act as an advertiser. You will create a campaign to promote a product or service of your choice. You will choose a target audience, develop key messages and slogans, select the media channels for promotion, and decide on any additional activities needed to reach your goals. My first suggestion request is, "I need help creating an advertising campaign for [PRODUCT DESCRIPTION]', 'application_openai_chat_create_completion', ['max_tokens' => 1024])
            ]
        ];
        $data['social_media'] = [
            'title' => 'Social media',
            'prompts' => [
                $this->sampleFactory('Make viral carousel', 'Write me an Instagram carousel about [TOPIC]. Also, write slide by slide with titles. Explain each slide with the exact content example I should use, not instructions. Do not include content instructions, instead, only write me an actionable text that I can just copy and paste', 'application_openai_chat_create_completion', ['max_tokens' => 1024]),
                $this->sampleFactory('Viral reels script', 'Write me an Instagram Reels script about [TOPIC]. Write it using an AIDA method and make it sound natural and spontaneous', 'application_openai_chat_create_completion', ['max_tokens' => 1024]),
                $this->sampleFactory('Write viral captions', 'Write me the Instagram post description/caption about [TOPIC] in just a few sentences. Format every new sentence with new lines so the text is more readable. Include emojis and the best hashtags for that post. The first caption sentence should be intriguing and captivating the readers', 'application_openai_chat_create_completion', ['max_tokens' => 1024]),
                $this->sampleFactory('Unlimited viral ideas', 'Give me [NUMBER] unique content ideas for [TOPIC]. The length of each should be between 4 and 7 words.', 'application_openai_chat_create_completion', ['max_tokens' => 1024])
            ]
        ];
        $data['economy'] = [
            'title' => 'Economy',
            'prompts' => [
                $this->sampleFactory('Budgeting and saving', 'How can I identify and reduce unnecessary expenses to increase my savings and improve my overall financial health?', 'application_openai_chat_create_completion', ['max_tokens' => 1024]),
                $this->sampleFactory('Investing and wealth building', 'What are the basic principles of investing and how can I develop an investment strategy that aligns with my risk tolerance and financial objectives?', 'application_openai_chat_create_completion', ['max_tokens' => 1024]),
                $this->sampleFactory('Retirement planning', 'This is a suggestion request. How can I estimate my financial needs in retirement and develop a savings plan to achieve those goals?', 'application_openai_chat_create_completion', ['max_tokens' => 1024])
            ]
        ];

        return $data;
    }

    /**
     * Sample factory
     *
     * @param string $title
     * @param string $prompt
     * @param string|null $path
     * @param array $parameters
     * @return string[]
     */
    private function sampleFactory(string $title, string $prompt, string $path = null, array $parameters = []): array
    {
        $data = [
            'title' => $title,
            'prompt' => $prompt
        ];

        if ($path !== null) {
            $parameters = array_merge($parameters, ['prompt' => $prompt]);

            array_walk($parameters,
                function ($value, $key) use (&$parameters) {
                    $parameters[$key] = urlencode($value);
                }
            );

            $data['link'] = $this->router->generate($path, $parameters);
        }

        return $data;
    }
}

