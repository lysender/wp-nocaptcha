<?php

	require_once dirname(__FILE__).'/wp-nocaptcha.class.php';

	// Generate the captcha token
	$wpNoCaptcha = new WP_NoCaptcha_Plugin;
	$wpNoCaptcha->setToken(uniqid(time(), true));
	$token = $wpNoCaptcha->getToken();

	// Write to cookie 
	$wpNoCaptcha->writeCookie();

	echo $token;
	die();