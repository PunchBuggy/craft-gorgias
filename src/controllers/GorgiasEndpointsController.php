<?php
/**
 * Gorgias plugin for Craft CMS 4.x
 *
 * Gorgias Integration for CraftCMS and CraftCommerce
 *
 * @link      https://www.punchbuggy.com.au
 * @copyright Copyright (c) 2022 Punch Buggy
 */

namespace punchbuggy\craftgorgias\controllers;

use punchbuggy\craftgorgias\Gorgias;

use Craft;
use craft\web\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use craft\commerce\Plugin as Commerce;
use craft\commerce\elements\Order;
use punchbuggy\craftgorgias\models\GorgiasData;
use punchbuggy\craftgorgias\services\GorgiasService;
use craft\services\Users;
use craft\services\Addresses;
use yii\web\Response;
use craft\services\Gql;
use craft\helpers\UrlHelper;


/**
 * GorgiasActions Controller
 *
 *
 * https://craftcms.com/docs/plugins/controllers
 *
 * @author    Punch Buggy
 * @package   Gorgias
 * @since     1.0.0
 */
class GorgiasEndpointsController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    array|bool|int Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected array|bool|int $allowAnonymous = ['users'];

    // Public Methods
    // =========================================================================


     /**
     * Handle a request going to our plugin's actionDoSomething URL,
     * e.g.: actions/gorgias/gorgias-endpoints/create-widget
     *
     * @return mixed
     */
    public function actionCreateWidget() : string
    {
        $gorgiasService = new GorgiasService();

        $widgetData = $gorgiasService->showWidgets();

        return $widgetData;
    }

    /**
     * Handle a request going to our plugin's actionDoSomething URL,
     * e.g.: actions/gorgias/gorgias-endpoints/create-integration
     *
     * @return mixed
     */
    public function actionCreateIntegration() : Response
    {

        // $gorgiasService = new GorgiasService();

        // $gorgiasService->listWidgets();

        $request = Craft::$app->getRequest();
        //Do the Integrations API call here.
        $settings = Gorgias::getInstance()->getSettings();

        $gorgiasService = new GorgiasService();

        $integrationId = $gorgiasService->createIntegration();

        if (!$integrationId ) {
            $this->setFailFlash(Craft::t('gorgias',"Failed to create Gorgias Integration"));
        }

        $settings->gorgiasIntegrationId = $integrationId;

        $pluginSettingsSaved = Craft::$app->getPlugins()->savePluginSettings( Gorgias::getInstance(), $settings->toArray());

        if ($pluginSettingsSaved) {
            $this->setSuccessFlash(Craft::t('gorgias', "Gorgias Integration successfully created"));
        }

        //We have created the integration - now we need to create a widget
        $gorgiasService->createWidgets();

        return $this->redirect($request->referrer);

    }

    /**
     * 
     * e.g.: actions/gorgias/gorgias-endpoints/users
     *
     * @return mixed
     */
    public function actionUsers($customerEmail) : string
    {
        
        $this->requirePostRequest();
        $headers = Craft::$app->getRequest()->getHeaders();
        $authToken = null;
        $siteUrl = getenv('DEFAULT_SITE_URL');

        if ($headers->get('Authorization')) {
            $authToken = $headers->get('Authorization');
        }
        elseif ($headers->get('X-Authorization')) {
            $authToken = $headers->get('X-Authorization');
        }

        
        $rawToken = str_replace("Bearer ", "", $authToken);
        //Authenticate using GraphQL
        $gqlService = Craft::$app->getGql();
        $settings = Gorgias::getInstance()->getSettings();
        $token = $gqlService->getTokenById($settings->tokenId);

        if ($token->accessToken !== $rawToken) {
            return json_encode(["error" => "Unauthorised Request"]);
        }
        
        //Get user form email address
        try {
            $client = new Client( [
                'base_uri' => $siteUrl . '/api/',
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $rawToken,
                ],
                'verify' => false //Remove for go live
            ]);
        }
        catch (ClientException $e) {
            return json_encode(["error" => "Unauthorised Request"]);
        }

        try {
            $response = $client->post('', ['form_params' => [
                     'query' => 'query GetUser($email: [String]) {
                      user(email: $email) {
                        id
                        firstName,
                        lastName,
                        dateCreated
                        dateUpdated
                      }
                    }',
                    'variables' => [
                         'email' => $customerEmail
                     ]
                ]
            ]);
        }
        catch (ClientException $e) {

            return json_encode(["error" => "Unauthorised Request"]);
        }

        $userBody = json_decode($response->getBody()->getContents());
        

        //User, Orders and Cart?


        $gorgiasData = new GorgiasData();

        if (isset($userBody->data) && isset($userBody->data->user)) {
            //There is a user with this email - get the ID and use that to show orders and shipping quotes.
            $userId = $userBody->data->user->id;

            $gorgiasData->setUserId($userId);
            $gorgiasData->setUserType('User');

            if ($userBody->data->user->firstName) {
                $gorgiasData->setFirstName($userBody->data->user->firstName);
            }

            if ($userBody->data->user->lastName) {
                $gorgiasData->setLastName($userBody->data->user->lastName);
            }

            if ($userBody->data->user->dateUpdated) {

                //TODO - Format this better
                $gorgiasData->setDateUpdated($userBody->data->user->dateUpdated);
            }

            //get the user for last login
            $usersService = new Users();

            $user = $usersService->getUserById($userId);

            //TODO - Format this better
            $gorgiasData->setLastLogin($user->lastLoginDate);

            
        }
        else {
            //There is no user - but maybe they are a guest.

            $gorgiasData->setUserType('Guest');

        }

        $addressService = new Addresses();

        $userOrders = Order::find()->email($customerEmail)->orderBy('dateUpdated DESC')->isCompleted(true)->limit(3)->all();

        $orders = [];

        $userCarts = Order::find()->email($customerEmail)->orderBy('dateUpdated DESC')->isCompleted(false)->limit(3)->all();

        $carts = [];


        foreach ($userOrders as $userOrder) {

            $items = [];

            $lineItems = $userOrder->getLineItems();

            foreach ($lineItems as $lineItem) {
                $items[] = [
                    'sku' => $lineItem->sku ?? '',
                    'description' => $lineItem->description ?? '',
                    'qty' => $lineItem->qty ?? '',
                    'price' => $lineItem->priceAsCurrency ?? '',
                    'salePrice' => $lineItem->salePriceAsCurrency ?? '',
                    'total' => $lineItem->totalAsCurrency ?? '',
                    'discount' => $lineItem->discountAsCurrency ?? '',
                    'status' => $lineItem->getLineItemStatus->name ?? '',
                    'note' => $lineItem->note ?? '',
                    'privateNote' => $lineItem->privateNote ?? '',
                    'length' => $lineItem->length ?? '',
                    'height' => $lineItem->height ?? '',
                    'width' => $lineItem->width ?? '',
                    'weight' => $lineItem->weight ?? '',
                ];
            }


            $orders[] = [
                'id' => $userOrder->id,
                'dateCreated' => $userOrder->dateCreated->format('Y-m-d H:i:s'),
                'orderNumber' => $userOrder->shortNumber,
                'paymentStatus' => $userOrder->paidStatus,
                'orderStatus' => $userOrder->getOrderStatus()->displayName ?? '',
                'shippingMethod' => $userOrder->shippingMethodName ?? '',
                'orderTotal' => $userOrder->storedTotalPriceAsCurrency ?? '',
                'shippingTotal' => $userOrder->storedTotalShippingCostAsCurrency ?? '',
                'discountTotal' => $userOrder->storedTotalDiscountAsCurrency ?? '',
                'taxTotal' => $userOrder->storedTotalTaxAsCurrency ?? '',
                'couponCode' => $userOrder->couponCode ?? '',
                'gateway' => $userOrder->getGateway()->displayName ?? '',
                'adminUrl' => UrlHelper::cpUrl('commerce/orders/' . $userOrder->id),
                'billingAddressFullName' => $userOrder->billingAddress->fullName ?? '',
                'billingAddressOrganization' => $userOrder->billingAddress->organization ?? '',
                'billingAddressLine1' => $userOrder->billingAddress->addressLine1 ?? '',
                'billingAddressLine2' => $userOrder->billingAddress->addressLine2 ?? '',
                'billingAddressLocality' => $userOrder->billingAddress->locality ?? '',
                'billingAddressAdministrativeArea' => $userOrder->billingAddress->administrativeArea ?? '',
                'billingAddressPostalCode' => $userOrder->billingAddress->postalCode ?? '',
                'billingAddressCountry' => $userOrder->billingAddress->country ?? '',

                'shippingAddressFullName' => $userOrder->shippingAddress->fullName ?? '',
                'shippingAddressOrganization' => $userOrder->shippingAddress->organization ?? '',
                'shippingAddressLine1' => $userOrder->shippingAddress->addressLine1 ?? '',
                'shippingAddressLine2' => $userOrder->shippingAddress->addressLine2 ?? '',
                'shippingAddressLocality' => $userOrder->shippingAddress->locality ?? '',
                'shippingAddressAdministrativeArea' => $userCart->shippingAddress->administrativeArea ?? '',
                'shippingAddressPostalCode' => $userOrder->shippingAddress->postalCode ?? '',
                'shippingAddressCountry' => $userOrder->shippingAddress->country ?? '',
                'orderItems' => $items

            ];
            
        }

        foreach ($userCarts as $userCart) {

            $items = [];

            $lineItems = $userCart->getLineItems();

            foreach ($lineItems as $lineItem) {
                $items[] = [
                    'sku' => $lineItem->sku ?? '',
                    'description' => $lineItem->description ?? '',
                    'qty' => $lineItem->qty ?? '',
                    'price' => $lineItem->priceAsCurrency ?? '',
                    'salePrice' => $lineItem->salePriceAsCurrency ?? '',
                    'total' => $lineItem->totalAsCurrency ?? '',
                    'discount' => $lineItem->discountAsCurrency ?? '',
                    'status' => $lineItem->getLineItemStatus->name ?? '',
                    'note' => $lineItem->note ?? '',
                    'privateNote' => $lineItem->privateNote ?? '',
                    'length' => $lineItem->length ?? '',
                    'height' => $lineItem->height ?? '',
                    'width' => $lineItem->width ?? '',
                    'weight' => $lineItem->weight ?? '',
                ];
            }

            $carts[] = [
                'id' => $userCart->id,
                'adminUrl' => UrlHelper::cpUrl('commerce/orders/' . $userCart->id),
                'shippingMethod' => $userCart->shippingMethodName ?? '',
                'orderTotal' => $userCart->storedTotalPriceAsCurrency ?? '',
                'shippingTotal' => $userCart->storedTotalShippingCostAsCurrency ?? '',
                'discountTotal' => $userCart->storedTotalDiscountAsCurrency ?? '',
                'taxTotal' => $userCart->storedTotalTaxAsCurrency ?? '',
                'couponCode' => $userCart->couponCode ?? '',
                'billingAddressFullName' => $userCart->billingAddress->fullName ?? '',
                'billingAddressOrganization' => $userCart->billingAddress->organization ?? '',
                'billingAddressLine1' => $userCart->billingAddress->addressLine1 ?? '',
                'billingAddressLine2' => $userCart->billingAddress->addressLine2 ?? '',
                'billingAddressLocality' => $userCart->billingAddress->locality ?? '',
                'billingAddressAdministrativeArea' => $userCart->billingAddress->administrativeArea ?? '',
                'billingAddressPostalCode' => $userCart->billingAddress->postalCode ?? '',
                'billingAddressCountry' => $userCart->billingAddress->country ?? '',

                'shippingAddressFullName' => $userCart->shippingAddress->fullName ?? '',
                'shippingAddressOrganization' => $userCart->shippingAddress->organization ?? '',
                'shippingAddressLine1' => $userCart->shippingAddress->addressLine1 ?? '',
                'shippingAddressLine2' => $userCart->shippingAddress->addressLine2 ?? '',
                'shippingAddressLocality' => $userCart->shippingAddress->locality ?? '',
                'shippingAddressAdministrativeArea' => $userCart->shippingAddress->administrativeArea ?? '',
                'shippingAddressPostalCode' => $userCart->shippingAddress->postalCode ?? '',
                'shippingAddressCountry' => $userCart->shippingAddress->country ?? '',

                'orderItems' => $items

            ];
            
        }

        $gorgiasData->setOrders($orders);
        $gorgiasData->setCarts($carts);

        //order id - items - shipping quotes

        return json_encode($gorgiasData);
    }


}
