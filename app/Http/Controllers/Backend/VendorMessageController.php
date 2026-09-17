<?php

namespace App\Http\Controllers\Backend;

use App\Events\MessageEvent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorMessageController extends Controller
{
  public function index(): View
  {
    $userId = auth()->user()->id;

    $users = Chat::with(['sender', 'receiver'])
      ->where(function ($query) use ($userId) {
        $query->where('sender_id', $userId)
          ->orWhere('receiver_id', $userId);
      })
      ->orderBy('created_at', 'desc')
      ->get();

    $chats = $users->map(function ($chat) use ($userId) {
      return $chat->sender_id === $userId
        ? $chat->receiver
        : $chat->sender;
    })->unique('id')->values();

    return view('vendor.messenger.index', compact('chats'));
  }

  public function sendMessage(Request $request)
  {
    $request->validate([
      'message' => ['required', 'string'],
      'receiver_id' => ['required', 'integer'],
    ]);

    $message = new Chat();
    $message->sender_id = auth()->user()->id;
    $message->receiver_id = $request->receiver_id;
    $message->message = $request->message;
    $message->save();

    broadcast((new MessageEvent($message->message, $message->receiver_id)));

    return response(['status' => 'success', 'message' => 'Tin nhắn đã được gửi thành công!']);
  }

  public function getMessage(string $id)
  {
    $userId = auth()->user()->id;
    $messages = Chat::whereIn('receiver_id', [$userId, $id])
      ->whereIn('sender_id', [$userId, $id])
      ->orderBy('created_at', 'asc')
      ->get();

    Chat::where(['sender_id' => $id, 'receiver_id' => $userId, 'seen' => false])->update(['seen' => true]);

    return response(['status' => 'success', 'messages' => $messages]);
  }
}
