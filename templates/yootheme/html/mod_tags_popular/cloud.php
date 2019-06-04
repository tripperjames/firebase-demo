<?php

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

defined('_JEXEC') or die;

$minsize = $params->get('minsize', 1);
$maxsize = $params->get('maxsize', 2);

JLoader::register('TagsHelperRoute', JPATH_BASE.'/components/com_tags/helpers/route.php');

?>
<div class="tagspopular tagscloud" uk-margin>
	<?php if (!count($list)) : ?>
	<div class="uk-alert"><?= Text::_('MOD_TAGS_POPULAR_NO_ITEMS_FOUND')?></div>
	<?php else :

		// Find maximum and minimum count
		$mincount = null;
		$maxcount = null;

		foreach ($list as $item) {

			if ($mincount === null or $mincount > $item->count) {
				$mincount = $item->count;
			}

			if ($maxcount === null or $maxcount < $item->count) {
				$maxcount = $item->count;
			}
		}

		$countdiff = $maxcount - $mincount;

		foreach ($list as $item) :

			$fontsize = ($countdiff == 0) ? $minsize : $minsize + (($maxsize - $minsize) / ($countdiff)) * ($item->count - $mincount);

		?>
		<span class="tag">
			<a class="tag-name" style="font-size:<?= $fontsize ?>em" href="<?= Route::_(TagsHelperRoute::getTagRoute($item->tag_id.'-'.$item->alias)) ?>">
				<?= htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8') ?>
				<?php if ($display_count) : ?>
				<span class="uk-badge"><?= $item->count ?></span>
				<?php endif ?>
			</a>
		</span>
		<?php endforeach ?>
	<?php endif ?>
</div>
