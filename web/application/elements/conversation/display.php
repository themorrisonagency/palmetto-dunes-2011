<? defined('C5_EXECUTE') or die("Access Denied."); ?>

<?
if (!is_array($messages)) {
	$messages = array();
}
$u = new User();
$ui = UserInfo::getByID($u->getUserID());
$page = Page::getByID($cID);
$editor = \Concrete\Core\Conversation\Editor\Editor::getActive();
$editor->setConversationObject($args['conversation']);
$val = Loader::helper('validation/token');
$form = Loader::helper('form');
?>

<? if ($displayForm && ($displayPostingForm != 'bottom')) { ?>

<? /* palmetto dunes change h4 to h3 */ ?>
<h3><?=$addMessageLabel?></h3>

	<? if ($enablePosting == Conversation::POSTING_ENABLED) { ?>
		<div class="ccm-conversation-add-new-message" rel="main-reply-form">
			<form method="post" class="main-reply-form">
			<? Loader::element('conversation/message/author');?>
			<div class="ccm-conversation-message-form">
				<div class="ccm-conversation-errors alert alert-danger"></div>
				<label for="cnvMessageBody"><?=t('Your Comment');?>:</label>
				<? $editor->outputConversationEditorAddMessageForm(); ?>
				<?php echo $form->hidden('blockAreaHandle', $blockAreaHandle) ?>
				<?php echo $form->hidden('cID', $cID) ?>
				<?php echo $form->hidden('bID', $bID) ?>
				<div class="submitRow">
					<button type="button" data-post-parent-id="0" data-submit="conversation-message" class="submit orange-btn"><?=t('Submit Comment')?></button>
                <?php if ($attachmentsEnabled) { ?>
				    <button type="button" class="pull-right btn btn-default ccm-conversation-attachment-toggle" href="#" title="<?php echo t('Attach Files'); ?>"><i class="fa fa-image"></i></button>
			    <?php } ?>
			    </div>
            </div>
			</form>
            <?php if($attachmentsEnabled) { ?>
			<div class="ccm-conversation-attachment-container">
				<form action="<?php echo Loader::helper('concrete/urls')->getToolsURL('conversations/add_file');?>" class="dropzone" id="file-upload">
					<div class="ccm-conversation-errors alert alert-danger"></div>
					<?php $val->output('add_conversations_file'); ?>
					<?php echo $form->hidden('blockAreaHandle', $blockAreaHandle) ?>
					<?php echo $form->hidden('cID', $cID) ?>
					<?php echo $form->hidden('bID', $bID) ?>
				</form>
			</div>
            <?php } ?>
		</div>

		<div class="ccm-conversation-add-reply">
			<form method="post" class="aux-reply-form">
			<? Loader::element('conversation/message/author');?>
			<div class="ccm-conversation-message-form">
				<div class="ccm-conversation-errors alert alert-danger"></div>
				<? $editor->outputConversationEditorReplyMessageForm(); ?>
				<?php echo $form->hidden('blockAreaHandle', $blockAreaHandle) ?>
				<?php echo $form->hidden('cID', $cID) ?>
				<?php echo $form->hidden('bID', $bID) ?>	
				<button type="btn btn-primary" data-submit="conversation-message" class="pull-right btn btn-primary"><?=t('Reply')?> </button>
                <?php if ($attachmentsEnabled) { ?>
                    <button type="button" class="pull-right btn btn-default ccm-conversation-attachment-toggle" href="#" title="<?php echo t('Attach Files'); ?>"><i class="fa fa-image"></i></button>
			    <?php } ?>
            </div>
			</form>
            <?php if($attachmentsEnabled) { ?>
			<div class="ccm-conversation-attachment-container">
				<form action="<?php echo Loader::helper('concrete/urls')->getToolsURL('conversations/add_file');?>" class="dropzone" id="file-upload-reply">
					<div class="ccm-conversation-errors alert alert-danger"></div>
					<?php $val->output('add_conversations_file'); ?>
					<?php echo $form->hidden('blockAreaHandle', $blockAreaHandle) ?>
					<?php echo $form->hidden('cID', $cID) ?>
					<?php echo $form->hidden('bID', $bID) ?>
				</form>
			</div>
            <?php } ?>
		</div>
	<? } else { ?>
        <? switch($enablePosting) {
            case Conversation::POSTING_DISABLED_MANUALLY:
                print '<p>' . t('Adding new posts is disabled for this conversation.') . '</p>';
                break;
            case Conversation::POSTING_DISABLED_PERMISSIONS:
                print '<p>';
                print ' ';
                if (!$u->isRegistered()) {
                    print t('You must <a href="%s">sign in</a> to post to this conversation.', URL::to('/login'));
                } else {
                    print t('You do not have permission to post this to conversation.');
                }
                print '</p>';
                break;
        } ?>
	<? } ?>

<? } ?>

