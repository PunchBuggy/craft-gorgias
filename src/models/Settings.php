<?php

namespace punchbuggy\craftgorgias\models;

use Craft;
use craft\base\Model;

/**
 * Gorgias settings
 */
class Settings extends Model
{

	/**
     * Default API Key
     *
     * @var string
     */
    public $baseApiUrl = '';

    public $username = '';

    public $apiKey = '';

    public $schemaId = '';

    public $tokenId = '';

    public $gorgiasIntegrationId = '';

    public function rules() : array
    {
        return [
            ['baseApiUrl', 'string'],
            ['baseApiUrl', 'default', 'value' => ''],
            ['username', 'string'],
            ['username', 'default', 'value' => ''],
            ['apiKey', 'string'],
            ['apiKey', 'default', 'value' => ''],
            ['schemaId', 'integer'],
            ['tokenId', 'integer'],
            ['gorgiasIntegrationId', 'integer'],
        ];
    }


    public function getBaseApiUrl() {
        return Craft::parseEnv($this->baseApiUrl);
    }

    public function getUsername() {
        return Craft::parseEnv($this->username);
    }

    public function getApiKey() {
        return Craft::parseEnv($this->apiKey);
    }

    public function getSchemaId() {

        if (!$this->schemaId || $this->schemaId === "") {
            $gqlService = Craft::$app->getGql();
            $allSchemas = $gqlService->getSchemas();
            foreach ($allSchemas as $schema) {
                if ($schema->name === 'Gorgias') {
                    $this->schemaId = $schema->id;
                }
            }
           // settings.getSection(section.uid)
        }
        return $this->schemaId;
    }

    public function getTokenId() {

        if (!$this->tokenId || $this->tokenId === "") {
            $gqlService = Craft::$app->getGql();
            $token = $gqlService->getTokenByName('Gorgias');
            $this->tokenId = $token->id;
        }
        return $this->tokenId;
    }

    public function getGorgiasIntegrationId() {
        return $this->gorgiasIntegrationId;
    }
}
