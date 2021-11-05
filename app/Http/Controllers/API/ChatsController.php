<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Events\MessageSent;

use App\Models\User;
use App\Models\Message;
use App\Models\Chat;

class ChatsController extends Controller
{


    public function createChat(Request $request)
    {
        $user = JWTAuth::user()->id;
        $uid = $request->uid;

        if ($user > $uid) {
            $chat = Chat::updateOrCreate(
                ['recipient' => $user, 'sender' => $uid],
            );
        } else {
            $chat = Chat::updateOrCreate(
                ['sender' => $uid, 'recipient' => $user],
            );
        }

        return response()->json([
            'status' => true,
            'uid' => $chat->id

        ], 201);
    }


    public function getallchat()
    {
        $user = JWTAuth::user()->id;
        $chat_list = User::with(['chats', 'chat.messages'])->where('id', $user)->get();
        $chat = $chat_list[0]['chat'];
        $chats = $chat_list[0]['chats'];
        $result = json_encode(array_merge(json_decode($chat, true), json_decode($chats, true)));
        
        return $result;
    }

    public function fetchMessages($id)
    {
        //   return Message::with('user')->get();
        return Message::with(['user'])->where('chat_id', $id)->orderBy('created_at','DESC')->get();
    }

    public function sendMessage(Request $request)
    {   $chat_id = $request->uid;
        $user = JWTAuth::user();
        $chat = Chat::find($chat_id);
        $message = $user->messages()->create([
            'message' => $request->input('message'),
            'chat_id' => $chat_id
        ]);

        broadcast(new MessageSent($user, $message, $chat))->toOthers();

        return ['status' => 'Message Sent!'];
    }
}
