<?php

use App\Models\Partners\Partner;
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

Route::bind('model', function ($id) {
    $type = app()->request->route('type');

    if (in_array($type, ['client', 'staff', 'supplier'])) {
        return Partner::findOrFail($id);
    }

    return ucfirst($type)::findOrFail($id);
});

Route::post('deploy', 'DeploymentController@store');

Auth::routes([
    'register' => false
]);
Route::middleware(['auth'])->group(function () {

    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::resource('/course', 'Courses\CourseController');
    Route::resource('course.date', 'Courses\DateController');
    Route::resource('course.participant', 'Courses\ParticipantController');
    Route::resource('date.participation', 'Courses\Dates\ParticipationController');
    Route::resource('/client', 'Partners\ClientController');
    Route::resource('/bookkeeping/invoice', 'Receipts\InvoiceController');
    Route::resource('/bookkeeping/receipt.line', 'Receipts\LineController');
    Route::resource('/item', 'Items\ItemController');
    Route::resource('/partner', 'Partners\PartnerController');
    Route::resource('/partner.healthdatas', 'Partners\HealthdataController');
    Route::resource('/staff', 'Partners\StaffController');
    Route::resource('/supplier', 'Partners\SupplierController');
    Route::resource('/unit', 'Items\UnitController');

    Route::get('/bookkeeping/receipt/{receipt}/pdf', 'Receipts\PdfController@show');
    Route::get('/bookkeeping/receipt/{receipt}/download', 'Receipts\PdfController@store');

    Route::get('comment', 'Comments\CommentController@index');
    Route::get('{type}/{model}/comment', 'Comments\CommentController@index');
    Route::post('{type}/{model}/comment', 'Comments\CommentController@store');

    Route::post('/date/{date}/participation/copy', 'Courses\Dates\Participations\CopyController@store');
});
