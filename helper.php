<?php
/**
 * @package      Social Community
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

use Virtualcurrency\User\Commodity\Commodity;
use Virtualcurrency\Currency\Currency;
use Prism\Database\Condition\Condition;
use Prism\Database\Condition\Conditions;
use Prism\Database\Request\Request;
use Prism\Database\Request\Fields;
use Prism\Database\Request\Field;
use Prism\Constants;

/**
 * Helper for mod_socialcommunitybar.
 *
 * @package     Socialcommunity
 * @subpackage  mod_socialcommunitybar
 */
class SocialcommunityBarModuleHelper
{
    public static function fetchProfile($userId)
    {
        // Prepare conditions.
        $conditions = new Conditions();
        $conditions
            ->addCondition(new Condition(['column' => 'user_id', 'value' => $userId]))
            ->addCondition(new Condition(['column' => 'active', 'value' => Constants::ACTIVE]));

        $fields = new Fields();
        $fields
            ->addField(new Field(['column' => 'id']))
            ->addField(new Field(['column' => 'name']))
            ->addField(new Field(['column' => 'image_icon']))
            ->addField(new Field(['column' => 'slug']));

        $databaseRequest = new Request();
        $databaseRequest
            ->setFields($fields)
            ->setConditions($conditions);

        // Fetch the results.
        $gateway    = new Socialcommunity\Profile\Gateway\JoomlaGateway(JFactory::getDbo());
        $repository = new Socialcommunity\Profile\Repository(new Socialcommunity\Profile\Mapper($gateway));

        return $repository->fetch($databaseRequest);
    }

    public static function fetchAccounts($userId, $currencyIds)
    {
        // Prepare conditions.
        $conditions = new Conditions();
        $conditions
            ->addCondition(new Condition(['column' => 'user_id', 'value' => $userId]))
            ->addCondition(new Condition(['column' => 'published', 'value' => Constants::PUBLISHED]))
            ->addSpecificCondition('currency_ids', new Condition(['column' => 'currency_id', 'value' => $currencyIds,  'operator'=> 'IN']));

        $databaseRequest = new Request();
        $databaseRequest->setConditions($conditions);

        // Fetch the results.
        $gateway    = new Virtualcurrency\Account\Gateway\JoomlaGateway(JFactory::getDbo());
        $repository = new Virtualcurrency\Account\Repository(new Virtualcurrency\Account\Mapper($gateway));

        return $repository->fetchCollection($databaseRequest);
    }

    public static function fetchCommodities($userId, $goodsIds)
    {
        // Prepare conditions.
        $conditions = new Conditions();
        $conditions
            ->addCondition(new Condition(['column' => 'user_id', 'value' => $userId, 'operator'=> '=', 'table' => 'a']))
            ->addCondition(new Condition(['column' => 'published', 'value' => Prism\Constants::PUBLISHED,  'operator'=> '=', 'table' => 'b']))
            ->addSpecificCondition('commodity_ids', new Condition(['column' => 'commodity_id', 'value' => $goodsIds, 'operator'=> 'IN', 'table' => 'a']));

        $databaseRequest = new Request();
        $databaseRequest->setConditions($conditions);

        // Fetch the results.
        $gateway    = new Virtualcurrency\User\Commodity\Gateway\JoomlaGateway(JFactory::getDbo());
        $repository = new Virtualcurrency\User\Commodity\Repository(new Virtualcurrency\User\Commodity\Mapper($gateway));

        return $repository->fetchCollection($databaseRequest);
    }

    /**
     * Display account information.
     *
     * @param string     $amount
     * @param Currency $currency
     * @param string   $mediaFolder
     *
     * @return string
     */
    public static function account($amount, $currency, $mediaFolder)
    {
        $output = '<div class="sc-vc-account-bar">';
        if (!$currency->getIcon()) {
            $output .= htmlentities($currency->getTitle()) . ': ' . $amount;
        } else {
            $output .= '<img src="' . $mediaFolder . '/' . $currency->getIcon() . '" title="' . htmlentities($currency->getTitle()) . '" class="hasTooltip" data-placement="bottom" /> ' . $amount;
        }
        $output .= '</div>';

        return $output;
    }

    /**
     * Display information about user virtual goods.
     *
     * @param Commodity $commodity
     * @param string                         $mediaFolder
     *
     * @return string
     */
    public static function commodity(Commodity $commodity, $mediaFolder)
    {
        $output = '<div class="sc-vc-commodity-bar">';
        if (!$commodity->getCommodity()->getIcon()) {
            $output .= htmlentities($commodity->getCommodity()->getTitle()) . ': ' . $commodity->getNumber();
        } else {
            $output .= '<img src="' . $mediaFolder . '/' . $commodity->getCommodity()->getIcon() . '" title="' . htmlentities($commodity->getCommodity()->getTitle()) . '" class="hasTooltip" data-placement="bottom" /> ' . $commodity->getNumber();
        }
        $output .= '</div>';

        return $output;
    }
}
