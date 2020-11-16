<?php
require_once("Router/Route.php");
use Router\Route;

Route::get("/user","Controllers\UserController@index");
Route::get("/showuser","Controllers\UserController@show");
Route::post("/upuser","Controllers\UserController@update");
Route::post("/cuser","Controllers\UserController@create");
Route::post("/duser","Controllers\UserController@delete");

Route::get("/book","Controllers\BookController@index");
Route::get("/showbook","Controllers\BookController@show");
Route::post("/upbook","Controllers\BookController@update");
Route::post("/cbook","Controllers\BookController@create");
Route::post("/dbook","Controllers\BookController@delete");