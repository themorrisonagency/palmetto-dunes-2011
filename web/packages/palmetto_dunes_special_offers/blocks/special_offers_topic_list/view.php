<div class="topics">
    <div class="topic topic-all <?= !$active_topic ? 'active' : '' ?>">
        <a href="<?= $view->action('') ?>">All Offers</a>
    </div>

    <?php
    /** @var Node $topic */
    foreach ($filterTopics as $topic) {
        $handle = trim(
            preg_replace(
                '/[-]+/',
                '-',
                preg_replace('/[^a-z]/', '-', strtolower($topic->getTreeNodeDisplayName('plain')))),
            '-');
        if ($active_topic == $topic->getTreeNodeID()) {
            ?>
            <div class="topic topic-<?= $handle ?> active">
                <span><?= $topic->getTreeNodeDisplayName() ?></span>
            </div>
        <?php
        } else {
            ?>
            <div class="topic topic-<?= $handle ?>">
                <a href="<?= $view->controller->getTopicLink($topic) ?>">
                    <?= $topic->getTreeNodeDisplayName() ?>
                </a>
            </div>
        <?php
        }
    } ?>
</div>
