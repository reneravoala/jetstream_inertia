<?php

namespace App\Http\Controllers;

use App\Events\MessageWasComposedEvent;
use App\Models\Discussion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Lexx\ChatMessenger\Models\Message;
use Lexx\ChatMessenger\Models\Participant;
use Lexx\ChatMessenger\Models\Thread;

class MessagesController extends Controller
{
    public function index()
    {
        return inertia('Messages/Index', [
            'threads' => auth()->user()->threads,
        ]);
    }

    public function show(int $thread_id)
    {
        $thread = Discussion::find($thread_id);
        $thread->one(auth()->user()->id)->update([
            'last_read' => new Carbon,
        ]);
        return inertia('Messages/Discussion', [
            'thread' => $thread,
            'messages' => $thread->messages()->with('user')->get(),
            'other' => $thread->other(auth()->user()->id),
        ]);
    }

    public function create(int $thread_id, Request $request)
    {
        $message = Message::create([
            'thread_id' => $thread_id,
            'user_id' => auth()->user()->id,
            'body' => $request->body,
        ]);

        $this->oooPushIt($message);
        return \Redirect::route('messages.show', $thread_id);
    }

    public function createThread()
    {
        return inertia('Messages/CreateThread', [
            'users' => User::whereNot('id', auth()->user()->id)->get()
        ]);
    }

    public function storeThread(Request $request)
    {
        $thread = Thread::create([
            'subject' => $request->subject
        ]);
        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => $request->user_id,
            'last_read' => new Carbon
        ]);
        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => auth()->user()->id,
            'last_read' => new Carbon
        ]);

        return \Redirect::route('messages.index');
    }

    /**
     * Send the new message to Pusher in order to notify users.
     *
     * @param Message $message
     * @return void
     */
    protected function oooPushIt(Message $message, $html = '')
    {
        $thread = $message->thread;
        $sender = $message->user;

        $data = [
            'thread_id' => $thread->id,
            'div_id' => 'thread_' . $thread->id,
            'sender_name' => $sender->name,
            'thread_url' => route('messages.show', ['id' => $thread->id]),
            'thread_subject' => $thread->subject,
            'message' => $message->body,
            'html' => $html,
            'text' => substr($message->body, 0, 50) . '...',
            'created_at' => $message->created_at
        ];

        $recipients = $thread->participantsUserIds();
        if (count($recipients) > 0) {
            foreach ($recipients as $recipient) {
                if ($recipient == $sender->id) {
                    continue;
                }

                // previous way of doing it
                // $pusher_resp = Pusher::trigger(['for_user_' . $recipient], 'new_message', $data);

                // new way of doing it
                event(new MessageWasComposedEvent($recipient, $data));

                // We're done here - how easy was that, it just works!
            }
        }
    }
}
