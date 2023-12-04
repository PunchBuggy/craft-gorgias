<?php

namespace punchbuggy\craftgorgias;

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use punchbuggy\craftgorgias\models\Settings;
use craft\helpers\Gql as GqlHelper;
use craft\helpers\UrlHelper;
use craft\services\Gql;
use craft\services\Plugins;
use craft\events\PluginEvent;
use yii\base\Event;
use craft\models\GqlSchema;
use craft\models\GqlToken;
use punchbuggy\craftgorgias\services\GorgiasService;


/**
 * Gorgias plugin
 *
 * @method static Gorgias getInstance()
 * @method Settings getSettings()
 * @author Punch Buggy Digital Agency <info@punchbuggy.com.au>
 * @copyright Punch Buggy Digital Agency
 * @license https://craftcms.github.io/license/ Craft License
 */
class Gorgias extends Plugin
{
    public string $schemaVersion = '1.0.0';
    public bool $hasCpSettings = true;


    public static function config(): array
    {
        return [
            'components' => [
                // Define component configs here...
            ],
        ];
    }

    public function init(): void
    {
        parent::init();

        // Defer most setup tasks until Craft is fully initialized
        Craft::$app->onInit(function() {
            $this->attachEventHandlers();
        });
    }

    protected function createSettingsModel(): ?Model
    {
        return Craft::createObject(Settings::class);
    }

    protected function settingsHtml(): ?string
    {
        return Craft::$app->view->renderTemplate('gorgias/_settings.twig', [
            'plugin' => $this,
            'settings' => $this->getSettings(),
        ]);
    }

    public function afterSaveSettings() :void
    {
            parent::afterSaveSettings();
            Craft::$app->response
                ->redirect(UrlHelper::cpUrl('settings/plugins/gorgias'))
                ->send();
    }
    
    private function attachEventHandlers(): void
    {
        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                    //Create Schema and create token, then store in settings
                    $gqlService = Craft::$app->getGql();

                    $settings = $this->getSettings();

                    $schema = new GqlSchema();
                    $schema->name = "Gorgias";
                    $schema->scope = [
                        'usergroups.everyone:read'
                    ];

                    if ($gqlService->saveSchema($schema)) {

                        $token = new GqlToken();
                        $token->name = 'Gorgias';
                        $token->accessToken = Craft::$app->getSecurity()->generateRandomString(32);
                        $token->enabled = true;
                        $token->schemaId = $schema->id;

                        $gqlService->saveToken($token);

                        $settings->schemaId = $schema->id;
                        $settings->tokenId = $token->id;

                        $pluginSettingsSaved = Craft::$app->getPlugins()->savePluginSettings( Gorgias::getInstance(), $settings->toArray());

                    }
                   
                }
            }
        );

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_UNINSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                    //Remove Schema and token,
                    Craft::$app->getGql()->deleteSchemaById($this->getSettings()->schemaId);
                    Craft::$app->getGql()->deleteTokenById($this->getSettings()->tokenId);

                    //Delete integration
                    $gorgiasService = new GorgiasService();

                    if ($this->getSettings()->gorgiasIntegrationId) {
                        $gorgiasService->deleteIntegration($this->getSettings()->gorgiasIntegrationId);
                        $gorgiasService->deleteWidgets($this->getSettings()->gorgiasIntegrationId);
                    }
                }
            }
        );

        
    }
}
