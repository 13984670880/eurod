<?php
global $image_thumbnail_params;
$image_thumbnail_params = array(
	'path' => '/../images',
	'defaultPath' => '/misc/',
	'subFolders' => array( '.', 'original', 'mini', 'small', 'medium' ),
	
	'formats' => array( 'mini', 'small', 'medium' ),
	'news' => array(
		'mini' => array(
			'size_x'  => 80
			,'size_y' => 60
			,'edit'   => true
		),
		'small' => array(
			'size_x'  => 200
			,'size_y' => 150
			,'edit'   => true
		),
		'medium' => array(
			'size_x'  => 400
			,'size_y' => 300
			,'edit'   => true
		),
	),
	'products' => array(
		'mini' => array(
			'size_x'  => 80
			,'size_y' => 60
			,'edit'   => true
		),
		'small' => array(
			'size_x'  => 200
			,'size_y' => 200
			,'edit'   => true
		),
		'medium' => array(
			'size_x'  => 400
			,'size_y' => 300
			,'edit'   => true
		),
	),
	'categories' => array(
		'mini' => array(
			'size_x'  => 80
			,'size_y' => 60
			,'edit'   => true
		),
		'small' => array(
			'size_x'  => 200
			,'size_y' => 50
			,'edit'   => true
		),
        'cover' => array(
            'size_x'  => 242
            ,'size_y' => 156
            ,'edit'   => true
        ),
		'medium' => array(
			'size_x'  => 400
			,'size_y' => 300
			,'edit'   => true
		),
	),    
	'groups' => array(
		'mini' => array(
			'size_x'  => 80
			,'size_y' => 60
			,'edit'   => true
		),
		'small' => array(
			'size_x'  => 200
			,'size_y' => 50
			,'edit'   => true
		),
        'cover' => array(
            'size_x'  => 242
            ,'size_y' => 156
            ,'edit'   => true
        ),
		'medium' => array(
			'size_x'  => 400
			,'size_y' => 300
			,'edit'   => true
		),
	),    
	'slides' => array(
		'mini' => array(
			'size_x'  => 80
			,'size_y' => 60
			,'edit'   => true
		),
		'small' => array(
			'size_x'  => 200
			,'size_y' => 150
			,'edit'   => true
		),
		'medium' => array(
			'size_x'  => 1349
			,'size_y' => 374
			,'edit'   => true
		),
	),
	'pages' => array(
		'mini' => array(
			'size_x'  => 80
			,'size_y' => 60
			,'edit'   => true
		),
		'small' => array(
			'size_x'  => 200
			,'size_y' => 150
			,'edit'   => true
		),
		'medium' => array(
			'size_x'  => 492
			,'size_y' => 354
			,'edit'   => true
		),
		'background' => array(
			'size_x'  => 600
			,'size_y' => 450
			,'edit'   => true	
		)
	),
	'home' => array(
		'mini' => array(
			'size_x'  => 80
			,'size_y' => 60
			,'edit'   => true
		),
		'small' => array(
			'size_x'  => 492
			,'size_y' => 354
			,'edit'   => true
		),
		'medium' => array(
			'size_x'  => 492
			,'size_y' => 354
			,'edit'   => true
		),
		'background' => array(
			'size_x'  => 600
			,'size_y' => 450
			,'edit'   => true	
		)
	)
);