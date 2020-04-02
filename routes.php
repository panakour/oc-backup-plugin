<?php

  Route::get('/api/webdav/{directory}', function($directory){
    return Storage::disk('webdav')->files($directory);
  });
