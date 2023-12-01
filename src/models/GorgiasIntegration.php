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
use punchbuggy\craftgorgias\models\GorgiasHTTP;

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
class GorgiasIntegration extends Model
{

	public $description = 'CraftCMS Integration';
	public $http;
	public $name = 'CraftCMS';
	public $type = 'http';

	public function __construct() {
		$this->http = new GorgiasHTTP();
	}

}
