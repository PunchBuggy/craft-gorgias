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
use punchbuggy\craftgorgias\models\GorgiasTicketTemplate;

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
 * @since     1.0.0
 */
class GorgiasCustomerWidget extends Model
{

	public $context = 'customer';
	public $type = 'http';
	public $integration_id;
	public $order = 1;
	public $template;


	public function __construct() {
		$this->integration_id = Gorgias::getInstance()->getSettings()->gorgiasIntegrationId;

		$this->template = new GorgiasTicketTemplate(); 
	}


}