<?php

namespace Concrete\Package\PalmettoDunesBlog\Controller\PageType;

class BlogEntry extends Blog
{

    public function on_start()
    {
        // override blog
        $this->blog = \Page::getByID($this->getPageObject()->getCollectionParentID());
        parent::on_start();
    }

    public function on_before_render()
    {
        $this->set('blog', $this->blog);
    }

    protected function loadBlogListing()
    {
        return false;
    }

    public function getTotalComments()
    {
        $blocks = id(new \Area('Conversation'))->getAreaBlocksArray($this->getPageObject());
        $blocks = array_filter($blocks, function($block) {
            return $block->getBlockTypeHandle() == BLOCK_HANDLE_CONVERSATION;
        });
        if (is_object($blocks[0])) {
            $controller = $blocks[0]->getController();
            if (is_object($controller)) {
                $conversation = $controller->getConversationObject();
                if (is_object($conversation)) {
                    return $conversation->getConversationMessagesTotal();
                }
            }
        }

        return 0;

    }

}