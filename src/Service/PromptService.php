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
                $this->sampleFactory("Plan de Cours sur [Sujet] pour [Classe scolaire d'un niveau]", "Rôle : Tu es [un enseignant] chargé de créer un plan de cours. Environnement : Je travaille [dans une école, par exemple : un lycée] et je dois [élaborer un plan de cours] sur le sujet de [le sujet d'étude, par exemple : les mathématiques appliquées] pour [la classe scolaire d'un niveau, par exemple : la classe de première]. Actions : Tu dois [décomposer ton travail en trois étapes : définir les objectifs pédagogiques, établir les modalités d'évaluation et organiser les séances]. Contraintes : Exemple : le plan doit couvrir un trimestre et ne pas dépasser [nombre de mots ou de pages, par exemple : 5 pages]. Ton : Utilise un ton [professionnel]. Objectif : L'objectif du plan de cours est de [décrire ce que tu espères atteindre, par exemple : structurer l'apprentissage des élèves de manière efficace et engageante]. Résultat : Tu fourniras le résultat au format [texte ou tout autre format souhaité, par exemple : PDF].", 'application_openai_chat_create_completion', ['max_tokens' => 1024]),
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
                $this->sampleFactory('Idées de Sujets de Blog pour [Thème de votre Choix]', "Rôle : Tu es [un expert en création de contenu et blogging]. Environnement : Je suis [dans un contexte de marketing digital ou de blogging] et je souhaite générer des idées de sujets de blog pour [thème de votre choix, par exemple : la santé, le développement personnel, la technologie, etc.]. Actions : Tu dois [proposer cinq idées de sujets de blog, les décrire brièvement et expliquer pourquoi chacune d'elles pourrait intéresser les lecteurs]. Contraintes : [les sujets doivent être originaux et pertinents, et il est important de respecter une longueur de description de 50 mots maximum par sujet]. Ton : Utilise un ton [inspirant et engageant]. Objectif : Je cherche à [attirer l'attention de mes lecteurs et les inciter à lire mon blog]. Résultat : Tu fourniras le résultat au format [format texte].", 'application_openai_chat_create_completion', ['max_tokens' => 1024]),
                $this->sampleFactory("Script d'une Minute pour Publicité sur [Produit, Service ou Entreprise]", " Rôle : Tu es un [rédacteur publicitaire expert]. Environnement : Je travaille [dans une agence de publicité] et je dois créer un script d'une minute pour une publicité sur [produit, service ou entreprise]. Actions : Tu dois [rédiger une introduction accrocheuse, développer le corps du script avec des messages clés, et conclure avec un appel à l'action]. Contraintes : La durée doit être d'une minute maximum et le contenu doit être captivant et adapté à [cible précise, par exemple : un public jeune, des professionnels, etc.]. Ton : Utilise un ton [persuasif et engageant]. Objectif : L'objectif est de [promouvoir efficacement le produit/service et inciter à l'action]. Résultat : Fournis le résultat au format texte.", 'application_openai_chat_create_completion', ['max_tokens' => 1024]),
                $this->sampleFactory('Description de [Produit ou Service ou Entreprise]', "Rôle : Tu es [rôle de l'IA, par exemple : rédacteur marketing]. Environnement : Je travaille [dans un contexte, par exemple : au sein d'une startup innovante] et je dois [développer une description] pour [un produit/service/entreprise spécifique, par exemple : un nouveau smartphone éco-responsable]. Actions : Tu dois [décomposer ton travail en plusieurs étapes, par exemple : 1. Faire une recherche sur le produit/service/entreprise, 2. Rédiger une description engageante, 3. Mettre en avant les caractéristiques et avantages clés]. Contraintes : La description doit être [d'une certaine longueur, par exemple : entre 200 et 300 mots] et inclure [des éléments spécifiques, par exemple : des témoignages de clients ou des données chiffrées]. Ton : Utilise un ton [par exemple : professionnel et engageant]. Objectif : L'objectif est de [donner envie aux clients potentiels d'en savoir plus ou d'acheter le produit, par exemple : mettre en avant les atouts uniques du produit]. Résultat : Tu fourniras le résultat au format [exemple : texte, Markdown, ou tout autre format pertinent].", 'application_openai_chat_create_completion', ['max_tokens' => 1024]),
                $this->sampleFactory('Méta Description pour Article de Blog sur [Sujet]', "Rôle : Tu es [rôle de l'IA, par exemple : rédacteur SEO].Environnement : Je travaille [contexte, par exemple : dans une agence de marketing digital] et je dois [tâche, par exemple : rédiger une méta description] pour un article de blog qui porte sur [sujet spécifique, exemple : l'optimisation SEO]. Actions : tu dois [décomposer le travail en 3 étapes, par exemple : 1. Identifier les mots-clés pertinents, 2. Rédiger une description accrocheuse de 155 caractères max, 3. S'assurer que la description reflète fidèlement le contenu de l'article]. Contraintes : Exemple : la méta description ne doit pas dépasser 155 caractères, inclure des mots-clés ciblés, et être orientée vers le référencement. Ton : Utilise un ton [par exemple : professionnel et engageant]. Objectif : La méta description doit [expliquer ce que l’on cherche à atteindre, par exemple, inciter les utilisateurs à cliquer sur le lien dans les résultats de recherche]. Résultat : Tu fourniras le résultat au format [exemple : texte formaté]. N'hésite pas à personnaliser les éléments entre crochets pour répondre à tes besoins spécifiques.", 'application_openai_chat_create_completion', ['max_tokens' => 1024]),
                $this->sampleFactory('Slogan Accrocheur pour [Produit, Service ou Entreprise]', "Rôle : Tu es [expert en marketing et communication]. Environnement : Je travaille [dans une agence de publicité] et je dois [créer un slogan accrocheur] pour [un produit, un service ou une entreprise spécifique]. Actions : Tu dois [1. analyser les valeurs clés et les caractéristiques du produit/service/entreprise, 2. brainstormer des phrases courtes et impactantes, 3. sélectionner et affiner les meilleures propositions]. Contraintes : [Le slogan doit être court (maximum 10 mots), mémorable et refléter l'identité de la marque]. Ton : Utilise un ton [créatif et engageant]. Objectif : [Le slogan doit captiver l’attention du public cible et se distinguer de la concurrence]. Résultat : [Tu fourniras le résultat sous forme d'une liste de 5 slogans proposés].", 'application_openai_chat_create_completion', ['max_tokens' => 1024])
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
     * @return array
     */
    public function getLinks(): array
    {
        return [
            $this->linkFactory('OpenAI Cookbook', 'Explore practical guides and recipes for using OpenAI models effectively.', 'https://cookbook.openai.com/'),
            $this->linkFactory('prompts.chat', 'World\'s First & Most Famous Prompts Directory.', 'https://prompts.chat/'),
            $this->linkFactory('200 advanced prompts', 'Free prompts provided by Jerome Dron.', 'https://docs.google.com/spreadsheets/d/1PEO7t06UzSjtG_QgbqYp22YpVnyseYM0Fxv2sVb0Mj8/edit?pli=1&gid=1135004812#gid=1135004812')
        ];
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

    /**
     * @param string $title
     * @param string $description
     * @param string $link
     * @return string[]
     */
    private function linkFactory(string $title, string $description, string $link): array
    {
        return [
            'title' => $title,
            'description' => $description,
            'link' => $link,
        ];
    }
}

