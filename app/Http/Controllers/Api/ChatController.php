<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    /**
     * Get list of users for chat.
     */
    public function users()
    {
        $users = User::where('id', '!=', auth()->id())
            ->orderBy('name')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    /**
     * Fetch messages between auth user and selected user.
     */
    public function messages(User $user)
    {
        $currentUserId = auth()->id();

        // Mark unread messages from this user as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $currentUserId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = Message::where(function($q) use ($currentUserId, $user) {
                $q->where('sender_id', $currentUserId)
                  ->where('receiver_id', $user->id);
            })
            ->orWhere(function($q) use ($currentUserId, $user) {
                $q->where('sender_id', $user->id)
                  ->where('receiver_id', $currentUserId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'messages' => $messages,
                'chat_with' => $user
            ]
        ]);
    }

    /**
     * Send a new message.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string',
            'file' => 'nullable|file|max:20480',
        ]);

        if (!$request->message && !$request->hasFile('file')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Message or file is required'
            ], 422);
        }

        $data = [
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('chat_files', 'public');
            
            $data['file_path'] = $path;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_type'] = $file->getMimeType();
        }

        $message = Message::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Message sent successfully',
            'data' => $message
        ], 201);
    }

    /**
     * Get unread messages count.
     */
    public function unread()
    {
        $unreadCounts = Message::select('sender_id', DB::raw('count(*) as count'))
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->groupBy('sender_id')
            ->pluck('count', 'sender_id');

        return response()->json([
            'status' => 'success',
            'data' => $unreadCounts
        ]);
    }
}
