<?php

namespace App\Http\Controllers;

use App\Events\NewChatMessage;
use App\Models\Message;
use App\Models\Room;
use Illuminate\Http\Request;

class ChatController extends Controller
{
  public function rooms()
  {
    return Room::all();
  }

  public function messages(Room $room)
  {
    return $room->messages;
  }

  public function store(Request $request, Room $room)
  {
    $message = new Message();
    $message->user_id = auth()->id();
    $message->room_id = $room->id;
    $message->message = $request->message;
    $message->save();
    broadcast(new NewChatMessage($message))->toOthers();
    return response(['message' => 'message created succsfull'], 201);
  }
}
