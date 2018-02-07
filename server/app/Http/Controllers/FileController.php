<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models as Model;
use Illuminate\Support\Facades\Cookie;

class FileController extends Controller
{
    public function uploadFile (Request $request) {

        $path = null;
        if ($request->file('logo') && $request->file('logo')->isValid()) {
            $path = $request->file('logo')->store('image/logo', 'public');
        } else if ($request->file('avatar') && $request->file('avatar')->isValid()) {
            $path = $request->file('avatar')->store('image/avatar', 'public');
        } else if ($request->file('picture') && $request->file('picture')->isValid()) {
            $path = $request->file('picture')->store('image/picture', 'public');
        } else if ($request->file('whitePaper') && $request->file('whitePaper')->isValid()) {
            $path = $request->file('whitePaper')->store('pdf/whitePaper', 'public');
        }
        if ($path === null) {
            return $this->error(100);
        }
        $path = '/storage/' . $path;

        return $this->output(['url' => $path]);
    }
}
