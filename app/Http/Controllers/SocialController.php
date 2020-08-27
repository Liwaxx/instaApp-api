<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Posts;
use App\Likes;
use App\Comments;
use App\Helper\responses;
use Illuminate\Support\Facades\DB;

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

    public function edit(Request $req, $id)
    {
        $posts = Posts::find($id);
        $posts->caption = $req->caption;
        $posts->save();

        $response = new responses;
        return $response->responseMessage('Berhasil Edit');
    }

    public function delete($id)
    {
        $posts = Posts::find($id);

        $path = public_path('images/'.$posts->images);

        if (file_exists($path)){
            unlink($path);
        }
        $posts->delete();

        $response = new responses;
        return $response->responseMessage('Berhasil Hapus Post');
    }

    public function like(Request $req)
    {
        $likes = new Likes;
        $likes->id_user = $req->id_user;
        $likes->id_post = $req->id_post;
        $likes->save();

        $response = new responses;
        return $response->responseMessage('Berhasil Like');
    }

    public function unlike($id)
    {
        $id_user = Auth::user()->id_user;
        $likes = Likes::where('id_user',$id_user)->where('id_post',$id);
        $likes->delete();
        $response = new responses;
        return $response->responseMessage('Berhasil Unlike');
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

    public function CommentDash($id)
    {
        $comments = DB::table('comments')
                    ->select('comments.*','users.name')
                    ->join('users','comments.id_user', '=', 'users.id_user')
                    ->where('id_post', $id)
                    ->get();

        $response = new responses;
        return $response->responseData($comments);
    }

    public function feeds()
    {
        $feeds = DB::table('posts')
                    ->join('users','posts.id_user', '=', 'users.id_user')
                    ->leftJoin('likes','posts.id', '=', 'likes.id_post')
                    ->select('posts.*','users.name', DB::raw('(CASE WHEN likes.id_post > 0 THEN 1 ELSE 0 END) AS liked'))
                    ->get();

        // $likeCount = DB::table('likes')
        //                 ->select('id_post',DB::raw('count(*)'))->groupBy('id_post')
        //                 ->get();

        $response = new responses;
        return $response->responseData($feeds);
    }

     public function myPosts($id)
    {
        $myPosts = Posts::where('id_user', '=', $id)->get();

        $response = new responses;
        return $response->responseData($myPosts);
    }
}
