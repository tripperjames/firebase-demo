<?php

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

defined('_JEXEC') or die;

HTMLHelper::_('bootstrap.tooltip');

$lang = Factory::getLanguage();
$limit = $lang->getUpperLimitSearchWord();

// Ordering
$this->lists['ordering'] = HTMLHelper::_('select.genericlist', [

    HTMLHelper::_('select.option', 'newest', Text::_('COM_SEARCH_NEWEST_FIRST')),
    HTMLHelper::_('select.option', 'oldest', Text::_('COM_SEARCH_OLDEST_FIRST')),
    HTMLHelper::_('select.option', 'popular', Text::_('COM_SEARCH_MOST_POPULAR')),
    HTMLHelper::_('select.option', 'alpha', Text::_('COM_SEARCH_ALPHABETICAL')),
    HTMLHelper::_('select.option', 'category', Text::_('JCATEGORY')),

], 'ordering', 'class="uk-select uk-form-width-medium"', 'value', 'text', $this->get('state')->get('ordering'));

?>

<form id="searchForm" action="<?= Route::_('index.php?option=com_search') ?>" method="post">

    <div class="uk-panel">

        <fieldset class="uk-margin uk-fieldset">

            <div class="uk-search uk-search-default">
                <input id="search-searchword" class="uk-search-input" type="text" name="searchword" placeholder="<?= Text::_('COM_SEARCH_SEARCH_KEYWORD') ?>" size="30" maxlength="<?= $limit ?>" value="<?= $this->escape($this->origkeyword) ?>">
            </div>

            <button class="uk-button uk-button-primary" name="Search" onclick="this.form.submit()" title="<?= HTMLHelper::tooltipText('COM_SEARCH_SEARCH') ?>"><?= HTMLHelper::tooltipText('COM_SEARCH_SEARCH') ?></button>

            <input type="hidden" name="task" value="search">

        </fieldset>

        <fieldset class="uk-margin uk-fieldset">

            <!-- todo text size -->
            <legend><?= Text::_('COM_SEARCH_FOR') ?></legend>

            <div class="uk-margin">
                <!-- todo margin -->
                <?= $this->lists['searchphrase'] ?>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="ordering"><?= Text::_('COM_SEARCH_ORDERING') ?></label>
                <?= $this->lists['ordering'] ?>
            </div>

        </fieldset>

        <?php if ($this->params->get('search_areas', 1)) : ?>
        <fieldset class="uk-margin uk-fieldset">

            <legend><?= Text::_('COM_SEARCH_SEARCH_ONLY') ?></legend>

            <div class="uk-margin">
                <?php foreach ($this->searchareas['search'] as $val => $txt) :
                    $checked = is_array($this->searchareas['active']) && in_array($val, $this->searchareas['active']) ? 'checked="checked"' : '';
                ?>
                <label for="area-<?= $val ?>">
                    <input type="checkbox" name="areas[]" value="<?= $val ?>" id="area-<?= $val ?>" <?= $checked ?> >
                    <?= Text::_($txt) ?>
                </label><br>
                <?php endforeach ?>
            </div>

        </fieldset>
        <?php endif ?>

    </div>

    <!-- todo leeres div mit margin wird immer gerendert -->
    <div class="uk-flex uk-flex-middle uk-flex-between uk-margin-bottom" uk-margin>

        <?php if (!empty($this->searchword)) : ?>
        <div><?= Text::plural('COM_SEARCH_SEARCH_KEYWORD_N_RESULTS', '<span class="uk-badge uk-badge-info">'.$this->total.'</span>') ?></div>
        <?php endif ?>

        <?php if ($this->total > 0) : ?>
        <div>
            <label for="limit"><?= Text::_('JGLOBAL_DISPLAY_NUM') ?></label>
            <?= $this->pagination->getLimitBox() ?>
        </div>
        <?php endif ?>

    </div>

</form>
