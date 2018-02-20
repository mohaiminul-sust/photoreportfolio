<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JavaScript;
use Auth;
use App\Album as Album;
use App\Photo as Photo;
use App\Http\Resources\AlbumResource;
use App\Http\Resources\PhotoResource;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home')
        ->with('adminUser', Auth::user())
        ->with('albums', Album::all())
        ->with('photos', Photo::all());
    }

    public function timeline() {
        return view('timeline');
    }

    public function getTimelineAlbums() {
        $albums = Album::orderBy('created_at','desc')->get();

        return AlbumResource::collection($albums);
    }

    public function getTimelinePhotos() {
        $photos = Photo::orderBy('created_at','desc')->get();

        return PhotoResource::collection($photos);
    }
}