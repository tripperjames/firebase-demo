<?php

use Joomla\CMS\Access\Access;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\Registry\Registry;

defined('JPATH_BASE') or die;

JLoader::register('TagsHelperRoute', JPATH_BASE.'/components/com_tags/helpers/route.php');

?>
<?php if (!empty($displayData)) : ?>
	<?php foreach ($displayData as $i => $tag) : ?>
		<?php if (in_array($tag->access, Access::getAuthorisedViewLevels(Factory::getUser()->get('id')))) : ?>
            <?php $seperator = $i++ < count($displayData) - 1 ? ',' : '' ?>
			<?php $tagParams = new Registry($tag->params) ?>
			<?php $tagClass = trim(str_replace(['label-info', 'label'], '', $tagParams->get('tag_link_class', ''))) ?>
			<a href="<?= Route::_(TagsHelperRoute::getTagRoute($tag->tag_id.'-'.$tag->alias)) ?>" class="<?= $tagClass ?>" property="keywords"><?= $this->escape($tag->title) ?></a><?= $seperator ?>
		<?php endif ?>
	<?php endforeach ?>
<?php endif ?>
