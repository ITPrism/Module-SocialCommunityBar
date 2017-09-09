<?php
/**
 * @package      Social Community
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('Prism.init');
jimport('Socialcommunity.init');

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

$userId = JFactory::getUser()->get('id');
if ($userId) {
    JLoader::register('SocialcommunityBarModuleHelper', JPATH_ROOT . '/modules/mod_socialcommunitybar/helper.php');

    $doc = JFactory::getDocument();

    $doc->addStyleSheet(JUri::root() . 'modules/mod_socialcommunitybar/css/style.css');
    $doc->addScript(JUri::root() . 'modules/mod_socialcommunitybar/js/mod_socialcommunitybar.js');
    $doc->addScriptOptions('mod_socialcommunitybar', ['limit' => $params->get('results_limit', 5)]);

    // Get profile information
    $profile = SocialcommunityBarModuleHelper::fetchProfile($userId);

    // Get currency
    $componentParams   = JComponentHelper::getParams('com_socialcommunity');
    $filesystemHelper  = new Prism\Filesystem\Helper($componentParams);
    $profileMediaFolderUrl = $filesystemHelper->getMediaFolderUri($userId);

    // ### Show data from Virtual Currency component.
    
    $accounts    = array();

    $currencyIds = $params->get('display_accounts');
    $currencyIds = Joomla\Utilities\ArrayHelper::toInteger($currencyIds);
    if (count($currencyIds) > 0) {
        jimport('Virtualcurrency.init');

        $accounts = SocialcommunityBarModuleHelper::fetchAccounts($userId, $currencyIds);

        // Get currency
        $componentParams = JComponentHelper::getParams('com_virtualcurrency');

        $moneyFormatter  = Virtualcurrency\Money\Helper::factory('joomla')->getFormatter();
        $mediaFolderUrl  = JUri::root() . $componentParams->get('media_folder');
    }

    $goodsIds = $params->get('display_goods');
    $goodsIds = Joomla\Utilities\ArrayHelper::toInteger($goodsIds);
    if (count($goodsIds) > 0) {
        jimport('Virtualcurrency.init');

        $commodities = SocialcommunityBarModuleHelper::fetchCommodities($userId, $currencyIds);

        // Get currency
        $componentParams = JComponentHelper::getParams('com_virtualcurrency');
        $mediaFolderUrl  = JUri::root() . $componentParams->get('media_folder');
    }

    require JModuleHelper::getLayoutPath('mod_socialcommunitybar', $params->get('layout', 'default'));
}
