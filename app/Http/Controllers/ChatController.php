<?php

namespace App\Http\Controllers;

use App\Events\SaveChat;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Store Chat
     *
     * @param Request $request
     * @return void
     */

    public function store(Request $request)
    {
        $request->validate([
            'body' => 'required'
        ]);

        $data = [
            'conversation_id' => $request->conversation_id,
            'user_id' => auth()->user()->id,
            'body' => $request->body
        ];

        broadcast(new SaveChat($request->conversation_id, auth()->user()->id, $request->body))->toOthers();
        
        Message::create($data);
        // broadcast(new SendMessage($data['body']));

        return response()->json([
            'success' => true,
            'message' => 'Message success stored'
        ]);
    }
    
    /**
     * Load message
     */

    public function loadMessage($roomId)
    {
        $message = Message::where('room_id', $roomId)->get();
        return response()->json([
            'success' => true,
            'data' => $message
        ]);
    }
}
