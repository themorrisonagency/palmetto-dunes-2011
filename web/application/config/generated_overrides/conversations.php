<?php

/**
 * -----------------------------------------------------------------------------
 * Generated 2015-07-09T17:02:11-04:00
 *
 * @item      files.allowed_types
 * @group     conversations
 * @namespace null
 * -----------------------------------------------------------------------------
 */
return array(
    'attachments_enabled' => false,
    'subscription_enabled' => false,
    'files' => array(
        'allowed_types' => '*.jpg;*.gif;*.jpeg;*.png;*.doc;*.docx;*.zip',
        'guest' => array(
            'max_size' => 1,
            'max' => 3
        ),
        'registered' => array(
            'max_size' => 10,
            'max' => 5
        )
    )
);
