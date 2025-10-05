<?php
if(strstr($_SERVER['HTTP_HOST'], 'localhost')) { //localhost
	$config['fb_app_id'] = 1125956484086498;
	define('CAPTCHASITEKEY', '6LfpyQYTAAAAAOrRI7xR4ztFJ_HxD2v_uzL3ajt1');
	define('CAPTCHASECRETKEY', '6LfpyQYTAAAAAPxpKK2MkA-AKPuFB3Jk1V2m1R3q');
	define('BASE_URL', 'https://localhost/food_fuels/');
} elseif(strstr($_SERVER['HTTP_HOST'], 'foodfuels.intensofy.com')) { //demo
	$config['fb_app_id'] = 1125387760810037;
	define('CAPTCHASITEKEY', '6LfpyQYTAAAAAOrRI7xR4ztFJ_HxD2v_uzL3ajt1');
	define('CAPTCHASECRETKEY', '6LfpyQYTAAAAAPxpKK2MkA-AKPuFB3Jk1V2m1R3q');
	define('BASE_URL', 'https://foodfuels.intensofy.com/');
} elseif(strstr($_SERVER['HTTP_HOST'], 'foodfuelsweightloss.com')) { //demo
	$config['fb_app_id'] = 1125387760810037;
	define('CAPTCHASITEKEY', '6LfpyQYTAAAAAOrRI7xR4ztFJ_HxD2v_uzL3ajt1');
	define('CAPTCHASECRETKEY', '6LfpyQYTAAAAAPxpKK2MkA-AKPuFB3Jk1V2m1R3q');
	define('BASE_URL', 'https://www.foodfuelsweightloss.com/firstlook/');
} elseif(strstr($_SERVER['HTTP_HOST'], 'www.foodfuelsweightloss.com')) { //live
	$config['fb_app_id'] = 1125387760810037;
	define('CAPTCHASITEKEY', '6LfpyQYTAAAAAOrRI7xR4ztFJ_HxD2v_uzL3ajt1');
	define('CAPTCHASECRETKEY', '6LfpyQYTAAAAAPxpKK2MkA-AKPuFB3Jk1V2m1R3q');
	define('BASE_URL', 'https://www.foodfuelsweightloss.com/firstlook/');
} elseif(strstr($_SERVER['HTTP_HOST'], 'http://demo.foodfuels.com')) { //live
	$config['fb_app_id'] = 1125387760810037;
	define('CAPTCHASITEKEY', '6LfpyQYTAAAAAOrRI7xR4ztFJ_HxD2v_uzL3ajt1');
	define('CAPTCHASECRETKEY', '6LfpyQYTAAAAAPxpKK2MkA-AKPuFB3Jk1V2m1R3q');
	define('BASE_URL', 'http://demo.foodfuels.com/');
} else { //prod
	$config['fb_app_id'] = '1125387760810037';
	define('CAPTCHASITEKEY', '6LfpyQYTAAAAAOrRI7xR4ztFJ_HxD2v_uzL3ajt1');
	define('CAPTCHASECRETKEY', '6LfpyQYTAAAAAPxpKK2MkA-AKPuFB3Jk1V2m1R3q');
	define('BASE_URL', 'http://demo.foodfuels.com/');
}

define('USERIMAGELIMIT', 6);
define('ADMINGROUPID', 1);
define('USERGROUPID', 2);
define('CLIENTGROUPID', 3);
define('backofficePAGINATIONLIMIT', 20);

define('INACTIVE_STATUS', 0);
define('ACTIVE_STATUS', 1);
define('IN_ACTIVE_STATUS', 2);

//Demo paypal credentials
//define('PAYPAL_USER', 'tech-facilitator_api1.lacart.com');
//define('PAYPAL_PWD', 'QEDV8FWA7W6WMBD9');
//define('PAYPAL_SIGNATURE', 'A0F.-QOn1ULI4bl1Q43RiH0eUWuzAUFtnMvlcw.wCXiQ4VVuyEML-LWF');

//define('PAYPAL_USER', 'wpp_1316533292_biz_api1.gmail.com');
//define('PAYPAL_PWD', 'ZQZR4LJ2DNM6N8JX');
//define('PAYPAL_SIGNATURE', 'AdqzVxaQ9-FrkQ9-vRe55llJDqAzASgIJrsz.X1lp-sumk3OqxiSDbqo');
define('PAYPAL_APPID', 'APP-80W284485P519543T');

