<?php
/**
 * Gorgias plugin for Craft CMS 4.x
 *
 * Gorgias Integration for CraftCMS and CraftCommerce
 *
 * @link      https://www.punchbuggy.com.au
 * @copyright Copyright (c) 2022 Punch Buggy
 */

namespace punchbuggy\craftgorgias\services;

use punchbuggy\craftgorgias\Gorgias;
use craft\helpers\Json;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use punchbuggy\craftgorgias\models\GorgiasIntegration;
use punchbuggy\craftgorgias\models\GorgiasCustomerWidget;
use punchbuggy\craftgorgias\models\GorgiasTicketWidget;

use Craft;
use craft\base\Component;

/**
 * GorgiasService Service
 *
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Punch Buggy
 * @package   Gorgias
 * @since     1.0.0
 */
class GorgiasService extends Component
{

	private $client;
	private $baseApiUrl;

	public function __construct() {
		$settings = Gorgias::getInstance()->getSettings();

        $this->baseApiUrl = Gorgias::getInstance()->getSettings()->getBaseApiUrl();
        $username = Gorgias::getInstance()->getSettings()->getUsername();
        $apiKey = Gorgias::getInstance()->getSettings()->getApiKey();

        $authToken = base64_encode($username .':'.$apiKey);

        try {
            $this->client = new Client( [
                'base_uri' => $this->baseApiUrl,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => 'Basic ' . $authToken,
                ],
                'verify' => false //Remove for go live
            ]);
        }
        catch (ClientException $e) {
             $this->setFailFlash(Craft::t('gorgias',"Gorgias Authentication Failed"));
        }
	}

	public function createIntegration() : bool|int {

		$integration = new GorgiasIntegration();

		try {
            $response = $this->client->post($this->baseApiUrl . 'integrations', ['body' => json_encode($integration)]);

            $gorgiasIntegration = json_decode($response->getBody()->getContents());

        }
        catch (ClientException $e) {
            return false;
        }

        return $gorgiasIntegration->id;
	}

	public function createWidgets() : bool|int {

		try {
			$customerWidget = new GorgiasCustomerWidget();

			$response = $this->client->post($this->baseApiUrl . 'widgets', ['body' => json_encode($customerWidget)]);
		}
		catch (ClientException $e) {
            return false;
        }

        try {
			$ticketWidget = new GorgiasTicketWidget();

			$response = $this->client->post($this->baseApiUrl . 'widgets', ['body' => json_encode($ticketWidget)]);
		}
		catch (ClientException $e) {
            return false;
        }

        return true;
	}


	public function listWidgets($integrationId) : bool|int {

		$response = $this->client->get($this->baseApiUrl . 'widgets?limit=30');

        $gorgiasWidgets = json_decode($response->getBody()->getContents());

        var_dump($gorgiasWidgets->data);
        exit();
	}

	public function deleteIntegration($integrationId) : bool {

		try {
            $response = $this->client->delete($this->baseApiUrl . 'integrations/' . $integrationId . '/');
        }
        catch (ClientException $e) {
            return false;
        }

        return true;
	}

	public function deleteWidgets($integrationId) : bool {
		//get all widgets and get Ids of widgets that are attached to this integration

		try {
            $response = $this->client->delete($this->baseApiUrl . 'integrations/' . $integrationId . '/');
        }
        catch (ClientException $e) {
            return false;
        }

        return true;
	}
}