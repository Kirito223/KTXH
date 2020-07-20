<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DownloadController extends Controller
{
    public function DownloadFile($file)
    {
        return response()->download(public_path('export') . '/' . $file);
    }
}
