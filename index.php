<?php
	error_reporting(E_ALL ^ E_DEPRECATED);
	require 'vendor/autoload.php';

	// SESSION START
	if(!isset($_SESSION)){
		session_start();
	}

	// SET DEFAULT TIMEZONE
	date_default_timezone_set('Asia/Seoul');

	/**
	 *	.env 파일에서 환경변수 로드
	 */
	if(!Store::LOAD_CONFIG()){
		header( $_SERVER["SERVER_PROTOCOL"] . ' 500 Internal Server Error');
		die("Load Config file failed");
	}

	/**
	 *	DB Connection
	 */
	Store::OPEN_DB();

	/**
	 *	Routing
	 */
	$router = new \Phroute\Phroute\RouteCollector();

	$router->get('/', function(){
		return "asfd";
	});

	$dispatcher = new \Phroute\Phroute\Dispatcher($router->getData());

	try {
		$response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], strtok($_SERVER["REQUEST_URI"],'?'));
		echo $response;
	} catch(Exception $e) {
		echo "Error!, Something went wrong!";
	}