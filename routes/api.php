<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



/* 
* Buyer
*/
Route::resource('buyers', 'Buyer\BuyerController',['only'=>['index','show'] ]);
Route::resource('buyers.transactions', 'Buyer\BuyerTransactionController', ['only' => ['index'] ]);
Route::resource('buyers.products', 'Buyer\BuyerProductController', ['only' => ['index'] ]);
Route::resource('buyers.sellers', 'Buyer\BuyerSellerController', ['only' => ['index'] ]);

/* 
* Products
*/
Route::resource('products', 'Product\ProductController',['only'=>['index','show'] ]);
Route::resource('products.transactions', 'Product\ProductTransactionController',['only'=>['index']]);
Route::resource('products.buyers', 'Product\ProductBuyerController',['only'=>['index']]);
Route::resource('products.categories', 'Product\ProductCategoryController',['only'=>['index', 'destroy','update']]);
Route::resource('products.buyers.transactions', 'Product\ProductBuyertransactionController',['only'=>['store']]);

/* 
* Seller
*/
Route::resource('sellers', 'Seller\SellerController',['only'=>['index','show'] ]);
Route::resource('sellers.transactions', 'Seller\SellerTransactionController',['only'=>['index'] ]);
Route::resource('sellers.buyers', 'Seller\SellerBuyerController',['only'=>['index'] ]);
Route::resource('sellers.products', 'Seller\SellerProductController',['except'=>['create', 'show', 'edit'] ]);

/* 
* Categories
*/
Route::resource('categories', 'Category\CategoryController',['except'=>['create','edit'] ]);
Route::resource('categories.products', 'Category\CategoryProductController',['only'=>['index'] ]);
Route::resource('categories.sellers', 'Category\CategorySellerController',['only'=>['index'] ]);
Route::resource('categories.transactions', 'Category\CategoryTransactionController',['only'=>['index'] ]);
Route::resource('categories.buyers', 'Category\CategoryBuyerController',['only'=>['index'] ]);

/* 
* Subcategories
*/
Route::resource('subcategories', 'Subcategory\SubcategoryController',['except'=>['create','edit'] ]);

/* 
* Transactions
*/
Route::resource('transactions', 'Transaction\TransactionController',['only'=>['index','show'] ]);
Route::resource('transactions.sellers', 'Transaction\TransactionSellerController',['only'=>['index'] ]);

/* 
* Users
*/
Route::resource('users', 'User\UserController',['except'=>['create','edit'] ]);
Route::get('users/verify/{token}', 'User\UserController@verify')
  ->name('verify');
Route::get('users/{user}/resend', 'User\UserController@resend')
  ->name('resend');

// Route::post('oauth/token', 'Laravel\Passport\Http\Controllers\AccessTokenController@issueToken'); 