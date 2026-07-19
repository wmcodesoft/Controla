<?php

declare(strict_types=1);

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\ResidentMessage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class MessageController extends Controller
{
    public function inbox(): View
    {
        $messages = ResidentMessage::with('sender')
            ->where('recipient_id', auth()->id())
            ->latest()
            ->paginate(20);

        $unreadCount = ResidentMessage::where('recipient_id', auth()->id())
            ->whereNull('read_at')
            ->count();

        return view('modules.resident.messages.inbox', compact('messages', 'unreadCount'));
    }

    public function sent(): View
    {
        $messages = ResidentMessage::with('recipient')
            ->where('sender_id', auth()->id())
            ->latest()
            ->paginate(20);

        return view('modules.resident.messages.sent', compact('messages'));
    }

    public function create(): View
    {
        $users = User::where('id', '!=', auth()->id())
            ->orderBy('name')
            ->get();

        return view('modules.resident.messages.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'recipient_id' => 'nullable|exists:users,id',
            'subject' => 'required|string|max:200',
            'body' => 'required|string',
        ]);

        ResidentMessage::create([
            'sender_id' => auth()->id(),
            'recipient_id' => $validated['recipient_id'],
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'status' => 'sent',
        ]);

        return redirect()
            ->route('resident.messages.sent')
            ->with('success', 'Mensaje enviado.');
    }

    public function show(ResidentMessage $message): View
    {
        if ($message->recipient_id !== auth()->id() && $message->sender_id !== auth()->id()) {
            abort(403);
        }

        if ($message->recipient_id === auth()->id()) {
            $message->markAsRead();
        }

        $message->load(['sender', 'recipient']);

        return view('modules.resident.messages.show', compact('message'));
    }
}
