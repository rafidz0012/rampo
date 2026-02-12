<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Display the chat interface.
     */
    public function index()
    {
        // Get all users except current user
        $users = User::where('id', '!=', auth()->id())
                    ->orderBy('name')
                    ->get();
                    
        return view('chat.index', compact('users'));
    }

    /**
     * Fetch messages between auth user and selected user.
     */
    public function fetchMessages(User $user)
    {
        $currentUserId = auth()->id();
        
        // Mark unread messages from this user as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $currentUserId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Get conversation
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
            'messages' => $messages,
            'user' => $user
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
            'file' => 'nullable|file|max:20480', // Max 20MB
        ]);

        if (!$request->message && !$request->hasFile('file')) {
            return response()->json(['status' => 'error', 'message' => 'Message or file is required'], 422);
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
            'message' => $message
        ]);
    }
    
    /**
     * Check for unread messages count generally or per user (for polling sidebar badges if needed)
     */
    public function checkUnread()
    {
        $unreadCounts = Message::select('sender_id', DB::raw('count(*) as count'))
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->groupBy('sender_id')
            ->pluck('count', 'sender_id');
            
        return response()->json($unreadCounts);
    }
}
