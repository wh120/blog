<?php

namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use File;
use  Response;
/**
 * Class AwardsController
 * @package App\Http\Controllers\API
 */

class APIController extends AppBaseController
{
    public function viewphoto(Request $r,$path)
    {
        //return $path;
        $fullpath = storage_path('app/' . $path);

        //return $fullpath;
        if (!File::exists($fullpath)) {
            return 'not found';
            abort(404);
        }

        $file = File::get($fullpath);
        $type = File::mimeType($fullpath);

        //  return $type;
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        if($r->w != null || $r->h != null)
        {
            $img = Image::make($file)->resize($r->w, $r->h, function($constraint)
            {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            return $img->response($type);
        }
        
        return $response;
    }
   
}
