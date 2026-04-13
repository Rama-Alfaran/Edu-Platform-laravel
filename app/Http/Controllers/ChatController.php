<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Teacher;
use App\Models\Student;

class ChatController extends Controller
{
    // Show chat list for both teachers and students
    public function index()
    {
        if (Auth::guard('teacher')->check()) {
            return $this->teacherChatList();
        } elseif (Auth::guard('student')->check()) {
            return $this->studentChatList();
        }
        
        return redirect('/login');
    }

    // Teacher chat list
    private function teacherChatList()
    {
        $teacher = Auth::guard('teacher')->user();
        
        // Get all conversations for this teacher
        $conversations = Conversation::where('teacher_id', $teacher->id)
            ->with(['student', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();
        
        // Count unread messages for each conversation
        foreach ($conversations as $conversation) {
            $conversation->unread_count = $conversation->unreadMessagesCount('teacher', $teacher->id);
        }
        
        // Get all students for starting new chats
        $students = Student::orderBy('first_name')->get();
        
        return view('chat', [
            'user' => $teacher,
            'role' => 'Teacher',
            'conversations' => $conversations,
            'availableUsers' => $students,
            'userType' => 'teacher'
        ]);
    }

    // Student chat list
    private function studentChatList()
    {
        $student = Auth::guard('student')->user();
        
        // Get all conversations for this student
        $conversations = Conversation::where('student_id', $student->id)
            ->with(['teacher', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();
        
        // Count unread messages for each conversation
        foreach ($conversations as $conversation) {
            $conversation->unread_count = $conversation->unreadMessagesCount('student', $student->id);
        }
        
        // Get all teachers for starting new chats
        $teachers = Teacher::orderBy('first_name')->get();
        
        return view('chat', [
            'user' => $student,
            'role' => 'Student',
            'conversations' => $conversations,
            'availableUsers' => $teachers,
            'userType' => 'student'
        ]);
    }

    // Show specific conversation
    public function show($conversationId)
    {
        $conversation = null;
        
        if (Auth::guard('teacher')->check()) {
            $teacher = Auth::guard('teacher')->user();
            $conversation = Conversation::where('id', $conversationId)
                ->where('teacher_id', $teacher->id)
                ->with(['student', 'messages'])
                ->firstOrFail();
            
            // Mark messages as read
            $conversation->messages()
                ->where('sender_type', 'student')
                ->where('is_read', false)
                ->update(['is_read' => true]);
                
            $userType = 'teacher';
            $userId = $teacher->id;
            
        } elseif (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();
            $conversation = Conversation::where('id', $conversationId)
                ->where('student_id', $student->id)
                ->with(['teacher', 'messages'])
                ->firstOrFail();
            
            // Mark messages as read
            $conversation->messages()
                ->where('sender_type', 'teacher')
                ->where('is_read', false)
                ->update(['is_read' => true]);
                
            $userType = 'student';
            $userId = $student->id;
        }
        
        return view('chat-conversation', [
            'conversation' => $conversation,
            'userType' => $userType,
            'userId' => $userId
        ]);
    }

    // Start new conversation
    public function startConversation(Request $request)
    {
        if (Auth::guard('teacher')->check()) {
            $teacher = Auth::guard('teacher')->user();
            $studentId = $request->user_id;
            
            $conversation = Conversation::firstOrCreate([
                'teacher_id' => $teacher->id,
                'student_id' => $studentId
            ]);
            
        } elseif (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();
            $teacherId = $request->user_id;
            
            $conversation = Conversation::firstOrCreate([
                'student_id' => $student->id,
                'teacher_id' => $teacherId
            ]);
        }
        
        return redirect('/chat/' . $conversation->id);
    }

    // Send message
    public function sendMessage(Request $request, $conversationId)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);
        
        if (Auth::guard('teacher')->check()) {
            $teacher = Auth::guard('teacher')->user();
            $conversation = Conversation::where('id', $conversationId)
                ->where('teacher_id', $teacher->id)
                ->firstOrFail();
            
            $senderType = 'teacher';
            $senderId = $teacher->id;
            
        } elseif (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();
            $conversation = Conversation::where('id', $conversationId)
                ->where('student_id', $student->id)
                ->firstOrFail();
            
            $senderType = 'student';
            $senderId = $student->id;
        }
        
        // Create message
        Message::create([
            'conversation_id' => $conversation->id,
            'sender_type' => $senderType,
            'sender_id' => $senderId,
            'message' => $request->message,
            'is_read' => false
        ]);
        
        // Update conversation last message time
        $conversation->update([
            'last_message_at' => now()
        ]);
        
        return redirect('/chat/' . $conversationId);
    }

    // Get new messages (for AJAX polling)
    public function getNewMessages($conversationId, $lastMessageId)
    {
        $conversation = Conversation::findOrFail($conversationId);
        
        $messages = Message::where('conversation_id', $conversationId)
            ->where('id', '>', $lastMessageId)
            ->with('sender')
            ->get();
        
        return response()->json([
            'messages' => $messages,
            'count' => $messages->count()
        ]);
    }

    // Get unread count for dashboard
    public function getUnreadCount()
    {
        $count = 0;
        
        if (Auth::guard('teacher')->check()) {
            $teacher = Auth::guard('teacher')->user();
            $count = Message::whereHas('conversation', function($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })
            ->where('sender_type', 'student')
            ->where('is_read', false)
            ->count();
            
        } elseif (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();
            $count = Message::whereHas('conversation', function($q) use ($student) {
                $q->where('student_id', $student->id);
            })
            ->where('sender_type', 'teacher')
            ->where('is_read', false)
            ->count();
        }
        
        return response()->json(['unread_count' => $count]);
    }
}