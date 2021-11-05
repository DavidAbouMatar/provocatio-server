<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Config;
use Illuminate\Support\Str;



use App\Models\User;
use App\Models\Post;
use App\Models\UserProfile;
use App\Models\Challenge;
use App\Models\Like;
use App\Models\Story;


use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Database\Schema\Builder;
// use JWTAuth;



class UserController extends Controller{
	

	
	function getUserProfile($id){

		$user = JWTAuth::user();
		$profile = User::with('posts')->with('isFollowing')->withCount(['followed','followers'])->where('id', $id)->get();			
		return json_encode($profile);
	}

	// get all posts with their comments to user 
	public function getPosts(){
		
		$feeds = Post::with('user')->where('id', 1)->get();
		$feed = Post::with(['user','user.likes','comments','isAuthLiked'])->withCount(['likes','comments'])->get();		
		return json_encode($feed);
	}
	public function get_user_Posts($id){
		$user = JWTAuth::user();
		$posts = User::with(['posts','posts.comments'])->where('id', $user)->get();		
		return json_encode($posts);
	}
	
	public function get_one_post($uid){
		// $post = Post::find($uid);
		$post = Post::with('comments')->where('id',$uid)->get();
		// $comments = $post->comments()->get();
		return json_encode($post);
	}
	//follow a user by id
	public function follow(Request $request){
		$uid = $request->uid;

		JWTAuth::user()->connections()->attach($uid);
		return response()->json([
			'status' => $uid,
			'message' => 'User profile successfully updated',
		], 200);

	}

	//unFollow a user by id
	public function unFollow(Request $request){
		$uid = $request->uid;

		JWTAuth::user()->connections()->detach($uid);
		return response()->json([
			'status' => true,
			'message' => 'User profile successfully updated',
		], 200);

	}
	//follow a user by id
	public function block(Request $request){
		$uid = $request->uid;

		JWTAuth::user()->block()->attach($uid);
		return response()->json([
			'status' => true,
			'message' => 'User profile successfully updated',
		], 200);

	}

	//unFollow a user by id
	public function unBlock(Request $request){
		$uid = $request->uid;

		JWTAuth::user()->block()->detacht($uid);
		return response()->json([
			'status' => true,
			'message' => 'User profile successfully updated',
		], 200);

	}

	// edit profile. profile picture is included 
	public function edit_profile(Request $request) {

		$file_path="";
		if($request->profile_picture){
			// base64 encoded image
		$image = $request -> profile_picture;  
		//name image
    	$imageName = Str::random(12).'.'.'jpg';
		// decode and store image public/storage/profileImages
		
		Storage::disk('public')->put($imageName, base64_decode($image));
		
		$file_path  = Config::get("APP_URL")  . '/storage/' . $imageName;
		}
		
		$user = JWTAuth::user()->id;

		 $profile = User::where('id', $user)
		 ->update(["bio" => $request -> bio,
			"dob" => $request -> dob,
			"gender" => $request -> gender,
			"profile_picture_path" => $file_path
	]);
	

	
		return response()->json([
			'status' => $file_path,
			'message' => 'User profile successfully updated',
		], 201);
	}	






	// search users by keyword
	function search($keyword){
		$user = JWTAuth::user()->id;

		$search = User::where('id','!=',$user)
					  ->where('first_name','like','%'.$keyword.'%')
					  ->orwhere('last_name','like','%'.$keyword.'%')
					  ->limit(20)
					  ->get()
					  ->toArray();
	
		return json_encode($search);
	}



	// run php artisan storage:link when testing
	public function uploadMedia(Request $request){
		$user = JWTAuth::user()->id;
		$type = $request->type;
		$fileModel = new Post();
		if($type == 0){
			$image = $request -> image;  
		// $image = substr_replace($newImage ,"",-5);
		//name image
    	$imageName = Str::random(12).'.'.'jpg';
		// decode and store image public/storage
		
		Storage::disk('public')->put($imageName, base64_decode($image));
		$fileModel->user_id =$user;
		$fileModel->caption =$request->caption;
		$fileModel->type = 0;
		$fileModel->path =  'http://127.0.0.1:8000' . '/storage/' . $imageName;
		$fileModel->save();

		}else{
			$image = $request -> image;  
			$image = str_replace('data:image/png;base64,', '', $image);
			$image = str_replace(' ', '+', $image);
			// $newimage = substr_replace("data:image/png;base64," ,"",$image);
			// $removeLast = str_replace(' ','+',$newimage);
			//name image
			$imageName = Str::random(12).'.'.'jpg';
			// decode and store image public/storage
			
			Storage::disk('public')->put($imageName, base64_decode($image));
			$fileModel->user_id =$user;
			$fileModel->caption =$request->caption;
			$fileModel->type = 0;
			$fileModel->path = 'http://127.0.0.1:8000'  . '/storage/' . $imageName;
			$fileModel->save();
		}

		
		// base64 encoded image
		

		return response()->json(array(
			"status" => true,
			"sucess" => "sucess"
		), 200);

		}
	


