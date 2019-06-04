<?php if ($theme->get('cookie.type') === 'bar') : ?>

    <div class="uk-section uk-section-xsmall uk-section-<?= $theme->get('cookie.bar_style') ?><?= $theme->get('cookie.bar_position') === 'bottom' ? ' uk-position-bottom uk-position-fixed uk-position-z-index' : ' uk-position-relative' ?>">
        <div class="uk-container uk-container-expand uk-text-center">

            <?= JText::_($theme->get('cookie.message'))?>

            <?php if ($theme->get('cookie.button_consent_style') === 'icon') : ?>
                <button type="button" class="js-accept uk-close uk-position-center-right uk-position-medium" data-uk-close data-uk-toggle="target: !.uk-section; animation: true"></button>
            <?php else : ?>
                <button type="button" class="js-accept uk-button uk-button-<?= $theme->get('cookie.button_consent_style') ?> uk-margin-small-left" data-uk-toggle="target: !.uk-section; animation: true"><?= JText::_($theme->get('cookie.button_consent_text'))?></button>
            <?php endif ?>

            <?php if ($theme->get('cookie.mode') === 'consent') : ?>
            <button type="button" class="js-reject uk-button uk-button-<?= $theme->get('cookie.button_reject_style') ?> uk-margin-small-left" data-uk-toggle="target: !.uk-section; animation: true"><?= JText::_($theme->get('cookie.button_reject_text'))?></button>
            <?php endif ?>

        </div>
    </div>

<?php else : ?>

    <div class="uk-notification uk-notification-<?= $theme->get('cookie.notification_position') ?>">
        <div class="uk-notification-message<?= $theme->get('cookie.notification_style') ? " uk-notification-message-{$theme->get('cookie.notification_style')}" : ''?> uk-panel">

            <?= JText::_($theme->get('cookie.message'))?>

            <?php if ($theme->get('cookie.button_consent_style') === 'icon') : ?>
                <button type="button" class="js-accept uk-notification-close uk-close" data-uk-close data-uk-toggle="target: !.uk-notification; animation: uk-animation-fade"></button>
            <?php endif ?>

            <?php if ($theme->get('cookie.button_consent_style') !== 'icon' || $theme->get('cookie.mode') === 'consent') : ?>
            <p class="uk-margin-small-top">

                <?php if ($theme->get('cookie.button_consent_style') !== 'icon') : ?>
                <button type="button" class="js-accept uk-button uk-button-<?= $theme->get('cookie.button_consent_style') ?>" data-uk-toggle="target: !.uk-notification; animation: uk-animation-fade"><?= JText::_($theme->get('cookie.button_consent_text'))?></button>
                <?php endif ?>

                <?php if ($theme->get('cookie.mode') === 'consent') : ?>
                <button type="button" class="js-reject uk-button uk-button-<?= $theme->get('cookie.button_reject_style') ?> uk-margin-small-left" data-uk-toggle="target: !.uk-notification; animation: uk-animation-fade"><?= JText::_($theme->get('cookie.button_reject_text'))?></button>
                <?php endif ?>

            </p>
            <?php endif ?>

        </div>
    </div>

<?php endif ?>