<div class="ccm-conversation-message-list ccm-conversation-messages-<?=$displayMode?>">

	<div class="ccm-conversation-delete-message" data-dialog-title="<?=t('Delete Message')?>" data-cancel-button-title="<?=t('Cancel')?>" data-confirm-button-title="<?=t('Delete Message')?>">
		<?=t('Remove this message? Replies to it will not be removed.')?>
	</div>
	<div class="ccm-conversation-delete-attachment" data-dialog-title="<?=t('Delete Attachment')?>" data-cancel-button-title="<?=t('Cancel')?>" data-confirm-button-title="<?=t('Delete Attachment')?>">
		<?=t('Remove this attachment?')?>
	</div>
	<div class="ccm-conversation-message-permalink" data-dialog-title="<?=t('Link')?>" data-cancel-button-title="<?=t('Close')?>">
	</div>

	<div class="ccm-conversation-messages-header">
		<? if ($enableOrdering) { ?>
		<select class="form-control pull-right ccm-sort-conversations" data-sort="conversation-message-list">
			<option value="date_desc" <? if ($orderBy == 'date_desc') { ?>selected="selected"<? } ?>><?=t('Recent')?></option>
			<option value="date_asc" <? if ($orderBy == 'date_asc') { ?>selected="selected"<? } ?>><?=t('Oldest')?></option>
			<option value="rating" <? if ($orderBy == 'rating') { ?>selected="selected"<? } ?>><?=t('Popular')?></option>
		</select>
		<? } ?>

		<? Loader::element('conversation/count_header', array('conversation' => $conversation))?>
	</div>


	<div class="ccm-conversation-no-messages well well-small" <? if (count($messages) > 0) { ?>style="display: none" <? } ?>><?=t('No messages in this conversation.')?></div>

	<div class="ccm-conversation-messages">

	<? foreach($messages as $m) {
		Loader::element('conversation/message', array('cID' => $cID, 'message' => $m, 'bID' => $bID, 'page' => $page, 'blockAreaHandle' => $blockAreaHandle, 'enablePosting' => $enablePosting, 'displayMode' => $displayMode, 'enableCommentRating' => $enableCommentRating, 'dateFormat' => $dateFormat, 'customDateFormat' => $customDateFormat));
	} ?>

	</div>

	<? if ($totalPages > $currentPage) { ?>
	<div class="ccm-conversation-load-more-messages">
		<button class="btn btn-large" type="button" data-load-page="conversation-message-list" data-total-pages="<?=$totalPages?>" data-next-page="<?=$currentPage + 1?>" ><?=t('Load More')?></button>
	</div>
	<? } ?>


</div>

</div>

