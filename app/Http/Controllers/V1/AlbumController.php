<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Http\Requests\StoreAlbumRequest;
use App\Http\Requests\UpdateAlbumRequest;
use App\Http\Resources\V1\AlbumResource;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Reouserce is link to File in Resource folder 
        // to hide the variable in db; 
        // the real variable of the db is i migation
        // the array show in resouce of album will be return
        //how the data is return is dretermine by the resouce
        return  AlbumResource::collection(Album::where('user_id', $request->user()->id)->paginate()); //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAlbumRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAlbumRequest $request)
    {

        Album::create($request->all()); //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Album $album)
    {
        if ($request->user()->id != $album->user_id) {
            return abort(403, ' unauthorized');
        }

        return new AlbumResource($album);
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAlbumRequest  $request
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAlbumRequest $request, Album $album)
    {
        if ($request->user()->id != $album->user_id) {
            return abort(403, ' unauthorized');
        }
        $album->update($request->all());
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Album $album)
    {
        if ($request->user()->id != $album->user_id) {
            return abort(403, ' unauthorized');
        }
        $album::where('id', $request->id)->delete(); //
    }
}
