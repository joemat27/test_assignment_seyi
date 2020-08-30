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

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/users', 'UsersController@index')->name('users');
Route::get('/roles', 'RolesController@index')->name('roles');
Route::post('/user', 'UserController@save')->name('user');
// Statuses
Route::get('/statuses', 'StatusesController@index')->name('statuses');
Route::get('/status' , 'StatusController@index')->name('status');
Route::post('/status', 'StatusController@save')->name('status-save');
Route::put('/status' , 'StatusController@update')->name('status-update');
// Actions
Route::get('/actions', 'ActionsController@index')->name('actions');
Route::get('/action' , 'ActionController@index')->name('action');
Route::post('/action', 'ActionController@save')->name('action-save');
Route::put('/action' , 'ActionController@update')->name('action-update');

Route::get('/clients' , 'ClientsController@index')->name('clients');

Route::post('/deposit', 'DepositController@save')->name('deposit-save');
Route::patch('/deposit', 'DepositController@changeStatus')->name('deposit-change-status');
Route::get('/deposits', 'DepositsController@index')->name('deposits');
Route::get('/deposit-history/{number}', 'DepositHistoryController@index')->name('deposit-history');
Route::get('/deposits-stats', 'DepositsStatsController@index')->name('deposit-stats');

Route::put('/profile' , 'ProfileController@index')->name('profile-update');

Route::patch('/notify' , 'NotifyController@isRead')->name('notify-is-read');

Route::post('/ticket' , 'TicketsController@create')->name('ticket-create');
Route::patch('/ticket' , 'TicketsController@send')->name('ticket-send');
Route::put('/ticket' , 'TicketsController@close')->name('ticket-close');

Route::get('/tickets' , 'TicketsController@all')->name('tickets');

Route::get('/ticket-dialog' , 'TicketsController@dialog')->name('ticket-opened');

Route::prefix('api')->group(function() {
    Route::get('accounts/{id}', function ($id) {
        $account = DB::table('accounts')
            ->whereRaw("id=$id")
            ->get();

        return $account;
    });

    Route::get('accounts/{id}/transactions', function ($id) {
        $account = DB::table('transactions')
            ->whereRaw("`from`=$id OR `to`=$id")
            ->get();

        return $account;
    });

    Route::post('accounts/{id}/transactions', function (Request $request, $id) {
        $to = $request->input('to');
        $amount = $request->input('amount');
        $details = $request->input('details');

        $account = DB::table('accounts')
            ->whereRaw("id=$id")
            ->update(['balance' => DB::raw('balance-' . $amount)]);

        $account = DB::table('accounts')
            ->whereRaw("id=$to")
            ->update(['balance' => DB::raw('balance+' . $amount)]);

        DB::table('transactions')->insert(
            [
                'from' => $id,
                'to' => $to,
                'amount' => $amount,
                'details' => $details
            ]
        );
    });

    Route::get('currencies', function () {
        $account = DB::table('currencies')
            ->get();

        return $account;
    });
});

Route::get('this-is-prone-to-sql-injection', function() {

    $name = "'Seyi' OR 1=1";
    
    return DB::select(
    
    DB::raw("SELECT * FROM users WHERE name = $name"));
    
    });
    
    
    Route::get('safe-from-sql-injection', function() {
    
    $name = "'Seyi' OR 1=1";
    
    return DB::select(
    
    DB::raw("SELECT * FROM users WHERE name = ?", [$name]));
    
    });
