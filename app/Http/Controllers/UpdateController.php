<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class UpdateController extends Controller
{
    // Update the app
    public function updateApp(Request $request){

        Artisan::call('app:refresh', [
            'user' => 1, '--queue' => 'default'
        ]);

        return Response()->json(['statusCode' => 200, 'message' => 'App has been updated'],200);
    }
}
