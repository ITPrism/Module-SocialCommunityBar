<?php
/**
 * @package      SocialCommunity
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */
 
// no direct access
defined('_JEXEC') or die;

/**
 * @var $profile Socialcommunity\Profile\Profile
 * @var $profileMediaFolderUrl string
 * @var $accounts array
 * @var $commodities array
 * @var $account Virtualcurrency\Account\Account
 * @var $commodity Virtualcurrency\User\Commodity\Commodity
 * @var $moneyFormatter \Prism\Money\Formatter
 * @var string $mediaFolderUrl
 */
?>
<div class="<?php echo $moduleclass_sfx; ?>">
    <div class="row">
        <div class="col-md-4 sc-mod-bar-home">
            <a href="<?php echo JRoute::_(SocialcommunityHelperRoute::getProfileRoute($profile->getSlug())); ?>">
                <img src="<?php echo $profileMediaFolderUrl.'/'.$profile->getImageIcon(); ?>" class="rounded" />
                <?php echo htmlentities($profile->getName()); ?>
            </a>
        </div>
        <div class="col-md-8">
            <div class="pull-right">
                <?php
                if (count($accounts) > 0) {
                    foreach ($accounts as $account) {
                        $amount          = $account->getAmount();
                        $virtualCurrency = $account->getCurrency();

                        $currency  = new Prism\Money\Currency;
                        $currency->bind($virtualCurrency->getProperties());

                        $money = new Prism\Money\Money($amount, $currency);

                        $amount = $moneyFormatter->format($money);

                        echo SocialcommunityBarModuleHelper::account($amount, $virtualCurrency, $mediaFolderUrl);
                    }
                }?>

                <?php
                if (count($commodities) > 0) {
                    foreach ($commodities as $commodity) {
                        echo SocialcommunityBarModuleHelper::commodity($commodity, $mediaFolderUrl);
                    }
                }?>

                <a href="javascript: void(0);" class="sc-ntfy" id="js-sc-ntfy" role="button">
                    <span id="js-sc-ntfy-number" class="badge bgcolor-red sc-ntfy-number" style="display: none;">0</span>
                    <span id="js-sc-ntfy-content" class="sc-ntfy-content"></span>
                </a>
            </div>
        </div>
    </div>
</div>