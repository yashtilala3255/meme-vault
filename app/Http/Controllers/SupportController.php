<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function index()
    {
        $tickets = Auth::check() 
            ? Auth::user()->supportTickets()->latest()->get()
            : collect();

        return view('support.index', compact('tickets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'email' => 'required_without:user_id|email',
        ]);

        $priority = 'normal';
        
        // Premium users get priority support
        if (Auth::check() && Auth::user()->hasPrioritySupport()) {
            $priority = Auth::user()->isBusiness() ? 'high' : 'normal';
        }

        $ticket = SupportTicket::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'message' => $request->message,
            'priority' => $priority,
            'status' => 'open',
        ]);

        if (Auth::check()) {
            return redirect()->route('support.show', $ticket)
                ->with('success', 'Support ticket created! Ticket #' . $ticket->ticket_number);
        }

        return redirect()->back()
            ->with('success', 'Support ticket created! We will respond to ' . $request->email . ' soon.');
    }

    public function show(SupportTicket $ticket)
    {
        if (Auth::id() !== $ticket->user_id) {
            abort(403);
        }

        $ticket->load('replies.user', 'replies.admin');

        return view('support.show', compact('ticket'));
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        if (Auth::id() !== $ticket->user_id) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string|min:10',
        ]);

        SupportTicketReply::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        // Reopen ticket if it was closed
        if (in_array($ticket->status, ['resolved', 'closed'])) {
            $ticket->update(['status' => 'open']);
        }

        return redirect()->back()->with('success', 'Reply sent!');
    }
}