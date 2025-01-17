<?php

Radjax\Route::get("/search/api", ["post"], "Modules\Search\App\Search@api", ["protected" => true, "session_saved" => false]);
 
$access = 'App\Middleware\Before\UserAuth@index'; 
Radjax\Route::get("/team/action", ["post"], "Modules\Team\App\Team@action", ["protected" => true, "before" => $access]);
Radjax\Route::get("/favorite", ["post"], "App\Controllers\FavoriteController@index", ["protected" => true, "before" => $access]);
Radjax\Route::get("/post/profile", ["post"], "App\Controllers\Post\PostController@postProfile", ["protected" => true, "before" => $access]);
Radjax\Route::get("/post/recommend", ["post"], "App\Controllers\Post\AddPostController@recommend", ["protected" => true, "before" => $access]);
Radjax\Route::get("/focus", ["post"], "App\Controllers\SubscriptionController@index", ["protected" => true, "before" => $access]);
Radjax\Route::get("/folder/content/del", ["post"], "App\Controllers\FolderController@delFolderContent", ["protected" => true, "before" => $access]);
Radjax\Route::get("/folder/del", ["post"], "App\Controllers\FolderController@delFolder", ["protected" => true, "before" => $access]);
Radjax\Route::get("/votes", ["post"], "App\Controllers\VotesController@index", ["protected" => true, "before" => $access]);
