<?php

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

defined('_JEXEC') or die;

JLoader::register('TagsHelperRoute', JPATH_BASE.'/components/com_tags/helpers/route.php');

?>

<?php if (!count($list)) : ?>
<div class="uk-alert"><?= Text::_('MOD_TAGS_POPULAR_NO_ITEMS_FOUND') ?></div>
<?php else : ?>
<ul class="tagspopular">
	<?php foreach ($list as $item) : ?>
	<li>
		<a href="<?= Route::_(TagsHelperRoute::getTagRoute($item->tag_id.'-'.$item->alias)) ?>">
			<?= htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8') ?>
			<?php if ($display_count) : ?>
			<span class="uk-badge"><?= $item->count ?></span>
			<?php endif ?>
		</a>
	</li>
	<?php endforeach ?>
</ul>
<?php endif ?>
