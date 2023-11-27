<?php

namespace punchbuggy\craftgorgias;

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use punchbuggy\craftgorgias\models\Settings;

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
            // ...
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

    private function attachEventHandlers(): void
    {
        // Register event handlers here ...
        // (see https://craftcms.com/docs/4.x/extend/events.html to get started)
    }
}
