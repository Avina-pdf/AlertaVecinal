<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use App\Models\PollVote;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function index()
    {
        return Poll::with(['options','user:id,name'])
            ->withCount('votes')
            ->latest()->paginate(10);
    }

    public function show(Poll $poll)
    {
        $poll->load(['options','user:id,name'])->loadCount('votes');
        $myVote = auth()->id()
          ? $poll->votes()->where('user_id', auth()->id())->value('poll_option_id')
          : null;

        return ['poll'=>$poll, 'my_vote'=>$myVote, 'is_active'=>$poll->is_active];
    }

    public function store(Request $r)
    {
        // Opcional: $this->authorize('create', Poll::class); // sólo admin
        $data = $r->validate([
            'title'=>'required|string|max:160',
            'description'=>'nullable|string|max:2000',
            'closes_at'=>'nullable|date',
            'options'=>'required|array|min:2|max:10',
            'options.*'=>'required|string|max:160',
        ]);

        $poll = $r->user()->polls()->create([
            'title'=>$data['title'],
            'description'=>$data['description'] ?? null,
            'closes_at'=>$data['closes_at'] ?? null,
        ]);
        foreach ($data['options'] as $i=>$text) {
            $poll->options()->create(['text'=>$text,'position'=>$i]);
        }
        return $poll->load('options');
    }

    public function vote(Request $r, Poll $poll)
    {
        abort_unless($poll->is_active, 422, 'Encuesta cerrada');

        $data = $r->validate(['option_id'=>'required|exists:poll_options,id']);
        $option = $poll->options()->findOrFail($data['option_id']);

        PollVote::updateOrCreate(
            ['poll_id'=>$poll->id, 'user_id'=>$r->user()->id],
            ['poll_option_id'=>$option->id]
        );

        return ['message'=>'ok'];
    }
}
