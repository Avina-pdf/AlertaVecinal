<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-indigo-700 tracking-tight">
                Notificaciones
            </h2>
            <form method="POST" action="{{ route('notifications.readAll') }}">
                @csrf
                <button class="text-sm text-indigo-600 hover:underline">Marcar todas como leídas</button>
            </form>
        </div>
    </x-slot>

    <div class="py-10 bg-gradient-to-br from-indigo-50 via-white to-pink-50 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @forelse ($notifications as $n)
                @php
                    $data = $n->data;
                    $isUnread = is_null($n->read_at);
                @endphp
                <div class="bg-white/90 p-4 rounded-xl border {{ $isUnread ? 'border-indigo-200' : 'border-gray-200' }} shadow-sm flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm">
                            <span class="font-semibold text-indigo-700">{{ $data['author_name'] ?? 'Alguien' }}</span>
                            publicó algo nuevo.
                        </p>
                        @if(!empty($data['excerpt']))
                            <p class="text-gray-600 text-sm mt-1">{{ $data['excerpt'] }}</p>
                        @endif
                        <p class="text-xs text-gray-400 mt-1">{{ $n->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('dashboard') }}" class="px-3 py-1.5 text-sm bg-indigo-600 text-white rounded-lg">
                            Ver
                        </a>
                        @if($isUnread)
                            <form method="POST" action="{{ route('notifications.readOne', $n->id) }}">
                                @csrf
                                <button class="px-3 py-1.5 text-sm bg-gray-100 rounded-lg">Marcar leída</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white/90 p-8 rounded-2xl shadow-lg text-indigo-400 border border-indigo-100 text-center font-medium">
                    No tienes notificaciones.
                </div>
            @endforelse

            <div class="mt-4">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
