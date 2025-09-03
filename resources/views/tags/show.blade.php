{{-- resources/views/tags/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-indigo-700 tracking-tight">
                #{{ $tag }}
            </h2>
            <a href="{{ route('dashboard') }}" class="text-sm text-indigo-600 hover:underline">← Volver al feed</a>
        </div>
    </x-slot>

    <div class="py-10 bg-gradient-to-br from-indigo-50 via-white to-pink-50 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if (session('status'))
                <div class="bg-green-100 border border-green-300 text-green-900 px-4 py-3 rounded-xl shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            @forelse ($posts as $post)
                <div class="bg-white/90 p-6 rounded-2xl shadow-lg border border-indigo-100 space-y-5">

                    {{-- Header --}}
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-200 to-pink-200 flex items-center justify-center font-bold text-indigo-700 text-lg shadow">
                                {{ strtoupper(substr($post->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-indigo-800">{{ $post->user->name }}</p>
                                <p class="text-xs text-indigo-400">{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            @can('update', $post)
                                <a href="{{ route('posts.edit', $post) }}" class="text-indigo-600 text-sm hover:underline font-semibold">
                                    Editar
                                </a>
                            @endcan
                            @can('delete', $post)
                                <form method="POST" action="{{ route('posts.destroy', $post) }}">
                                    @csrf @method('DELETE')
                                    <button class="text-pink-600 text-sm hover:underline font-semibold">Eliminar</button>
                                </form>
                            @endcan
                        </div>
                    </div>

                    {{-- Texto con menciones/hashtags --}}
                    <div class="text-gray-900 text-base font-medium">{!! $post->body_html !!}</div>

                    {{-- Galería / Carrusel --}}
                    @php $imgs = $post->images ?? collect(); @endphp
                    @if($imgs->count() === 1)
                        <img src="{{ asset('storage/'.$imgs->first()->path) }}"
                             class="rounded-xl max-h-[400px] object-cover w-full border border-indigo-100 shadow" alt="">
                    @elseif($imgs->count() > 1)
                        <div class="relative">
                            <div class="overflow-hidden rounded-xl border border-indigo-100 shadow">
                                <div class="flex transition-transform duration-300" data-carousel="{{ $post->id }}">
                                    @foreach($imgs as $img)
                                        <img src="{{ asset('storage/'.$img->path) }}"
                                             class="w-full max-h-[400px] object-cover flex-shrink-0" alt="">
                                    @endforeach
                                </div>
                            </div>
                            <button class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 px-3 py-1 rounded-xl border border-indigo-200 shadow hover:bg-white"
                                    onclick="prevSlide({{ $post->id }}, {{ $imgs->count() }})">‹</button>
                            <button class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 px-3 py-1 rounded-xl border border-indigo-200 shadow hover:bg-white"
                                    onclick="nextSlide({{ $post->id }}, {{ $imgs->count() }})">›</button>
                        </div>
                    @elseif ($post->image_path)
                        <img src="{{ asset('storage/'.$post->image_path) }}"
                             class="rounded-xl max-h-[400px] object-cover w-full border border-indigo-100 shadow" alt="">
                    @endif

                    {{-- Acciones (likes + contadores) --}}
                    @php
                        $liked = auth()->check() ? $post->likes->contains('user_id', auth()->id()) : false;
                    @endphp
                    <div class="flex items-center gap-6 text-sm">
                        @if ($liked)
                            <form method="POST" action="{{ route('likes.destroy', $post) }}">
                                @csrf @method('DELETE')
                                <button class="flex items-center gap-1 text-pink-600 font-semibold hover:underline">❤️ Quitar like</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('likes.store', $post) }}">
                                @csrf
                                <button class="flex items-center gap-1 text-indigo-600 font-semibold hover:underline">🤍 Me gusta</button>
                            </form>
                        @endif

                        <span class="text-indigo-400 font-medium">{{ $post->likes->count() }} likes</span>
                        <span class="text-indigo-400 font-medium">{{ $post->comments->count() }} comentarios</span>
                    </div>

                    {{-- Comentarios (lista + formulario) --}}
                    <div class="space-y-3">
                        @foreach ($post->comments->take(3) as $comment)
                            <div class="bg-indigo-50 rounded-xl p-3 border border-indigo-100">
                                <p class="text-sm">
                                    <span class="font-bold text-indigo-700">{{ $comment->user->name }}:</span>
                                    {{ $comment->body }}
                                </p>
                                <p class="text-xs text-indigo-400">{{ $comment->created_at->diffForHumans() }}</p>

                                @if (auth()->id() === $comment->user_id || auth()->id() === $post->user_id)
                                    <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="mt-1">
                                        @csrf @method('DELETE')
                                        <button class="text-xs text-pink-600 hover:underline font-semibold">Eliminar</button>
                                    </form>
                                @endif
                            </div>
                        @endforeach

                        @if ($post->comments->count() > 3)
                            <p class="text-xs text-indigo-400">
                                Mostrando 3 de {{ $post->comments->count() }} comentarios
                            </p>
                        @endif

                        <form method="POST" action="{{ route('comments.store', $post) }}" class="flex gap-2">
                            @csrf
                            <input name="body" class="flex-1 border-2 border-indigo-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400 text-gray-900 placeholder:text-indigo-400"
                                   placeholder="Escribe un comentario..." required>
                            <button class="px-4 py-2 bg-indigo-600 hover:bg-pink-500 text-white rounded-xl font-semibold shadow">Comentar</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white/90 p-8 rounded-2xl shadow-lg text-indigo-400 border border-indigo-100 text-center font-medium">
                    No hay publicaciones con #{{ $tag }}.
                </div>
            @endforelse

            <div class="mt-6 flex justify-center">
                {{ $posts->links() }}
            </div>
        </div>
    </div>

    {{-- Carrusel (mismo JS que en el dashboard) --}}
    <script>
        const carousels = {};
        function showSlide(postId, count){
          const track = document.querySelector(`[data-carousel="${postId}"]`);
          if(!track) return;
          const index = carousels[postId] ?? 0;
          track.style.transform = `translateX(-${index * 100}%)`;
        }
        function nextSlide(postId, count){
          carousels[postId] = ((carousels[postId] ?? 0) + 1) % count;
          showSlide(postId, count);
        }
        function prevSlide(postId, count){
          const current = (carousels[postId] ?? 0);
          carousels[postId] = (current - 1 + count) % count;
          showSlide(postId, count);
        }
        document.addEventListener('DOMContentLoaded', () => {
          document.querySelectorAll('[data-carousel]').forEach(el => {
            const id = parseInt(el.getAttribute('data-carousel'));
            carousels[id] = 0;
            showSlide(id, el.children.length);
          });
        });
    </script>
</x-app-layout>
