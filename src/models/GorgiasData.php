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

use Craft;
use craft\base\Model;

/**
 * Gorgias Data Model
 *
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Punch Buggy
 * @package   Gorgias
 * @since    1.0.0
 */
class GorgiasData extends Model
{
    // Public Properties

     /**
     * The Users ID in Craft
     *
     * @var int
     */
    public $userId;

    /**
     * User First Name
     *
     * @var string
     */
    public $firstName;

    /**
     * User Last Name
     *
     * @var string
     */
    public $lastName;

    /**
     * User Last Updated
     *
     * @var DateTime
     */
    public $dateUpdated;

    /**
     * User Last Login
     *
     * @var DateTime
     */
    public $lastLogin;

    /**
     * User Orders
     *
     * @var array
     */
    public $orders;

    /**
     * User Type
     *
     * @var string
     */
    public $userType;

    /**
    * @return int
    */
    public function getUserId() : int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     *
     * @return self
     */
    public function setUserId($userId) : GorgiasData
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName() : string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return self
     */
    public function setFirstName($firstName) : GorgiasData
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName() : string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return self
     */
    public function setLastName($lastName) : GorgiasData
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateUpdated() : DateTime
    {
        return $this->dateUpdated;
    }

    /**
     * @param DateTime $dateUpdated
     *
     * @return self
     */
    public function setDateUpdated($dateUpdated) : GorgiasData
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getLastLogin() : DateTime
    {
        return $this->lastLogin;
    }

    /**
     * @param DateTime $lastLogin
     *
     * @return self
     */
    public function setLastLogin($lastLogin) : GorgiasData
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * @return array
     */
    public function getOrders() : array
    {
        return $this->orders;
    }

    /**
     * @param array $orders
     *
     * @return self
     */
    public function setOrders(array $orders) : GorgiasData
    {
        $this->orders = $orders;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserType() : string
    {
        return $this->userType;
    }

    /**
     * @param string $userType
     *
     * @return self
     */
    public function setUserType($userType) : GorgiasData
    {
        $this->userType = $userType;

        return $this;
    }
}
