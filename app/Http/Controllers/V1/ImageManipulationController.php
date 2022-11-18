<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResizeImageManipulation;
use App\Models\ImageManipulation;
use App\Http\Requests\StoreImageManipulationRequest;
use App\Http\Requests\UpdateImageManipulationRequest;
use App\Http\Resources\V1\ImageResource;
use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;


class ImageManipulationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        return ImageResource::collection(ImageManipulation::where('user_id', $request->user()->id)->paginate());

        // if($validateUser->fails()){
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'validation error',
        //         'errors' => $validateUser->errors()
        //     ], 401);
        // }
        // $user = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password)
        // ]);

        // return response()->json([
        //     'status' => true,
        //     'message' => 'User Created Successfully',
        //     'token' => $user->createToken("API TOKEN")->plainTextToken
        // ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreImageManipulationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreImageManipulationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ImageManipulation  $imageManipulation
     * @return \Illuminate\Http\Response
     */
    public function show(ImageManipulation $imageManipulation, Album $album, Request $request)
    {
        if ($request->user()->id != $album->user_id) {
            return abort(403, ' unauthorized');
        }
        $data  =   ImageManipulation::lazyByIdDesc(500, $column = $request->user()->id)->paginate();
    }

    /**
     * @param  App\Http\Requests\ResizeImageManipulationRequest
     */
    public function resize(ResizeImageManipulation $request)
    {
        $all_post_data  = $request->all();
        /** @param image from form or url*/
        $image  = $all_post_data['image'];
        unset($all_post_data['image']);
        //print_r($image);
        $data =  [
            'type' => ImageManipulation::TYPE_RESIZE,
            'data' => json_encode($all_post_data),
            'user_id' => null,
        ];

        if (isset($all_post_data['album_id'])) {
            //TODO
            $data['album_id']  = $all_post_data['album_id'];
        }
        $folder_name  =  Str::random();
        $dir  = 'image\\' .  $folder_name . '\\'; // image path
        // var_dump(public_path($dir));
        $absolutePath  = public_path($dir);
        try {
            \mkdir($absolutePath, 0775); //code...//if 0755 permission denie error 5 =>user rea/write no execusion
            // File::makeDirectory($absolutePath);
        } catch (\Throwable $th) {
            var_dump($th);
        }

        if ($image instanceof UploadedFile) {
            $data['name']  = $image->getClientOriginalName();
            $filename  = pathinfo($data['name'], PATHINFO_FILENAME);
            $extension  = $image->getClientOriginalExtension();
            $image->move($absolutePath, $data['name']);
            $data['path']  = $dir . $data['name'];
        } else {
            //is url
            $data['name']  = pathinfo($image, PATHINFO_BASENAME);
            $filename = pathinfo($image, PATHINFO_FILENAME);
            $extension  = pathinfo($image, PATHINFO_EXTENSION);
            $originalPath  = $absolutePath . $data['name'];
            $resizedFileName  =  $filename . '_resize.' . $extension;
            try {
                copy($image, $originalPath); //code...
            } catch (\Exception $e) {
                var_dump($e);

                \rmdir(public_path($dir));
            }
        }
        $data['path']  = $dir . $data['name'];

        $w = $all_post_data['width'];
        $h  = $all_post_data['height'];
        list($width, $height, $image)  = $this->getImageWidthAndHeight($w, $h, $originalPath);


        $image->resize($width, $height)->save($absolutePath . $resizedFileName);

        $data['output_path']  = $dir . $resizedFileName;
        $data['album_id']  = 1;
        $data['user_id']  = 1;
        echo $absolutePath . $resizedFileName;
        //  return ($absolutePath . $data['output_path']);
        try {
            $creatImage  = ImageManipulation::create($data);  //code...
            return $creatImage;
        } catch (\Exception $e) {
            if (\file_exists($absolutePath . $resizedFileName)) {
                unlink($absolutePath . $resizedFileName);
            }
            if (file_exists($originalPath)) {
                unlink($originalPath);
            }
            \rmdir($absolutePath);
        }
        return $e;
    }

    private function getImageWidthAndHeight($w, $h, string $path)
    {
        $imageObj  = new ImageManager(['driver' => 'gd']);
        $image  = $imageObj->make($path);
        $originalW  = $image->width();
        $originalH  = $image->height();
        $newW = 0;
        $newH = 0;
        if (\str_ends_with($w, '%')) {
            $ratioW  = (float)str_replace('%', '', $w);
            $ratioH  = $h ? (float)str_replace('%', '', $h) : $ratioW;
            $newW  = $originalW * $ratioW / 100;
            $newH  =  $originalH * $ratioH / 100;
        } else {
            $newW  = (float)$w;
            $newH  = $h ? (float)$h : $originalH * $newW / $originalW;
        }
        // 
        return [$newW, $newH, $image];
    }

    /**
     * @param  App\Http\Requests\ResizeImageManipulationRequest
     */
    public function byAlbum(Album $album, Request $request)
    {
        if ($request->user()->id != $album->user_id) {
            return abort(403, ' unauthorized');
        }
        $where = [
            'album_id' => $request->user()->id
        ];

        return ImageResource::collection(ImageManipulation::where($where)->pagiante());
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateImageManipulationRequest  $request
     * @param  \App\Models\ImageManipulation  $imageManipulation
     * @return \Illuminate\Http\Response
     */
    // public function update(UpdateImageManipulationRequest $request, ImageManipulation $imageManipulation)
    // {

    //     //
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ImageManipulation  $imageManipulation
     * @return \Illuminate\Http\Response
     */
    public function destroy(ImageManipulation $image, Request $request)
    {
        if (gettype($request->post('id')) == 'array') {
            foreach ($request->post('id') as $id) {
                $cond  = ['id' => $id];
                $image::where($cond)->delete();
            }
            return response('', 204);
        }

        $image::where(['id' => $request->post('id')])->delete();  //
        return response('', 204);
    }
}
