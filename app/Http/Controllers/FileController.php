<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    public function store(Request $request)
    {
        //  Store files temporarily in storage/tmp/uploads
        $path = storage_path('tmp/uploads');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');
        $extension = explode(".", trim($file->getClientOriginalName()));
        $name = "";
        
        if ($extension[count($extension) - 1] == "slx")
            $name = "M" . uniqid();
        else if ($extension[count($extension) - 1] == "xcos")
            $name = "S" . uniqid();
        else 
            $name = "O" . uniqid();

        $file->move($path, $name);

        return response()->json([
            'name' => $name
        ]);
    }
}
