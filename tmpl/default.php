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
?>
<div class="<?php echo $moduleclass_sfx; ?>">
    <?php
    if (count($accounts) > 0) {
        /** @var $account Virtualcurrency\Account\Account */
        foreach ($accounts as $account) {
            $currency = $account->getCurrency();
            $money->setCurrency($currency);
            ?>
            <?php echo SocialcommunityBarModuleHelper::account($account, $money, $mediaFolderUrl); ?>
        <?php }
    }?>

    <?php
    if (count($commodities) > 0) {
        /** @var $commodity Virtualcurrency\User\Commodity */
        foreach ($commodities as $commodity) { ?>
            <?php echo SocialcommunityBarModuleHelper::commodity($commodity, $mediaFolderUrl); ?>
    <?php }
    }?>

    <a href="javascript: void(0);" class="sc-ntfy" id="js-sc-ntfy" role="button">
        <span id="js-sc-ntfy-number" class="badge bgcolor-red sc-ntfy-number" style="display: none;">0</span>
        <span id="js-sc-ntfy-content" class="sc-ntfy-content"></span>
    </a>
</div>