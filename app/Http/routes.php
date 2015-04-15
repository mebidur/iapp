<?php

	Route::get('/', 'Auth\AuthController@getLogin');

	Route::controllers([
		'auth' => 'Auth\AuthController',
		'password' => 'Auth\PasswordController',
	]);

	Route::group(['middleware' => 'auth'], function()
	{
		Route::controllers([
			'invoice' => 'InvoiceController',
			'receipt' => 'ReceiptController',
			'config'  => 'ConfigurationController',
		]);
		Route::get('home', 'InvoiceController@getIndex');
	});






