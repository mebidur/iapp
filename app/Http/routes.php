<?php
	// \Blade::setRawTags('{{', '}}');
	// \Blade::setContentTags('{{{', '}}}');
	// \Blade::setEscapedContentTags('{{{', '}}}');

	Route::get('/', 'Auth\AuthController@getLogin');

	Route::controllers([
		'auth' => 'Auth\AuthController',
		'password' => 'Auth\PasswordController',
	]);

	Route::group(['middleware' => 'auth'], function()
	{
		Route::controllers([
			// 'home' => 'HomeController',
			'invoice' => 'InvoiceController',
			'receipt' => 'ReceiptController',
		]);
		Route::get('home', 'InvoiceController@getIndex');
	});






