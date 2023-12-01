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
use yii\web\Response;
use craft\services\Gql;


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
        
        //Authenticate using GraphQL
        $gqlService = Craft::$app->getGql();
        $settings = Gorgias::getInstance()->getSettings();
        $token = $gqlService->getTokenById($settings->tokenId);

        if ($token->accessToken !== $authToken) {
            return json_encode(["error" => "Unauthorised Request"]);
        }

        //Get user form email address
        try {
            $client = new Client( [
                'base_uri' => $siteUrl . '/api/',
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => $authToken,
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
                $gorgiasData->setDateUpdated($userBody->data->user->dateUpdated);
            }

            //get the user for last login
            $usersService = new Users();

            $user = $usersService->getUserById($userId);

            $gorgiasData->setLastLogin($user->lastLoginDate);

            //Make this conditional - show order information
            $userOrders = Order::find()->email($customerEmail)->all();

            foreach ($userOrders as $orders) {
                
            }
        }
        else {
            //There is no user - but maybe they are a guest.

            $gorgiasData->setUserType('Guest');

        }

        //order id - items - shipping quotes

        return json_encode($gorgiasData);
    }


}
