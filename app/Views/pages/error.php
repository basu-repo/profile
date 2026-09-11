<?php
/**
 * @var int $statusCode
 * @var string $heading
 * @var string $note
 */
layout_start($heading);
?>
<h1><?= h($heading) ?></h1>
<p class="entry-empty-note"><?= h($note) ?></p>
<p><a href="/">Back to Home</a></p>
<?php
layout_end();
