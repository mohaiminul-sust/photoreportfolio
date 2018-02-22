<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;

class TagController extends Controller
{
    public function create(Request $request) {
        $tag = new Tag();
        $tag->tag = $request->tag;
        $tag->photo_id = $request->photo_id;
        $tag->save();

        return response()->json(['Success, 200']);
    }

    public function destroy($id) {
        $tag = Tag::find($id);
        if ($tag) {
            $tag->delete();
        }

        return response()->json(['Success, 200']);
    }
}