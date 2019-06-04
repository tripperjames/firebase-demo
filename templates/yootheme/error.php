<?php

// no direct access
defined( '_JEXEC' ) or die;

$app = JFactory::getApplication();
$params = $app->getTemplate(true)->params->get('config');

if (!is_array($params)) {
    $params = json_decode($params, true);
}

// prefer child theme's error.php
if (isset($params['child_theme']) && file_exists($file = "{$directory}_{$params['child_theme']}/error.php")) {
    return include $file;
}

$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;

$error = $this->error->getCode();
$message = $this->error->getMessage();

$favicon = isset($params['favicon']) ? $this->baseurl.'/'.$params['favicon'] : $this->baseurl.'/templates/yootheme/vendor/yootheme/theme/platforms/joomla/assets/images/favicon.png';
$touchicon = isset($params['touchicon']) ? $this->baseurl.'/'.$params['touchicon'] : $this->baseurl.'/templates/yootheme/vendor/yootheme/theme/platforms/joomla/assets/images/apple-touch-icon.png';

?>

<!DOCTYPE html>
<html lang="<?= $this->language ?>" dir="<?= $this->direction ?>" vocab="http://schema.org/">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?= $favicon ?>">
    <link rel="apple-touch-icon-precomposed" href="<?= $touchicon ?>">
    <title><?= $error ?> - <?= $message ?></title>
    <?php if ($this->direction == 'ltr') : ?>
        <link rel="stylesheet" href="<?= $this->baseurl ?>/templates/yootheme/css/theme.css" type="text/css" />
    <?php else : ?>
        <link rel="stylesheet" href="<?= $this->baseurl ?>/templates/system/css/theme.rtl.css" type="text/css" />
    <?php endif ?>
    <script src="<?= $this->baseurl ?>/templates/yootheme/vendor/assets/uikit/dist/js/uikit.min.js"></script>
</head>
<body class="">

<div class="uk-section uk-section-default uk-flex uk-flex-center uk-flex-middle uk-text-center" uk-height-viewport>
    <div>
        <h1 class="uk-heading-hero"><?= $error ?></h1>
        <p class="uk-h3"><?= $message ?></p>
        <a class="uk-button uk-button-primary" href="<?= $this->baseurl ?>/index.php"><?= JText::_('JERROR_LAYOUT_HOME_PAGE') ?></a>

        <?php if ($this->debug) : ?>
            <div class="uk-margin-large-top">
                <?php echo $this->renderBacktrace() ?>

                <?php if ($this->error->getPrevious()) : ?>

                    <?php $loop = true ?>

                    <?php $this->setError($this->_error->getPrevious()) ?>

                    <?php while ($loop === true) : ?>
                        <p><strong><?php echo JText::_('JERROR_LAYOUT_PREVIOUS_ERROR') ?></strong></p>
                        <p>
                            <?php echo htmlspecialchars($this->_error->getMessage(), ENT_QUOTES, 'UTF-8') ?>
                            <br/><?php echo htmlspecialchars($this->_error->getFile(), ENT_QUOTES, 'UTF-8') ?>:<?php echo $this->_error->getLine() ?>
                        </p>
                        <?php echo $this->renderBacktrace() ?>
                        <?php $loop = $this->setError($this->_error->getPrevious()) ?>
                    <?php endwhile ?>

                    <?php $this->setError($this->error) ?>

                <?php endif ?>
            </div>
        <?php endif ?>

    </div>
</div>

</body>
</html>
