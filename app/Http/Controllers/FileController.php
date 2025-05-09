<?php

namespace App\Http\Controllers;

use App\Facades\Auth;
use App\Http\Requests\StoreFileRequest;
use App\Models\File;
use Exception;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function upload(StoreFileRequest $request)
    {
        $path = $request->file('attachment')->store('public/attachments');
        $url = Storage::url($path);
        try {
            File::create([
                'path' => $path,
                'user_id' => Auth::user()->id,
            ]);

            return $url;
        } catch (Exception $e) {
            Storage::delete($path);
            throw new Exception();
        }
    }
}
