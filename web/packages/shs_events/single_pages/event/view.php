<?php
defined('C5_EXECUTE') or die("Access Denied.");
//$this->inc('elements/header.php');
?>

<section>
    <article>
        <section class="row content-area">
            <div class="column small-12">
                <?php Loader::element('system_errors', array('format' => 'block', 'error' => $error, 'success' => $success, 'message' => $message)); ?>
                <?php
                //$event->view();
                //$a = new ShsEvents('SingleEvent');
                //$event->on_page_view();
                $event->render();
                //$a = new Area('Main');
                //$c->addblock($event, 'Main', $eventoccurrence);
                //$c->render($event);
                //$a->display($event);
                //print $innerContent;
                //$event->display($c);
                ?>
            </div>
        </section>
    </article>
</section>

<?php  //$this->inc('elements/footer.php'); ?>
