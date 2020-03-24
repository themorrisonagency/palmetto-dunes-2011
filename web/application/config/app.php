<?php

return array(

    "assets" => array(
        'palmetto.blog' => array(
            array('css', 'themes/theme_palmetto/css/inc/blog.css')
        ),
        'palmetto.common' => array(
            array('css', 'themes/theme_palmetto/css/inc/common.css')
        ),
        'palmetto.foundation' => array(
            array('css', 'themes/theme_palmetto/css/inc/foundation.css')
        ),
        'palmetto.layout' => array(
            array('css', 'themes/theme_palmetto/css/inc/layout.css')
        ),
        'palmetto.normalize' => array(
            array('css', 'themes/theme_palmetto/css/inc/normalize.css')
        ),
        'palmetto.press' => array(
            array('css', 'themes/theme_palmetto/css/inc/press.css')
        ),
        'palmetto.print' => array(
            array('css', 'themes/theme_palmetto/css/inc/print.css')
        ),
        'palmetto.reset' => array(
            array('css', 'themes/theme_palmetto/css/inc/reset.css')
        ),
        'palmetto.specials' => array(
            array('css', 'themes/theme_palmetto/css/inc/specials.css')
        )
    ),

    "routes" => array(
        '/ccm/event_stream/get_occurrences' => array('\Application\Block\EventStream\Controller::get_occurrences'),
        '/ccm/event_stream/get_occupied_dates' => array('\Application\Block\EventStream\Controller::get_occupied_dates')
    ),
    'providers'=>array(
		'core_session'=>'\Application\Src\Custom\CustomSessionServiceProvider',
		)

);
