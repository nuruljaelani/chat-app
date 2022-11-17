<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index()
    {
        $userId = auth()->user()->id;
        $user = User::findOrFail($userId);
        $users = User::whereNot('id', $userId)->get();
        return view('index', compact('user', 'users'));
    }

    public function getUserById($id)
    {
        $user = User::find($id);
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function create(Request $request)
    {
        $userOne = auth()->user()->id;
        $userTwo = $request->friend_id;

        $conversation = $this->findConversation($userOne, $userTwo);

        if (is_null($conversation)) {
            $conver = new Conversation();
            $conver->user_one = $userOne;
            $conver->user_two = $userTwo;
            $conver->save();

            $data = $this->findConversation($conver->user_one, $conver->user_two);
            $user = User::findOrFail($userTwo);
        } else {
            $data = $conversation;
            if ($userOne === $conversation->user_one) {
                $user = User::findOrFail($conversation->user_two);
            } else {
                $user = User::findOrFail($conversation->user_one);
            }
        }


        return response()->json([
            'success' => true,
            'data' => [
                'conversation' => $data,
                'user' => $user
            ]
        ]);
    }

    protected function findConversation($userOne, $userTwo)
    {
        return Conversation::where(function ($query) use ($userOne, $userTwo) {
            $query->where(['user_one' => $userOne, 'user_two' => $userTwo]);
        })->orWhere(function ($query) use ($userOne, $userTwo) {
            $query->where(['user_one' => $userTwo, 'user_two' => $userOne]);
        })->with(['messages' => function($query) {
            $query->orderBy('created_at', 'asc');
        }, 'userTwo:id,name', 'userOne:id,name'])->first();
    }
}
