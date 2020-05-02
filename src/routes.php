<?php
Route::get('success', function(){
   return 'Congratulation!! Your firebase initialized.' . config('firebase_api_key');
    // return view('livesupport::index');
});