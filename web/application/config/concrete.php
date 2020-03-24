<?php

return array
(

	'session'           => array(
	   'name'         => 'C5_Palmettodunes',
	   'handler'      => 'phpnative',
	   'max_lifetime' => 7200,
	   'cookie'       => array(
		   'path'     => '',
		   'lifetime' => 7200,
		   'domain'   => '',
		   'secure'   => false,
		   'httponly' => false
	   )
	),
	'security' => array(
		'session' => array(
			'invalidate_on_ip_mismatch' => false,
		),
	)
);
