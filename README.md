# Setup local development environment
This document describes how to setup the project on your local environment

## Requirements
### Composer 2
https://getcomposer.org/download/

### Symfony server
https://symfony.com/doc/current/setup/symfony_server.html

### OpenAI account
https://platform.openai.com/docs/overview

### EdenAI account
https://docs.edenai.co/docs/quickstart-ai-apis

### ElevenLabs account
https://elevenlabs.io/docs/overview

### MistralAI account
https://docs.mistral.ai/

### XAI account
https://docs.x.ai/docs/overview

### DeepSeek account
https://platform.deepseek.com/docs

### Gemini account
https://ai.google.dev/gemini-api/docs


## Components versions
### PHP 8.1
https://www.php.net/releases/8.1/en.php

### Symfony 6.4
https://symfony.com/doc/6.4/index.html

### AssetMapper 6.4
https://symfony.com/doc/6.4/frontend/asset_mapper.html

## Installation
### Environment variables
At the root of the project, create a ".env.local" file from the ".env" file and fill in the variables:
- OPENAI_API_KEY (from your OpenAI account)
- OPENAI_ORGANISATION (from your OpenAI account)
- EDENAI_API_KEY (from your EdenAI account)
- ELEVENLABS_API_KEY (from your ElevenLabs account)
- MISTRALAI_API_KEY (from your MistralAI account)
- XAI_API_KEY (from your XAI account)
- DEEPSEEK_API_KEY (from your DeepSeek account)
- GEMINI_API_KEY (from your Gemini account)
- DATA_USER_JSON_FILE (ex: data/users_local.json)
- DATA_API_TOKEN_JSON_FILE (ex: data/api_tokens_local.json)

### Install packages
```bash
composer install
```

### Execute commands
Create a user
```bash
php .\bin\console user add --username=test --password=test --firstname=John --lastname=Doe --description="Test user" --roles="ROLE_APP,ROLE_API"
```

Create an API token
```bash
php .\bin\console api-token create --username=test
```

### Start the server
Start the symfony server
```bash
symfony server:start
```

## Next steps
### Improvements
- Speech to text feature
- Chat feature
- Translation for other languages

### Ideas
- Dockerize the project
- Add an external database
- Drupalize the project in order to get benefit of the security layer

### Commands
```bash
composer update && composer clear-cache && composer dump-autoload --optimize
```
```bash
php .\bin\console cache:clear
```
```bash
php bin/console asset-map:compile
```
