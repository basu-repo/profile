<?php
/**
 * @var array $grouped Entries keyed by type, in the declared type order.
 * @var bool $isEmpty
 */
layout_start('Research', 'research', site_content_get('research_page_intro'));
?>
<h1><?= h(site_content_get('research_page_heading')) ?></h1>
<?php if (site_content_get('research_page_intro') !== ''): ?>
    <p class="entry-card-abstract"><?= h(site_content_get('research_page_intro')) ?></p>
<?php endif; ?>

<?php if ($isEmpty): ?>
    <p class="entry-empty-note">Nothing published here yet.</p>
<?php else: ?>
    <?php foreach ($grouped as $typeKey => $items): ?>
        <?php if ($items === []) { continue; } ?>
        <section class="entry-group">
            <h2 class="entry-group-heading"><?= h(entry_type_label($typeKey)) ?></h2>
            <?php foreach ($items as $entry): ?>
                <?php
                $metaBits = array_values(array_filter([
                    trim((string)($entry['venue'] ?? '')),
                    (string)($entry['year'] ?? ''),
                ], static fn(string $bit): bool => $bit !== ''));
                $abstract = trim((string)($entry['abstract'] ?? ''));
                ?>
                <a class="entry-card" href="/research/<?= h((string)$entry['slug']) ?>">
                    <?php if ($metaBits !== []): ?>
                        <span class="entry-card-meta"><?= h(implode(' · ', $metaBits)) ?></span>
                    <?php endif; ?>
                    <span class="entry-card-title"><?= h((string)$entry['title']) ?></span>
                    <?php if ($abstract !== ''): ?>
                        <p class="entry-card-abstract"><?= h(excerpt($abstract, 190)) ?></p>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </section>
    <?php endforeach; ?>
<?php endif; ?>
<?php
layout_end();
