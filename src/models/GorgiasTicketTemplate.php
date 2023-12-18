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
			'title' => Craft::t('gorgias',"Date updated")
		];


		$this->widgets[0]['widgets'][] = [
			'path' => 'firstName',
			'type' => 'text',
			'order' => 1,
			'title' => Craft::t('gorgias',"First Name")
		];

		$this->widgets[0]['widgets'][] = [
			'path' => 'lastName',
			'type' => 'text',
			'order' => 1,
			'title' => Craft::t('gorgias',"Last Name")
		];

		$this->widgets[0]['widgets'][] = [
			'path' => 'userType',
			'type' => 'text',
			'order' => 2,
			'title' => Craft::t('gorgias',"User Type")
		];

		$this->widgets[0]['widgets'][] = [
			'path' => 'lastLogin',
			'type' => 'date',
			'order' => 3,
			'title' => Craft::t('gorgias',"Last Login")
		];

		$orderUrl = UrlHelper::cpUrl('commerce/orders/{{id}}');

		$this->widgets[1] = [
			'path' => '',
			'type' => 'card',
			'order' => 1,
			'title' => Craft::t('gorgias',"Orders")
		];

		$this->widgets[1]['widgets'][] = [
			'path' => 'orders',
			'type' => 'list',
			'widgets' => [
				[
					'type' => 'card',
					'title' => Craft::t('gorgias',"Order") .' {{orderNumber}}',
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
							'title' => Craft::t('gorgias',"Payment Status")
						],
						[
							'path' => 'orderStatus',
							'type' => 'text',
							'order' => 0,
							'title' => Craft::t('gorgias',"Order Status")
						],
						[
							'path' => 'dateCreated',
							'type' => 'date',
							'order' => 0,
							'title' => Craft::t('gorgias',"Date Created")
						],
						[
							'path' => 'gateway',
							'type' => 'text',
							'order' => 1,
							'title' => Craft::t('gorgias',"Payment Gateway")
						],
						[
							'path' => 'shippingMethod',
							'type' => 'text',
							'order' => 2,
							'title' => Craft::t('gorgias',"Shipping Method")
						],
						[
							'path' => 'orderTotal',
							'type' => 'text',
							'order' => 3,
							'title' => Craft::t('gorgias',"Order Total")
						],
						[
							'path' => 'shippingTotal',
							'type' => 'text',
							'order' => 4,
							'title' => Craft::t('gorgias',"Shipping Total")
						],
						[
							'path' => 'discountTotal',
							'type' => 'text',
							'order' => 5,
							'title' => Craft::t('gorgias',"Discount Total")
						],
						[
							'path' => 'taxTotal',
							'type' => 'text',
							'order' => 6,
							'title' => Craft::t('gorgias',"Tax Total")
						],
						[
							'path' => 'couponCode',
							'type' => 'text',
							'order' => 7,
							'title' => Craft::t('gorgias',"Coupon Code")
						],
						[
							'type' => 'card',
							'order' => 8,
							'title' => Craft::t('gorgias',"Billing Address"),
							'widgets' => [
								[
									'path' => 'billingAddressFullName',
									'type' => 'text',
									'order' => 0,
									'title' => Craft::t('gorgias',"Billing Address Full Name")
								],
								[
									'path' => 'billingAddressOrganization',
									'type' => 'text',
									'order' => 1,
									'title' => Craft::t('gorgias',"Billing Address Organization")
								],
								[
									'path' => 'billingAddressLine1',
									'type' => 'text',
									'order' => 2,
									'title' => Craft::t('gorgias',"Billing Address Line 1")
								],
								[
									'path' => 'billingAddressLine2',
									'type' => 'text',
									'order' => 3,
									'title' => Craft::t('gorgias',"Billing Address Line 2")
								],
								[
									'path' => 'billingAddressLocality',
									'type' => 'text',
									'order' => 4,
									'title' => Craft::t('gorgias',"Billing Address Locality")
								],
								[
									'path' => 'billingAddressAdministrativeArea',
									'type' => 'text',
									'order' => 5,
									'title' => Craft::t('gorgias',"Billing Address Administrative Area")
								],
								[
									'path' => 'billingAddressPostalCode',
									'type' => 'text',
									'order' => 6,
									'title' => Craft::t('gorgias',"Billing Address Postal Code")
								],
								[
									'path' => 'billingAddressCountry',
									'type' => 'text',
									'order' => 7,
									'title' => Craft::t('gorgias',"Billing Address Country")
								]
							]
						],
						[
							'type' => 'card',
							'order' => 9,
							'title' => Craft::t('gorgias',"Shipping Address"),
							'widgets' => [
								[
									'path' => 'shippingAddressFullName',
									'type' => 'text',
									'order' => 0,
									'title' => Craft::t('gorgias',"Shipping Address Full Name")
								],
								[
									'path' => 'shippingAddressOrganization',
									'type' => 'text',
									'order' => 1,
									'title' => Craft::t('gorgias',"Shipping Address Organization")
								],
								[
									'path' => 'shippingAddressLine1',
									'type' => 'text',
									'order' => 2,
									'title' => Craft::t('gorgias',"Shipping Address Line 1")
								],
								[
									'path' => 'shippingAddressLine2',
									'type' => 'text',
									'order' => 3,
									'title' => Craft::t('gorgias',"Shipping Address Line 2")
								],
								[
									'path' => 'shippingAddressLocality',
									'type' => 'text',
									'order' => 4,
									'title' => Craft::t('gorgias',"Shipping Address Locality")
								],
								[
									'path' => 'shippingAddressAdministrativeArea',
									'type' => 'text',
									'order' => 5,
									'title' => Craft::t('gorgias',"Shipping Address Administrative Area")
								],
								[
									'path' => 'shippingAddressPostalCode',
									'type' => 'text',
									'order' => 6,
									'title' => Craft::t('gorgias',"Shipping Address Postal Code")
								],
								[
									'path' => 'shippingAddressCountry',
									'type' => 'text',
									'order' => 7,
									'title' => Craft::t('gorgias',"Shipping Address Country")
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
									'title' => Craft::t('gorgias',"Order Items"),
									'widgets' => [
										[
											'path' => 'status',
											'type' => 'text',
											'order' => 0,
											'title' => Craft::t('gorgias',"Line Item Status")
										],
										[
											'path' => 'description',
											'type' => 'text',
											'order' => 1,
											'title' => Craft::t('gorgias',"Line Item Description")
										],
										[
											'path' => 'sku',
											'type' => 'text',
											'order' => 2,
											'title' => Craft::t('gorgias',"Line Item SKU")
										],
										[
											'path' => 'qty',
											'type' => 'text',
											'order' => 3,
											'title' => Craft::t('gorgias',"Line Item Quantity")
										],
										[
											'path' => 'price',
											'type' => 'text',
											'order' => 4,
											'title' => Craft::t('gorgias',"Line Item Price")
										],
										[
											'path' => 'salePrice',
											'type' => 'text',
											'order' => 5,
											'title' => Craft::t('gorgias',"Line Item Sale Price")
										],
										[
											'path' => 'discount',
											'type' => 'text',
											'order' => 6,
											'title' => Craft::t('gorgias',"Line Item Discount")
										],
										[
											'path' => 'total',
											'type' => 'text',
											'order' => 7,
											'title' => Craft::t('gorgias',"Line Item Total")
										],
										[
											'path' => 'note',
											'type' => 'text',
											'order' => 8,
											'title' => Craft::t('gorgias',"Line Item Customer Note")
										],
										[
											'path' => 'privateNote',
											'type' => 'text',
											'order' => 9,
											'title' => Craft::t('gorgias',"Line Item Private Note")
										],
										[
											'path' => 'length',
											'type' => 'text',
											'order' => 10,
											'title' => Craft::t('gorgias',"Line Item Length")
										],
										[
											'path' => 'height',
											'type' => 'text',
											'order' => 11,
											'title' => Craft::t('gorgias',"Line Item Height")
										],
										[
											'path' => 'width',
											'type' => 'text',
											'order' => 12,
											'title' => Craft::t('gorgias',"Line Item Width")
										],
										[
											'path' => 'weight',
											'type' => 'text',
											'order' => 13,
											'title' => Craft::t('gorgias',"Line Item Weight")
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
			'title' => Craft::t('gorgias',"Carts")
		];

		$this->widgets[2]['widgets'][] = [
			'path' => 'carts',
			'type' => 'list',
			'widgets' => [
				[
					'type' => 'card',
					'title' => Craft::t('gorgias',"Cart") .' {{orderNumber}}',
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
							'title' => Craft::t('gorgias',"Date Created")
						],
						[
							'path' => 'shippingMethod',
							'type' => 'text',
							'order' => 1,
							'title' => Craft::t('gorgias',"Shipping Method")
						],
						[
							'path' => 'orderTotal',
							'type' => 'text',
							'order' => 2,
							'title' => Craft::t('gorgias',"Order Total")
						],
						[
							'path' => 'shippingTotal',
							'type' => 'text',
							'order' => 3,
							'title' => Craft::t('gorgias',"Shipping Total")
						],
						[
							'path' => 'discountTotal',
							'type' => 'text',
							'order' => 4,
							'title' => Craft::t('gorgias',"Discount Total")
						],
						[
							'path' => 'taxTotal',
							'type' => 'text',
							'order' => 5,
							'title' =>  Craft::t('gorgias',"Tax Total")
						],
						[
							'path' => 'couponCode',
							'type' => 'text',
							'order' => 6,
							'title' => Craft::t('gorgias',"Coupon Code")
						],
						[
							'type' => 'card',
							'order' => 7,
							'title' => Craft::t('gorgias',"Billing Address"),
							'widgets' => [
								[
									'path' => 'billingAddressFullName',
									'type' => 'text',
									'order' => 0,
									'title' => Craft::t('gorgias',"Billing Address Full Name")
								],
								[
									'path' => 'billingAddressOrganization',
									'type' => 'text',
									'order' => 1,
									'title' => Craft::t('gorgias',"Billing Address Organization")
								],
								[
									'path' => 'billingAddressLine1',
									'type' => 'text',
									'order' => 2,
									'title' => Craft::t('gorgias',"Billing Address Line 1")
								],
								[
									'path' => 'billingAddressLine2',
									'type' => 'text',
									'order' => 3,
									'title' => Craft::t('gorgias',"Billing Address Line 2")
								],
								[
									'path' => 'billingAddressLocality',
									'type' => 'text',
									'order' => 4,
									'title' => Craft::t('gorgias',"Billing Address Locality")
								],
								[
									'path' => 'billingAddressAdministrativeArea',
									'type' => 'text',
									'order' => 5,
									'title' => Craft::t('gorgias',"Billing Address Administrative Area")
								],
								[
									'path' => 'billingAddressPostalCode',
									'type' => 'text',
									'order' => 6,
									'title' => Craft::t('gorgias',"Billing Address Postal Code")
								],
								[
									'path' => 'billingAddressCountry',
									'type' => 'text',
									'order' => 7,
									'title' => Craft::t('gorgias',"Billing Address Country")
								]
							]
						],
						[
							'type' => 'card',
							'order' => 8,
							'title' => Craft::t('gorgias',"Shipping Address"),
							'widgets' => [
								[
									'path' => 'shippingAddressFullName',
									'type' => 'text',
									'order' => 0,
									'title' => Craft::t('gorgias',"Shipping Address Full Name")
								],
								[
									'path' => 'shippingAddressOrganization',
									'type' => 'text',
									'order' => 1,
									'title' => Craft::t('gorgias',"Shipping Address Organization")
								],
								[
									'path' => 'shippingAddressLine1',
									'type' => 'text',
									'order' => 2,
									'title' => Craft::t('gorgias',"Shipping Address Line 1")
								],
								[
									'path' => 'shippingAddressLine2',
									'type' => 'text',
									'order' => 3,
									'title' => Craft::t('gorgias',"Shipping Address Line 2")
								],
								[
									'path' => 'shippingAddressLocality',
									'type' => 'text',
									'order' => 4,
									'title' => Craft::t('gorgias',"Shipping Address Locality")
								],
								[
									'path' => 'shippingAddressAdministrativeArea',
									'type' => 'text',
									'order' => 5,
									'title' => Craft::t('gorgias',"Shipping Address Administrative Area")
								],
								[
									'path' => 'shippingAddressPostalCode',
									'type' => 'text',
									'order' => 6,
									'title' => Craft::t('gorgias',"Shipping Address Postal Code")
								],
								[
									'path' => 'shippingAddressCountry',
									'type' => 'text',
									'order' => 7,
									'title' => Craft::t('gorgias',"Shipping Address Country")
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
									'title' => Craft::t('gorgias',"Order Items"),
									'widgets' => [
										[
											'path' => 'description',
											'type' => 'text',
											'order' => 0,
											'title' => Craft::t('gorgias',"Line Item Description")
										],
										[
											'path' => 'sku',
											'type' => 'text',
											'order' => 1,
											'title' => Craft::t('gorgias',"Line Item SKU")
										],
										[
											'path' => 'qty',
											'type' => 'text',
											'order' => 2,
											'title' => Craft::t('gorgias',"Line Item Quantity")
										],
										[
											'path' => 'price',
											'type' => 'text',
											'order' => 3,
											'title' => Craft::t('gorgias',"Line Item Price")
										],
										[
											'path' => 'salePrice',
											'type' => 'text',
											'order' => 4,
											'title' => Craft::t('gorgias',"Line Item Sale Price")
										],
										[
											'path' => 'discount',
											'type' => 'text',
											'order' => 5,
											'title' => Craft::t('gorgias',"Line Item Discount")
										],
										[
											'path' => 'total',
											'type' => 'text',
											'order' => 6,
											'title' => Craft::t('gorgias',"Line Item Total")
										],
										[
											'path' => 'note',
											'type' => 'text',
											'order' => 7,
											'title' => Craft::t('gorgias',"Line Item Customer Note")
										],
										[
											'path' => 'privateNote',
											'type' => 'text',
											'order' => 8,
											'title' => Craft::t('gorgias',"Line Item Private Note")
										],
										[
											'path' => 'length',
											'type' => 'text',
											'order' => 9,
											'title' => Craft::t('gorgias',"Line Item Length")
										],
										[
											'path' => 'height',
											'type' => 'text',
											'order' => 10,
											'title' => Craft::t('gorgias',"Line Item Height")
										],
										[
											'path' => 'width',
											'type' => 'text',
											'order' => 11,
											'title' => Craft::t('gorgias',"Line Item Width")
										],
										[
											'path' => 'weight',
											'type' => 'text',
											'order' => 12,
											'title' => Craft::t('gorgias',"Line Item Weight")
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