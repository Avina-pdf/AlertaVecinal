<x-app-layout>
  <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $poll->title }}</h2></x-slot>

  <div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">
      @if(session('status'))
        <div class="bg-green-50 text-green-700 px-4 py-2 rounded">{{ session('status') }}</div>
      @endif

      <div class="bg-white p-6 rounded border space-y-4">
        @if($poll->description)
          <p class="text-gray-700">{{ $poll->description }}</p>
        @endif

        @php
          $isActive = !$poll->is_closed && (is_null($poll->closes_at) || $poll->closes_at->isFuture());
        @endphp

        @if($isActive)
          <form method="POST" action="{{ route('polls.vote',$poll) }}" class="space-y-3">
            @csrf
            @foreach($poll->options as $opt)
              <label class="flex items-center gap-2">
                <input type="radio" name="option_id" value="{{ $opt->id }}" {{ optional($userVote)->poll_option_id === $opt->id ? 'checked' : '' }}>
                <span>{{ $opt->text }}</span>
              </label>
            @endforeach
            <div class="flex items-center gap-2">
              <button class="px-4 py-2 bg-indigo-600 text-white rounded">Votar</button>
              @can('close',$poll)
                <form method="POST" action="{{ route('polls.close',$poll) }}" onsubmit="return confirm('¿Cerrar encuesta?');">
                  @csrf
                  <button class="px-4 py-2 bg-gray-200 rounded">Cerrar</button>
                </form>
             
              @endcan
            </div>
          </form>
        @else
          <div class="text-sm text-gray-500">Encuesta cerrada.</div>
        @endif

        <div class="space-y-2 mt-4">
          @foreach($poll->options as $opt)
            @php $pct = round(($opt->votes_count ?? 0) * 100 / max(1,$total)); @endphp
            <div>
              <div class="flex justify-between text-xs text-gray-500">
                <span>{{ $opt->text }}</span>
                <span>{{ $opt->votes_count }} votos ({{ $pct }}%)</span>
              </div>
              <div class="w-full bg-gray-100 rounded h-2">
                <div class="bg-indigo-500 h-2 rounded" style="width: {{ $pct }}%"></div>
              </div>
            </div>
          @endforeach
        </div>

        @can('delete',$poll)
          <form method="POST" action="{{ route('polls.destroy',$poll) }}" onsubmit="return confirm('¿Eliminar encuesta?');" class="mt-4">
            @csrf @method('DELETE')
            <button class="text-pink-600 text-sm hover:underline">Eliminar encuesta</button>
          </form>
        @endcan
      </div>
    </div>
  </div>
</x-app-layout>
