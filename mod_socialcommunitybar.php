<?php
/**
 * @package      Social Community
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined("_JEXEC") or die;

jimport('Prism.init');
jimport('Socialcommunity.init');

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

JLoader::register('SocialcommunityBarModuleHelper', JPATH_ROOT . '/modules/mod_socialcommunitybar/helper.php');

$userId = JFactory::getUser()->get('id');

if ($userId) {
    $doc = JFactory::getDocument();

    $doc->addStyleSheet(JUri::root() . 'modules/mod_socialcommunitybar/css/style.css');
    $doc->addScript(JUri::root() . 'modules/mod_socialcommunitybar/js/jquery.socialcommunitybar.js');
    $js = '
        jQuery(document).ready(function() {
            jQuery("#js-sc-ntfy").SocialCommunityBar({
                resultsLimit: ' . $params->get('results_limit', 5) . '
            });
        });
    ';
    $doc->addScriptDeclaration($js);

    $accounts    = null;

    $currencyIds = $params->get('display_accounts');
    $currencyIds = Joomla\Utilities\ArrayHelper::toInteger($currencyIds);
    if (count($currencyIds) > 0) {
        jimport('Virtualcurrency.init');

        $options = array(
            'user_id' => $userId,
            'currency_ids' => $currencyIds,
            'state' => Prism\Constants::PUBLISHED
        );

        $accounts = new Virtualcurrency\Account\Accounts(JFactory::getDbo());
        $accounts->load($options);

        $accounts = $accounts->getAccounts();

        // Get currency
        $componentParams = JComponentHelper::getParams('com_virtualcurrency');

        $moneyFormatter  = VirtualcurrencyHelper::getMoneyFormatter();
        $money           = new Prism\Money\Money($moneyFormatter);

        $mediaFolderUrl  = JUri::root() . $componentParams->get('media_folder');
    }

    $goodsIds = $params->get('display_goods');
    $goodsIds = Joomla\Utilities\ArrayHelper::toInteger($goodsIds);
    if (count($goodsIds) > 0) {
        jimport('Virtualcurrency.init');

        $options = array(
            'user_id' => $userId,
            'commodity_id' => $goodsIds,
            'state' => Prism\Constants::PUBLISHED
        );

        $commodities = new Virtualcurrency\User\Commodities(JFactory::getDbo());
        $commodities->load($options);
        $commodities = $commodities->getCommodities();

        // Get currency
        $componentParams = JComponentHelper::getParams('com_virtualcurrency');
        $mediaFolderUrl  = JUri::root() . $componentParams->get('media_folder');
    }

    require JModuleHelper::getLayoutPath('mod_socialcommunitybar', $params->get('layout', 'default'));
}
