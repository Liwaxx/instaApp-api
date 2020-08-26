<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Posts;
use App\Likes;
use App\Comments;
use App\Helper\responses;

class SocialController extends Controller
{
    public function upload(Request $req, $id)
    {
        $posts = new Posts;
        $posts->id_user = $id;
        $posts->caption = $req->caption;

        $posts->images = $req->image->getClientOriginalName(); 
        $image = $req->file('image');
        $target = '/public/images';
        $image->move(\base_path() . $target, $image->getClientOriginalName());

        $posts->save();

        $response = new responses;
        return $response->responseMessage('Berhasil Upload');
    }

    public function like($id)
    {
        $likes = new Likes;
        $likes->id_user = Auth::user()->id_user;
        $likes->id_post = $id;
        $likes->save();

        $response = new responses;
        return $response->responseMessage('Berhasil Like');
    }

    public function comment(Request $req, $id)
    {
        $comment = new Comments;
        $comment->id_user = Auth::user()->id_user;
        $comment->id_post = $id;
        $comment->comment = $req->comment;
        $comment->save();

        $response = new responses;
        return $response->responseMessage('Berhasil Comment');
    }

    public function feeds()
    {
        $posts = Posts::all();

        $response = new responses;
        return $response->responseData($posts);
    }
}
