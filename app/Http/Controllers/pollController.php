<?php

// app/Http/Controllers/PollController.php
namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function __construct(){ $this->middleware(['auth','verified']); }

    public function index()
    {
        $polls = Poll::with(['user','options' => fn($q)=>$q->withCount('votes')])
            ->withCount('votes')
            ->latest()
            ->paginate(10);

        return view('polls.index', compact('polls'));
    }

    public function create()
    {
        $this->authorize('create', Poll::class);
        return view('polls.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Poll::class);

        $data = $request->validate([
            'title' => ['required','string','max:160'],
            'description' => ['nullable','string','max:2000'],
            'closes_at' => ['nullable','date'],
            'options' => ['required','array','min:2','max:10'],
            'options.*' => ['required','string','max:160'],
        ]);

        $poll = $request->user()->polls()->create([
            'title'=>$data['title'],
            'description'=>$data['description'] ?? null,
            'closes_at'=>$data['closes_at'] ?? null,
        ]);

        foreach($data['options'] as $i => $text){
            $poll->options()->create(['text'=>$text,'position'=>$i]);
        }

        return redirect()->route('polls.index')->with('status','Encuesta creada');
    }

    public function show(Poll $poll)
    {
        $poll->load(['options' => fn($q)=>$q->withCount('votes')]);
        $total = max(1, $poll->options->sum('votes_count'));
        $userVote = auth()->user()->pollVotes()->where('poll_id',$poll->id)->first();

        return view('polls.show', compact('poll','total','userVote'));
    }

    public function vote(Request $request, Poll $poll)
    {
        if(!$poll->active()->exists()){
            return back()->with('status','La encuesta está cerrada.');
        }

        $data = $request->validate([
            'option_id' => ['required','integer','exists:poll_options,id']
        ]);

        // Asegura que la opción pertenece a la encuesta
        if(! $poll->options()->where('id',$data['option_id'])->exists()){
            abort(422,'Opción inválida');
        }

        // upsert del voto (un usuario, un voto por encuesta)
        PollVote::updateOrCreate(
            ['poll_id'=>$poll->id,'user_id'=>$request->user()->id],
            ['poll_option_id'=>$data['option_id']]
        );

        return back()->with('status','¡Voto registrado!');
    }

    public function close(Poll $poll)
    {
        $this->authorize('close', $poll);
        $poll->update(['is_closed'=>true]);
        return back()->with('status','Encuesta cerrada');
    }

    public function destroy(Poll $poll)
    {
        $this->authorize('delete', $poll);
        $poll->delete();
        return redirect()->route('polls.index')->with('status','Encuesta eliminada');
    }
}
