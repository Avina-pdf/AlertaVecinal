{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Feed
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Mensaje de estado --}}
            @if (session('status'))
                <div class="bg-green-50 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Crear publicación --}}
            <div class="bg-white p-5 sm:p-6 rounded-xl shadow">
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <textarea name="body" rows="3" class="w-full border rounded-lg p-3 focus:outline-none focus:ring"
                              placeholder="¿Qué estás pensando?" required>{{ old('body') }}</textarea>
                    @error('body')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror

                    <div class="flex items-center justify-between gap-3">
                        <input type="file" name="image" accept="image/*" class="text-sm">
                        @error('image')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror

                        <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                            Publicar
                        </button>
                    </div>
                </form>
            </div>

            {{-- Feed --}}
            @if(isset($posts) && $posts->count() > 0)
                @foreach ($posts as $post)
                    <div class="bg-white p-5 sm:p-6 rounded-xl shadow space-y-4">

                        {{-- Header del post --}}
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center font-semibold">
                                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold">{{ $post->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            @can('delete', $post)
                                <form method="POST" action="{{ route('posts.destroy', $post) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 text-sm hover:underline">Eliminar</button>
                                </form>
                            @endcan
                        </div>

                        {{-- Cuerpo --}}
                        <div class="whitespace-pre-line text-gray-800">{{ $post->body }}</div>

                        @if ($post->image_path)
                            <img src="{{ asset('storage/' . $post->image_path) }}" alt="imagen"
                                 class="rounded-lg max-h-[480px] object-cover w-full">
                        @endif

                        {{-- Acciones --}}
                        @php
                            $liked = auth()->check() ? $post->likes->contains('user_id', auth()->id()) : false;
                        @endphp
                        <div class="flex items-center gap-4 text-sm">
                            @if ($liked)
                                <form method="POST" action="{{ route('likes.destroy', $post) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button>❤️ Quitar like</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('likes.store', $post) }}">
                                    @csrf
                                    <button>🤍 Me gusta</button>
                                </form>
                            @endif

                            <span class="text-gray-600">{{ $post->likes->count() }} likes</span>
                            <span class="text-gray-600">{{ $post->comments->count() }} comentarios</span>
                        </div>

                        {{-- Comentarios --}}
                        <div class="space-y-3">
                            @foreach ($post->comments->take(3) as $comment)
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-sm">
                                        <span class="font-semibold">{{ $comment->user->name }}:</span>
                                        {{ $comment->body }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>

                                    @if (auth()->id() === $comment->user_id || auth()->id() === $post->user_id)
                                        <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="mt-1">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-xs text-red-600 hover:underline">Eliminar</button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach

                            @if ($post->comments->count() > 3)
                                <p class="text-xs text-gray-500">
                                    Mostrando 3 de {{ $post->comments->count() }} comentarios
                                </p>
                            @endif

                            <form method="POST" action="{{ route('comments.store', $post) }}" class="flex gap-2">
                                @csrf
                                <input name="body" class="flex-1 border rounded-lg px-3 py-2 focus:outline-none focus:ring"
                                       placeholder="Escribe un comentario..." required>
                                <button class="px-3 py-2 bg-gray-800 hover:bg-black text-white rounded-lg">Comentar</button>
                            </form>
                        </div>
                    </div>
                @endforeach

                {{-- Paginación --}}
                <div class="mt-4">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="bg-white p-6 rounded-xl shadow text-gray-600">
                    No hay publicaciones aún.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