		//has bugs
	function currentUser(){
		$user = JWTAuth::user()->id;
		// $user_data =User::withCount('comments')->get();
		$user_data = User::find($user);			
		return json_encode($user_data);
	}

	//user profile images and videos
	public function get_User_Profile_Media(){
		$user = JWTAuth::user()->id;
		$media = Post::select('*')->where('user_id',$user)->get();
		return json_encode($media);


	}

	//create a challenge
	public function challenge(Request $request){
			$validator = Validator::make($request->all(), [
            'uid' => 'required|integer',
            'discription' => 'required|string|between:2,1000',
			
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                "status" => false,
                "errors" => $validator->errors()
            ), 400);
        }
		$user = JWTAuth::user()->id;
		$challenge = new Challenge();
		$challenge->user_id = $user;
		$challenge->for_user_id = $request->uid;
		$challenge->discription = $request->discription;
		$challenge->is_done = 0;
		$challenge->save();

		return response()->json(array(
			"status" => true,
			"sucess" => "sucess"
		), 200);


	}
	//challenge is done
	public function challenge_done(Request $request){
		$image = $request->image;
		$id = $request->uid;
		$user = JWTAuth::user()->id;
		$imageName = Str::random(12).'.'.'jpg';
		// decode and store image public/storage/profileImages
		Storage::disk('public')->put($imageName, base64_decode($image));
		// 'http://127.0.0.1:8000'
		
		$file_path  = 'http://127.0.0.1:8000'  . '/storage/' . $imageName;

		$challenge=Challenge::where('id', $id,)
		 	 ->update([
				"is_done" => 1,
				
			]);


			$story = new Story;

			$story->user_id = $user;
			$story->source = $file_path;
	
			$story->save();

		return response()->json([
			'status' => true,
			'message' => 'challenge done',
		], 201);


}
public function get_stories(){
	$story = Story::with('user')->get();

	$sto = Story::orderBy('stories.created_at', 'desc')
    ->join('users', 'users.id', '=', 'stories.user_id')->get(['users.first_name AS user','users.profile_picture_path AS avatar', 'stories.source', 'stories.id']);
  
	return json_encode($sto);

}

public function get_challenges(){
	$user = JWTAuth::user()->id;
	$challenges = user::with('challenges')->where('id', $user)->get();
	// $challenge = Challenge::where('for_user_id', $user)->get();
	return json_encode($challenges);
}
public function buy_gifts(Request $request){
	$validator = Validator::make($request->all(), [
		'no_of_gifts' => 'required|integer',
		'gift_name' => 'required|string|min:6',
		'amount' => 'required|integer',
		'type' => 'required|integer',
	]);

	if ($validator->fails()) {
		return response()->json($validator->errors(), 422);
	}

	$user = JWTAuth::user()->id;
	$gift = new Gift();
	$gift -> user_id = $user;
	$gift -> gift_name =$request ->gift_name;
	$gift -> type =$request ->type;
	$gift -> amount =$request ->amount;
	$gift->save();

}

public function gift_user(Request $request){
	
	$validator = Validator::make($request->all(), [
		'no_of_gifts' => 'required|integer',
		'gift_name' => 'required|string|min:6',
		'user_gifted' => 'required|integer',
	]);

	if ($validator->fails()) {
		return response()->json($validator->errors(), 422);
	}
	$user = JWTAuth::user()->id;
	$name= $request->name;
	$user_gifted = $request->user_gifted;
	Gift::where('user_id', $user)->where('gift_name',$name)
    ->update([
      'amount'=> DB::raw('amount-1'), 
    
    ]);

	Gift::where('user_id', $user_gifted)->where('gift_name',$name)
    ->update([
      'amount'=> DB::raw('amount+1'), 
    
    ]);


}

public function get_gifts(){
	$user = JWTAuth::user()->id;
	$gift =Gift::where('user_id', $user)->get();
	return json_encode($gift);

}


}

?>