<?php

$el = $this->el('pre');

?>

<?= $el($props, $attrs) ?>
<code class="el-content"><?= $this->e($props['content']) ?></code>
</pre>
