<?php

Route::prefix('text')
    ->group(function () {
        /*
        * [GET] /api/v1/text
        */
        Route::get('/', 'TextController@index')
            ->name('v1.text.index');
});
