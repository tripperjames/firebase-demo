<?php

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;

defined('_JEXEC') or die;

HTMLHelper::_('behavior.keepalive');

?>

<form action="<?= Route::_('index.php', true, $params->get('usesecure')) ?>" method="post">

    <?php if ($params->get('pretext')) : ?>
    <div class="uk-margin">
        <?= $params->get('pretext') ?>
    </div>
    <?php endif ?>

    <div class="uk-margin">
        <input class="uk-input" type="text" name="username" size="18" placeholder="<?= Text::_('MOD_LOGIN_VALUE_USERNAME') ?>">
    </div>

    <div class="uk-margin">
        <input class="uk-input" type="password" name="password" size="18" placeholder="<?= Text::_('JGLOBAL_PASSWORD') ?>">
    </div>

    <?php if (count($twofactormethods) > 1) : ?>
    <div class="uk-margin">
        <input class="uk-input" type="text" name="secretkey" tabindex="0" size="18" placeholder="<?= Text::_('JGLOBAL_SECRETKEY') ?>" />
    </div>
    <?php endif ?>

    <?php if (PluginHelper::isEnabled('system', 'remember')) : ?>
    <div class="uk-margin">
        <label>
            <input type="checkbox" name="remember" value="yes" checked>
            <?= Text::_('MOD_LOGIN_REMEMBER_ME') ?>
        </label>
    </div>
    <?php endif ?>

    <div class="uk-margin">
        <button class="uk-button uk-button-primary" value="<?= Text::_('JLOGIN') ?>" name="Submit" type="submit"><?= Text::_('JLOGIN') ?></button>
    </div>

    <ul class="uk-list uk-margin-remove-bottom">
        <li><a href="<?= Route::_('index.php?option=com_users&view=reset') ?>"><?= Text::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD') ?></a></li>
        <li><a href="<?= Route::_('index.php?option=com_users&view=remind') ?>"><?= Text::_('MOD_LOGIN_FORGOT_YOUR_USERNAME') ?></a></li>
        <?php $usersConfig = ComponentHelper::getParams('com_users') ?>
        <?php if ($usersConfig->get('allowUserRegistration')) : ?>
        <li><a href="<?= Route::_('index.php?option=com_users&view=registration') ?>"><?= Text::_('MOD_LOGIN_REGISTER') ?></a></li>
        <?php endif ?>
    </ul>

    <?php if ($params->get('posttext')) : ?>
    <div class="uk-margin">
        <?= $params->get('posttext') ?>
    </div>
    <?php endif ?>

    <input type="hidden" name="option" value="com_users">
    <input type="hidden" name="task" value="user.login">
    <input type="hidden" name="return" value="<?= $return ?>">
    <?= HTMLHelper::_('form.token') ?>

</form>
