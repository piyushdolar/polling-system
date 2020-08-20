<?php

use Illuminate\Support\Facades\Auth;
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

// Home route
Route::get('/home', 'HomeController@index')->name('home');

// Question route
Route::post('/create-question', 'QuestionController@create')->name('question.create');
Route::get('/questions', 'QuestionController@show')->name('question.show');
Route::get('/take-a-poll', 'AnswerController@index')->name('answer.index');
Route::post('/submit-answer', 'AnswerController@create')->name('answer.create');
Route::get('/result', 'AnswerController@show')->name('answer.result');