<? if ($displayForm && ($displayPostingForm == 'bottom')) { ?>

<h4><?=$addMessageLabel?></h4>

    <? if ($enablePosting == Conversation::POSTING_ENABLED) { ?>
		<div class="ccm-conversation-add-new-message" rel="main-reply-form">
			<form method="post" class="main-reply-form">
			<? Loader::element('conversation/message/author');?>
			<div class="ccm-conversation-message-form">
				<div class="ccm-conversation-errors alert alert-danger"></div>
				<? $editor->outputConversationEditorAddMessageForm(); ?>
				<button type="button" data-post-parent-id="0" data-submit="conversation-message" class="pull-right btn btn-primary btn-small"><?=t('Reply')?> </button>
				<?php if ($attachmentsEnabled) { ?>
                    <button type="button" class="pull-right btn btn-default ccm-conversation-attachment-toggle" title="<?php echo t('Attach Files'); ?>"><i class="fa fa-image"></i></button>
                <?php } ?>
				<?php echo $form->hidden('blockAreaHandle', $blockAreaHandle) ?>
				<?php echo $form->hidden('cID', $cID) ?>
				<?php echo $form->hidden('bID', $bID) ?>	
			</div>
			</form>
            <?php if($attachmentsEnabled) { ?>
			<div class="ccm-conversation-attachment-container">
				<form action="<?php echo Loader::helper('concrete/urls')->getToolsURL('conversations/add_file');?>" class="dropzone" id="file-upload">
					<div class="ccm-conversation-errors alert alert-danger"></div>
					<?php $val->output('add_conversations_file'); ?>
					<?php echo $form->hidden('blockAreaHandle', $blockAreaHandle) ?>
					<?php echo $form->hidden('cID', $cID) ?>
					<?php echo $form->hidden('bID', $bID) ?>		
				</form>
			</div>
            <?php } ?>
		</div>

		<div class="ccm-conversation-add-reply" >
			<form method="post" class="aux-reply-form">
			<? Loader::element('conversation/message/author');?>
			<div class="ccm-conversation-message-form">
				<div class="ccm-conversation-errors alert alert-danger"></div>
				<? $editor->outputConversationEditorReplyMessageForm(); ?>
				<button type="button" data-submit="conversation-message" class="pull-right btn btn-primary btn-small"><?=t('Reply')?></button>
                <?php if ($attachmentsEnabled) { ?>
				    <button type="button" class="pull-right btn btn-default ccm-conversation-attachment-toggle" title="<?php echo t('Attach Files'); ?>"><i class="fa fa-image"></i></button>
                <?php } ?>
				<?php echo $form->hidden('blockAreaHandle', $blockAreaHandle) ?>
				<?php echo $form->hidden('cID', $cID) ?>
				<?php echo $form->hidden('bID', $bID) ?>	
			</div>
			</form>
            <?php if ($attachmentsEnabled) { ?>
			<div class="ccm-conversation-attachment-container">
				<form action="<?php echo Loader::helper('concrete/urls')->getToolsURL('conversations/add_file');?>" class="dropzone" id="file-upload-reply">
					<div class="ccm-conversation-errors alert alert-danger"></div>
					<?php $val->output('add_conversations_file'); ?>
					<?php echo $form->hidden('blockAreaHandle', $blockAreaHandle) ?>
					<?php echo $form->hidden('cID', $cID) ?>
					<?php echo $form->hidden('bID', $bID) ?>		
				</form>
			</div>
            <?php } ?>
		</div>
	<? } else { ?>
        <? switch($enablePosting) {
            case Conversation::POSTING_DISABLED_MANUALLY:
                print '<p>' . t('Adding new posts is disabled for this conversation.') . '</p>';
                break;
            case Conversation::POSTING_DISABLED_PERMISSIONS:
                print '<p>';
                print ' ';
                if (!$u->isRegistered()) {
                    print t('You must <a href="%s">sign in</a> to post to this conversation.', URL::to('/login'));
                } else {
                    print t('You do not have permission to post this to conversation.');
                }
                print '</p>';
                break;
        } ?>
	<? } ?>

<? } ?>
