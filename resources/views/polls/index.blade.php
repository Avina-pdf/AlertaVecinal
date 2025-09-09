<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Encuestas</h2>
  </x-slot>

  <div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
      @if(session('status'))
        <div class="bg-green-50 text-green-700 px-4 py-2 rounded">{{ session('status') }}</div>
      @endif

      <div class="flex justify-end">
        <a href="{{ route('polls.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">Nueva encuesta</a>
      </div>

      @forelse($polls as $poll)
        <a href="{{ route('polls.show',$poll) }}" class="block bg-white rounded border p-4 hover:bg-gray-50">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="font-semibold text-gray-800">{{ $poll->title }}</h3>
              <p class="text-sm text-gray-500">Por {{ $poll->user->name }} • {{ $poll->created_at->diffForHumans() }}</p>
            </div>
            @php
              $active = !$poll->is_closed && (is_null($poll->closes_at) || $poll->closes_at->isFuture());
            @endphp
            <span class="text-xs px-2 py-1 rounded {{ $active ? 'bg-green-100 text-green-700':'bg-gray-100 text-gray-600' }}">
              {{ $active ? 'Activa' : 'Cerrada' }}
            </span>
          </div>
          <div class="mt-3 space-y-2">
            @foreach($poll->options as $opt)
              @php $total = max(1, $poll->votes_count); $pct = round(($opt->votes_count ?? 0) * 100 / $total); @endphp
              <div>
                <div class="flex justify-between text-xs text-gray-500">
                  <span>{{ $opt->text }}</span><span>{{ $pct }}%</span>
                </div>
                <div class="w-full bg-gray-100 rounded h-2">
                  <div class="bg-indigo-500 h-2 rounded" style="width: {{ $pct }}%"></div>
                </div>
              </div>
            @endforeach
          </div>
        </a>
      @empty
        <div class="bg-white p-6 rounded border text-gray-500">No hay encuestas.</div>
      @endforelse

      {{ $polls->links() }}
    </div>
  </div>
</x-app-layout>
