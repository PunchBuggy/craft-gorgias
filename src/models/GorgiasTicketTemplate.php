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

	public $meta = [
		'color' => '#000'
	];

	public $widgets = [];


	public function __construct() {

		$customerUrl = UrlHelper::cpUrl('users/{{userId}}');

		$this->widgets[0] = [
			'path' => '',
			'type' => 'card',
			'order' => 0,
			'title' => '{{firstName}} {{lastName}}',
			'meta' => [
				'link' => $customerUrl,
				'color' => '',
				'pictureUrl' => '',
				'displayCard' => true
			]
		];

		$this->widgets[0]['widgets'][] = [
			'path' => 'dateUpdated',
			'type' => 'date',
			'order' => 0,
			'title' => 'Date updated'
		];


		$this->widgets[0]['widgets'][] = [
			'path' => 'firstName',
			'type' => 'text',
			'order' => 1,
			'title' => 'First Name'
		];

		$this->widgets[0]['widgets'][] = [
			'path' => 'lastName',
			'type' => 'text',
			'order' => 1,
			'title' => 'Last Name'
		];

		$this->widgets[0]['widgets'][] = [
			'path' => 'userType',
			'type' => 'text',
			'order' => 2,
			'title' => 'User Type'
		];

		$this->widgets[0]['widgets'][] = [
			'path' => 'lastLogin',
			'type' => 'date',
			'order' => 3,
			'title' => 'Last Login'
		];

		$orderUrl = UrlHelper::cpUrl('commerce/orders/{{id}}');

		$this->widgets[1] = [
			'path' => '',
			'type' => 'card',
			'order' => 1,
			'title' => 'Orders'
		];

		$this->widgets[1]['widgets'][] = [
			'path' => 'orders',
			'type' => 'list',
			'widgets' => [
				[
					'type' => 'card',
					'title' => 'Order {{orderNumber}}',
					'meta' => [
						'link' => $orderUrl,
						'color' => '',
						'pictureUrl' => '',
						'displayCard' => true
					],
					'widgets' => [
						[
							'path' => 'paymentStatus',
							'type' => 'text',
							'order' => 0,
							'title' => 'Payment Status'
						],
						[
							'path' => 'orderStatus',
							'type' => 'text',
							'order' => 0,
							'title' => 'Order Status'
						],
						[
							'path' => 'dateCreated',
							'type' => 'date',
							'order' => 0,
							'title' => 'Date Created'
						],
						[
							'path' => 'gateway',
							'type' => 'text',
							'order' => 1,
							'title' => 'Payment Gateway'
						],
						[
							'path' => 'shippingMethod',
							'type' => 'text',
							'order' => 2,
							'title' => 'Shipping Method'
						],
						[
							'path' => 'orderTotal',
							'type' => 'text',
							'order' => 3,
							'title' => 'Order Total'
						],
						[
							'path' => 'shippingTotal',
							'type' => 'text',
							'order' => 4,
							'title' => 'Shipping Total'
						],
						[
							'path' => 'discountTotal',
							'type' => 'text',
							'order' => 5,
							'title' => 'Discount Total'
						],
						[
							'path' => 'taxTotal',
							'type' => 'text',
							'order' => 6,
							'title' => 'Tax Total'
						],
						[
							'path' => 'couponCode',
							'type' => 'text',
							'order' => 7,
							'title' => 'Coupon Code'
						],
						[
							'type' => 'card',
							'order' => 8,
							'title' => 'Billing Address',
							'widgets' => [
								[
									'path' => 'billingAddressFullName',
									'type' => 'text',
									'order' => 0,
									'title' => 'Full Name'
								],
								[
									'path' => 'billingAddressOrganization',
									'type' => 'text',
									'order' => 1,
									'title' => 'Company'
								],
								[
									'path' => 'billingAddressLine1',
									'type' => 'text',
									'order' => 2,
									'title' => 'Address Line 1'
								],
								[
									'path' => 'billingAddressLine2',
									'type' => 'text',
									'order' => 3,
									'title' => 'Address Line 2'
								],
								[
									'path' => 'billingAddressLocality',
									'type' => 'text',
									'order' => 4,
									'title' => 'Suburb'
								],
								[
									'path' => 'billingAddressAdministrativeArea',
									'type' => 'text',
									'order' => 5,
									'title' => 'State'
								],
								[
									'path' => 'billingAddressPostalCode',
									'type' => 'text',
									'order' => 6,
									'title' => 'Post Code'
								],
								[
									'path' => 'billingAddressCountry',
									'type' => 'text',
									'order' => 7,
									'title' => 'Country'
								]
							]
						],
						[
							'type' => 'card',
							'order' => 9,
							'title' => 'Shipping Address',
							'widgets' => [
								[
									'path' => 'shippingAddressFullName',
									'type' => 'text',
									'order' => 0,
									'title' => 'Full Name'
								],
								[
									'path' => 'shippingAddressOrganization',
									'type' => 'text',
									'order' => 1,
									'title' => 'Company'
								],
								[
									'path' => 'shippingAddressLine1',
									'type' => 'text',
									'order' => 2,
									'title' => 'Address Line 1'
								],
								[
									'path' => 'shippingAddressLine2',
									'type' => 'text',
									'order' => 3,
									'title' => 'Address Line 2'
								],
								[
									'path' => 'shippingAddressLocality',
									'type' => 'text',
									'order' => 4,
									'title' => 'Suburb'
								],
								[
									'path' => 'shippingAddressAdministrativeArea',
									'type' => 'text',
									'order' => 5,
									'title' => 'State'
								],
								[
									'path' => 'shippingAddressPostalCode',
									'type' => 'text',
									'order' => 6,
									'title' => 'Post Code'
								],
								[
									'path' => 'shippingAddressCountry',
									'type' => 'text',
									'order' => 7,
									'title' => 'Country'
								]
							]
						],
						[
							'path' => 'orderItems',
							'type' => 'list',
							'widgets' => [
								[
									'type' => 'card',
									'order' => 10,
									'title' => 'Order Items',
									'widgets' => [
										[
											'path' => 'status',
											'type' => 'text',
											'order' => 0,
											'title' => 'Status'
										],
										[
											'path' => 'description',
											'type' => 'text',
											'order' => 1,
											'title' => 'Description'
										],
										[
											'path' => 'sku',
											'type' => 'text',
											'order' => 2,
											'title' => 'SKU'
										],
										[
											'path' => 'qty',
											'type' => 'text',
											'order' => 3,
											'title' => 'Quantity'
										],
										[
											'path' => 'price',
											'type' => 'text',
											'order' => 4,
											'title' => 'Price'
										],
										[
											'path' => 'salePrice',
											'type' => 'text',
											'order' => 5,
											'title' => 'Sale Price'
										],
										[
											'path' => 'discount',
											'type' => 'text',
											'order' => 6,
											'title' => 'Discount'
										],
										[
											'path' => 'total',
											'type' => 'text',
											'order' => 7,
											'title' => 'Total'
										],
										[
											'path' => 'note',
											'type' => 'text',
											'order' => 8,
											'title' => 'Customer Note'
										],
										[
											'path' => 'privateNote',
											'type' => 'text',
											'order' => 9,
											'title' => 'Private Note'
										],
										[
											'path' => 'length',
											'type' => 'text',
											'order' => 10,
											'title' => 'Length'
										],
										[
											'path' => 'height',
											'type' => 'text',
											'order' => 11,
											'title' => 'Height'
										],
										[
											'path' => 'width',
											'type' => 'text',
											'order' => 12,
											'title' => 'Width'
										],
										[
											'path' => 'weight',
											'type' => 'text',
											'order' => 13,
											'title' => 'Weight'
										]
									]
								]
							]
						]

					]
				]
				
			]
		];



		$this->widgets[2] = [
			'path' => '',
			'type' => 'card',
			'order' => 1,
			'title' => 'Carts'
		];

		$this->widgets[2]['widgets'][] = [
			'path' => 'carts',
			'type' => 'list',
			'widgets' => [
				[
					'type' => 'card',
					'title' => 'Cart {{orderNumber}}',
					'meta' => [
						'link' => $orderUrl,
						'color' => '',
						'pictureUrl' => '',
						'displayCard' => true
					],
					'widgets' => [
						[
							'path' => 'dateCreated',
							'type' => 'text',
							'order' => 0,
							'title' => 'Date Created'
						],
						[
							'path' => 'shippingMethod',
							'type' => 'text',
							'order' => 1,
							'title' => 'Shipping Method'
						],
						[
							'path' => 'orderTotal',
							'type' => 'text',
							'order' => 2,
							'title' => 'Order Total'
						],
						[
							'path' => 'shippingTotal',
							'type' => 'text',
							'order' => 3,
							'title' => 'Shipping Total'
						],
						[
							'path' => 'discountTotal',
							'type' => 'text',
							'order' => 4,
							'title' => 'Discount Total'
						],
						[
							'path' => 'taxTotal',
							'type' => 'text',
							'order' => 5,
							'title' => 'Tax Total'
						],
						[
							'path' => 'couponCode',
							'type' => 'text',
							'order' => 6,
							'title' => 'Coupon Code'
						],
						[
							'type' => 'card',
							'order' => 7,
							'title' => 'Billing Address',
							'widgets' => [
								[
									'path' => 'billingAddressFullName',
									'type' => 'text',
									'order' => 0,
									'title' => 'Full Name'
								],
								[
									'path' => 'billingAddressOrganization',
									'type' => 'text',
									'order' => 1,
									'title' => 'Company'
								],
								[
									'path' => 'billingAddressLine1',
									'type' => 'text',
									'order' => 2,
									'title' => 'Address Line 1'
								],
								[
									'path' => 'billingAddressLine2',
									'type' => 'text',
									'order' => 3,
									'title' => 'Address Line 2'
								],
								[
									'path' => 'billingAddressLocality',
									'type' => 'text',
									'order' => 4,
									'title' => 'Suburb'
								],
								[
									'path' => 'billingAddressAdministrativeArea',
									'type' => 'text',
									'order' => 5,
									'title' => 'State'
								],
								[
									'path' => 'billingAddressPostalCode',
									'type' => 'text',
									'order' => 6,
									'title' => 'Post Code'
								],
								[
									'path' => 'billingAddressCountry',
									'type' => 'text',
									'order' => 7,
									'title' => 'Country'
								]
							]
						],
						[
							'type' => 'card',
							'order' => 8,
							'title' => 'Shipping Address',
							'widgets' => [
								[
									'path' => 'shippingAddressFullName',
									'type' => 'text',
									'order' => 0,
									'title' => 'Full Name'
								],
								[
									'path' => 'shippingAddressOrganization',
									'type' => 'text',
									'order' => 1,
									'title' => 'Company'
								],
								[
									'path' => 'shippingAddressLine1',
									'type' => 'text',
									'order' => 2,
									'title' => 'Address Line 1'
								],
								[
									'path' => 'shippingAddressLine2',
									'type' => 'text',
									'order' => 3,
									'title' => 'Address Line 2'
								],
								[
									'path' => 'shippingAddressLocality',
									'type' => 'text',
									'order' => 4,
									'title' => 'Suburb'
								],
								[
									'path' => 'shippingAddressAdministrativeArea',
									'type' => 'text',
									'order' => 5,
									'title' => 'State'
								],
								[
									'path' => 'shippingAddressPostalCode',
									'type' => 'text',
									'order' => 6,
									'title' => 'Post Code'
								],
								[
									'path' => 'shippingAddressCountry',
									'type' => 'text',
									'order' => 7,
									'title' => 'Country'
								]
							]
						],
						[
							'path' => 'orderItems',
							'type' => 'list',
							'widgets' => [
								[
									'type' => 'card',
									'order' => 7,
									'title' => 'Order Items',
									'widgets' => [
										[
											'path' => 'description',
											'type' => 'text',
											'order' => 0,
											'title' => 'Description'
										],
										[
											'path' => 'sku',
											'type' => 'text',
											'order' => 1,
											'title' => 'SKU'
										],
										[
											'path' => 'qty',
											'type' => 'text',
											'order' => 2,
											'title' => 'Quantity'
										],
										[
											'path' => 'price',
											'type' => 'text',
											'order' => 3,
											'title' => 'Price'
										],
										[
											'path' => 'salePrice',
											'type' => 'text',
											'order' => 4,
											'title' => 'Sale Price'
										],
										[
											'path' => 'discount',
											'type' => 'text',
											'order' => 5,
											'title' => 'Discount'
										],
										[
											'path' => 'total',
											'type' => 'text',
											'order' => 6,
											'title' => 'Total'
										],
										[
											'path' => 'note',
											'type' => 'text',
											'order' => 7,
											'title' => 'Customer Note'
										],
										[
											'path' => 'privateNote',
											'type' => 'text',
											'order' => 8,
											'title' => 'Private Note'
										],
										[
											'path' => 'length',
											'type' => 'text',
											'order' => 9,
											'title' => 'Length'
										],
										[
											'path' => 'height',
											'type' => 'text',
											'order' => 10,
											'title' => 'Height'
										],
										[
											'path' => 'width',
											'type' => 'text',
											'order' => 11,
											'title' => 'Width'
										],
										[
											'path' => 'weight',
											'type' => 'text',
											'order' => 12,
											'title' => 'Weight'
										]
									]
								]
							]
						]

					]
				]
				
			]
		];


	}

}