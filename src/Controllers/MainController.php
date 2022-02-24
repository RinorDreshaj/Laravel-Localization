<?php

namespace Rinordreshaj\Localization\Controllers;

use App\Http\Controllers\Controller;

class MainController extends Controller
{
    public function success($data, $status = 200, $headers = [])
    {
        return response()->json($data, $status, $headers);
    }

    public function error($messge = "Something went wrong!", $status = 400, $headers = [])
    {
        return response()->json($messge, $status, $headers);
    }
}
