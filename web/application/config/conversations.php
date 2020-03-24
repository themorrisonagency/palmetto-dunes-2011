<?php

/**
 * -----------------------------------------------------------------------------
 * Generated 2015-01-29T22:01:13+00:00
 *
 * @item      files.allowed_types
 * @group     conversations
 * @namespace null
 * -----------------------------------------------------------------------------
 */
return array(
    'attachments_enabled' => false,
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
