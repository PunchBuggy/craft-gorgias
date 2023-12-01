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
 * Gorgias Ticket Template Model
 *
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Punch Buggy
 * @package   Gorgias
 * @since     1.0.0
 */
class GorgiasTicketTemplate extends Model
{

	public $type = 'wrapper';

	public $widgets = [
		'path' => 'customer',
		'type' => 'card',
		'order' => 0,
		'title' => '{{first_name}} {{last_name}}',
		'widgets' => []
	];


	public function __construct() {

		$this->widgets[] = [
			'path' => 'created_at',
			'type' => 'date',
			'order' => 0,
			'title' => 'Created at'
		];

		$this->widgets[] = [
			'meta' => [
				'limit' => '3',
				'orderBy' => ''
			],
			'path' => 'orders',
			'type' => 'list',
			'order' => 2,
			'widgets' => [
				'type' => 'card',
				'title' => '{{name}}',
				'widgets' =>[
					[
						'meta' => [
							'link' => ""
						]
					],
					[
						'path' => 'created_at',
						'type' => 'date',
						'title' => 'Created at'
					],
					[
						'path' => 'status',
						'type' => 'text',
						'title' => 'Order Status'
					]
				]
			]

		];
	}

}