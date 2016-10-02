<?php
/**
 * @package      Social Community
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_socialcommunitybar.
 *
 * @package     Socialcommunity
 * @subpackage  mod_socialcommunitybar
 */
class SocialcommunityBarModuleHelper
{
    /**
     * Display account information.
     *
     * @param Virtualcurrency\Account\Account $account
     * @param Prism\Money\Money               $money
     * @param string                          $mediaFolder
     *
     * @return string
     */
    public static function account($account, $money, $mediaFolder)
    {
        $output = '<div class="sc-vc-account-bar">';
        if (!$account->getCurrency()->getIcon()) {
            $output .= htmlentities($account->getCurrency()->getTitle()) . ': ' . $money->setAmount($account->getAmount())->format();
        } else {
            $output .= '<img src="' . $mediaFolder . '/' . $account->getCurrency()->getIcon() . '" title="' . htmlentities($account->getCurrency()->getTitle()) . '" class="hasTooltip" data-placement="bottom" /> ' . $money->setAmount($account->getAmount())->format();
        }
        $output .= '</div>';

        return $output;
    }

    /**
     * Display information about user virtual goods.
     *
     * @param Virtualcurrency\User\Commodity $commodity
     * @param string                         $mediaFolder
     *
     * @return string
     */
    public static function commodity($commodity, $mediaFolder)
    {
        $output = '<div class="sc-vc-commodity-bar">';
        if (!$commodity->getIcon()) {
            $output .= htmlentities($commodity->getTitle()) . ': ' . $commodity->getNumber();
        } else {
            $output .= '<img src="' . $mediaFolder . '/' . $commodity->getIcon() . '" title="' . htmlentities($commodity->getTitle()) . '" class="hasTooltip" data-placement="bottom" /> ' . $commodity->getNumber();
        }
        $output .= '</div>';

        return $output;
    }
}
