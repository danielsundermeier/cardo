<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::middleware(['auth'])->group(function () {

    Route::get('/home', 'HomeController@index')->name('home');
    Route::resource('/course', 'Courses\CourseController');
    Route::resource('/client', 'Partners\ClientController');
    Route::resource('/bookkeeping/invoice', 'Receipts\InvoiceController');
    Route::resource('/bookkeeping/receipt.line', 'Receipts\LineController');
    Route::resource('/item', 'Items\ItemController');
    Route::resource('/partner', 'Partners\PartnerController');
    Route::resource('/staff', 'Partners\StaffController');
    Route::resource('/supplier', 'Partners\SupplierController');
    Route::resource('/unit', 'Items\UnitController');

    Route::get('/bookkeeping/receipt/{receipt}/pdf', 'Receipts\PdfController@show');
    Route::get('/bookkeeping/receipt/{receipt}/download', 'Receipts\PdfController@store');
});
