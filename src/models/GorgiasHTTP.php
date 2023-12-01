<?php
/**
 * Gorgias plugin for Craft CMS 4.x
 *
 * Gorgias Integration for CraftCMS and CraftCommerce
 *
 * @link      https://www.punchbuggy.com.au
 * @copyright Copyright (c) 2023 Punch Buggy
 */

namespace punchbuggy\craftgorgias\models;

use punchbuggy\craftgorgias\Gorgias;
use craft\helpers\UrlHelper;

use Craft;
use craft\base\Model;

/**
 * Gorgias Integration Model
 *
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Punch Buggy
 * @package   Gorgias
 * @since     0.0.1
 */
class GorgiasHTTP extends Model
{

	public $url = "";
	public $method = 'POST';
	public $form = '{{context}}';
	public $request_content_type = 'application/json';
	public $response_content_type = 'application/json';
	public $triggers;
	public $headers;

	public function __construct() {

		$this->url = UrlHelper::actionUrl('gorgias/gorgias-endpoints/users?customerEmail={{ticket.customer.email}}');


		$tokenId = Gorgias::getInstance()->getSettings()->tokenId;

		$gqlService = Craft::$app->getGql();
        $tokenObject = $gqlService->getTokenById($tokenId);
        $token = "";

        if ($tokenObject) {
        	$token = $tokenObject->accessToken;
        }

		$this->triggers = [
			"ticket-created" => true,
			"ticket-updated" => true,
			"ticket-message-created" => true
		];

		$this->headers = ["X-Authorization" => $token];

	}

}