//Live paypal credentials
define('PAYPAL_USER', 'seanpcornelius_api1.gmail.com');
define('PAYPAL_PWD', 'D45H5PYFHGBRWJH7');
define('PAYPAL_SIGNATURE', 'AuLu5UPBUzJmbwiwP4NG6-HZUwIhA86DTLA1A.Nz2OEF35pBk8nNEt9L');

//define('UPLOAD_IMAGE_PATH', WWW_ROOT.'users_uploads/');
//define('UPLOAD_IMAGE_URL', 'users_uploads/');

define('USER_IMAGE_PATH', WWW_ROOT.'media/user/');
define('USER_IMAGE_URL', 'media/user/');

define('DISH_IMAGE_PATH', WWW_ROOT.'media/dish/');
define('DISH_IMAGE_URL', 'media/dish/');

define('MYPIC_IMAGE_PATH', WWW_ROOT.'media/pics/');
define('MYPIC_IMAGE_URL', 'media/pics/');

define('MYVIDOES_PATH', WWW_ROOT.'media/videos/');
define('MYVIDOES_URL', 'media/videos/');

define('LIST_IMAGE_PATH', WWW_ROOT.'media/shopping_list/');
define('LIST_IMAGE_URL', 'media/shopping_list/');

define('SECRET_KEY_APP','asgtfsdaf3242saf325asgfa35saf');

define('DEFAULT_UNIT', 1);

define('WEIGHT_SETT', 'weight_sett');
define('HEIGHT_SETT', 'height_sett');
define('DISTANCE_SETT', 'distance_sett');
define('ENERGY_SETT', 'energy_sett');
define('CURRENCY_SETT', 'currency_sett');
define('DATE_SETT', 'date_sett');
define('TIME_SETT', 'time_sett');

define('FOOD_CATEGORY', 1);


define('FOOD_PICTURE_FOLDER', 'foods-pictures');
define('EXERCISE_PICTURE_FOLDER', 'exercise-pictures');
define('USER_PICTURE_FOLDER', 'users-pictures');


define('BACKEND_FOOD', 1);
define('CUSTOM_FOOD', 2);

define('USER_THUMB', 'thumb_');
define('USER_LARGE', 'Large_');
define('PROFILE_IMAGE', 'Profile_');
define('USER_MEDIUM', 'Medium_');
define('USER_BEFORE', 'Before_');
define('USER_AFTER', 'After_');

$config = array(
	'profile_thumb' => array(
		array(
			'width'=>380,
			'height'=>300,
			'suffix' => USER_MEDIUM
		),
		array(
			'width'=>740,
			'height'=>300,
			'suffix' => USER_LARGE
		),
		array(
			'width'=>80,
			'height'=>80,
			'suffix' => USER_THUMB
		),
		array(
			'width'=>250,
			'height'=>250,
			'suffix' => PROFILE_IMAGE
		),
		array(
			'width'=>500,
			'height'=>500,
			'suffix' => ''
		)
	),
	'crop_before' => array(
		array(
			'width'=>150,
			'height'=>300,
			'suffix' => USER_BEFORE
		)
	),
	'crop_after' => array(
		array(
			'width'=>150,
			'height'=>300,
			'suffix' => USER_AFTER
		)
	),
	'food_added_type_array' => array(
		BACKEND_FOOD => 'Admin',
		CUSTOM_FOOD => 'Custom',
	),
	'categories_type' => array(
		FOOD_CATEGORY => 'Food'
	),
	'UserImage' => array(
		'uploadDir' => 'user_images'
	),
	'DealImage' => array(
		'uploadDir' => 'deals_uploads'
	),
	'gender_arr' => array(
		1 => 'Male',
		2 => 'Female'
	),
	'status_array' => array(
		INACTIVE_STATUS => 'Inactive',
		ACTIVE_STATUS => 'Active',
	),
	'units_array' => array(
	),
	'conversion_array' => array(
		
	),
	//Default value will be saved in database
	'settings' => array(
		
	)
);

define('ReCAPTCHA_PublicKey', '6LcLd-oSAAAAABiZbBMfp6RWnkh5m2qKSUrziAFF');  
define('ReCAPTCHA_PrivateKey', '6LcLd-oSAAAAAF_LnerlaGlIwR6cJjzy3WAee6_X');
