<?php

	Route::get('/', 'Auth\AuthController@getLogin');

	Route::controllers([
		'auth' => 'Auth\AuthController'
	]);

	Route::group(['middleware' => 'auth'], function()
	{
		Route::controllers([
			'invoice' => 'InvoiceController',
			'receipt' => 'ReceiptController',
			'config'  => 'ConfigController',
		]);
		Route::get('home', 'InvoiceController@getIndex');
	});






