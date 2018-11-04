<?php

Route::get('items', \BlueRestAPI\Controller\ItemController::class . "@showAll");
Route::get('items/{id}', \BlueRestAPI\Controller\ItemController::class . "@show");
Route::post('items', \BlueRestAPI\Controller\ItemController::class . "@create");
Route::put('items/{id}', \BlueRestAPI\Controller\ItemController::class . "@update");
Route::patch('items/{id}', \BlueRestAPI\Controller\ItemController::class . "@update");
Route::delete('items/{id}', \BlueRestAPI\Controller\ItemController::class . "@destroy");