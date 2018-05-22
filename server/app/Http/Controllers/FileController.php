<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models as Model;
use Illuminate\Support\Facades\Cookie;
use App\Utils\Qiniu;

class FileController extends Controller
{
    // 本地文件 storage 上传
    /*public function uploadFile (Request $request) {

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
    }*/

    // 七牛上传
    public function uploadFile (Request $request)
    {
        if ($request->file('logo') && $request->file('logo')->isValid()) {
            $filePath = $request->logo->path();
            $prefix = 'logo';
        } else if ($request->file('avatar') && $request->file('avatar')->isValid()) {
            $filePath = $request->avatar->path();
            $prefix = 'avatar';
        } else if ($request->file('picture') && $request->file('picture')->isValid()) {
            $filePath = $request->picture->path();
            $prefix = 'picture';
        } else if ($request->file('whitePaper') && $request->file('whitePaper')->isValid()) {
            $filePath = $request->whitePaper->path();
            $prefix = 'whitePaper';
        } else if ($request->file('appVersion') && $request->file('appVersion')->isValid()) {
            $filePath = $request->appVersion->path();
            $prefix = 'appVersion';
        } else if ($request->file('saasPacketPic') && $request->file('saasPacketPic')->isValid()) {
            $filePath = $request->saasPacketPic->path();
            $prefix = 'saasPacketPic';
        }
        $result = Qiniu::upload($filePath,$prefix);
        if (!$result['code'] == 0) {
            $this->output($result);
        }
        $url = Qiniu::getUrl($result['key']);
        return $this->output(['url' => $url]);
    }
}
