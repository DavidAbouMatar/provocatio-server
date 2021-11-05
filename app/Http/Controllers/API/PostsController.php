<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Post;
use App\Models\UserProfile;
use App\Models\Comment;
use App\Models\Like;

use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Database\Schema\Builder;

class PostsController extends Controller
{
    public function like_post(Request $request){
        $user = JWTAuth::user()->id;
    
        $like = new Like();
        $like -> post_id = $request->post_id;
        $like -> user_id = $user;
        $like -> like = 1;
        $like -> save();
        return response()->json([
            'status' => true,
        
        ], 201);
    }
    
    public function dislike_post(Request $request){
    
        $likeId = $request->likeId;
    
        $like =Like ::find($likeId);
        $like->delete();
     
        return response()->json([
            'status' => true,
        
        ], 201);
    }
    
    public function add_comment(Request $request){
        $user = JWTAuth::user()->id;
    
        $comment = new Comment();
        $comment -> post_id = $request -> post_id;
        $comment -> user_id = $user;
        $comment -> comment = $request -> comment;
        $comment -> save();
        $comment_id  = $comment->id;
        $newcomment = Comment::with('users')->where('id', $comment_id)->get();
        return response()->json([
            'comment' => $newcomment,
            'status' => true,
        
        ], 201);

    }


    public function get_comment($pid){
        
        $post = Post::where('id', $pid);
        // $comments = $post->comments()->with('users')->get();
        $comments = Post::findOrFail($pid) -> comments() -> with('users') -> get();
        return json_encode($comments);

    }
}
