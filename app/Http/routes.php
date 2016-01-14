<?php

Route::get('/', 'CategoryController@index');

/*
    /node (GET)  Get all root nodes
*/
Route::get('/node', 'CategoryController@index');

/*
    /node/{ID}/ (GET) Get node with specified ID
*/
Route::get('/node/{id}', 'CategoryController@showNode')->where(['id' => '[0-9]*']);

/*
    /node (POST) Store new root node
*/
Route::post('/node', 'CategoryController@createNode');

/*
    /node/{ID} (PUT) - Update existing root node
*/
Route::put('/node/{id}', 'CategoryController@updateNode')->where(['id' => '[0-9]*']);

/*
    /node/{ID} (DELETE) - Delete node and it’s children
*/
Route::delete('/node/{id}', 'CategoryController@destroyNode')->where(['id' => '[0-9]*']);

/*
    /node/{ID}/children (GET) - Get all children of node with specified ID
*/
Route::get('/node{children}', 'CategoryController@showChildren')
        ->where('children', '(/[0-9]+/children)+')->where(['id' => '[0-9]*']);

/*
    /node/{ID}/children/{ID} (GET) - Get child node with specified ID
*/
Route::get('/node/{id}{children}', 'CategoryController@showChild')
        ->where('children', '(/children/[0-9]+)+')->where(['id' => '[0-9]*']);

/*
    /node/{ID}/children (POST) – Add child node to root node with specified ID
*/
Route::post('/node{children}', 'CategoryController@createChild')
        ->where('children', '(/[0-9]+/children)+')->where(['id' => '[0-9]*']);

/*
    /node/{ID}/children/{ID} (PUT) Update child node
*/
Route::put('/node/{id}{children}', 'CategoryController@updateChild')
        ->where('children', '(/children/[0-9]+)+')->where(['id' => '[0-9]*', 'child_id' => '[0-9]*']);

/*
    /node/{ID}/children/{ID} (DELETE) Delete child node
*/
Route::delete('/node/{id}{children}', 'CategoryController@destroyChild')
        ->where('children', '(/children/[0-9]+)+')->where(['id' => '[0-9]*', 'child_id' => '[0-9]*']);
