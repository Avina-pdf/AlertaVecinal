{{-- resources/views/posts/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar publicación
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if ($errors->any())
                <div class="bg-red-50 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc ms-5 text-sm">
                        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-xl border p-6 space-y-4">
                <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Contenido</label>
                        <textarea name="body" rows="4" class="mt-1 w-full border rounded-lg px-3 py-2" required>{{ old('body', $post->body) }}</textarea>
                    </div>

                    {{-- Imágenes actuales con opción de quitar --}}
                    @if($post->images->count())
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">Imágenes actuales</p>
                            <div class="flex flex-wrap gap-3">
                                @foreach($post->images as $img)
                                    <label class="inline-flex flex-col items-center gap-2">
                                        <img src="{{ asset('storage/'.$img->path) }}" class="h-24 w-24 object-cover rounded border" alt="">
                                        <input type="checkbox" name="remove_images[]" value="{{ $img->id }}">
                                        <span class="text-xs text-gray-600">Quitar</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Agregar nuevas imágenes --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Agregar imágenes (opcional)</label>
                        <input type="file" name="images[]" multiple accept="image/*" class="mt-1 block">
                        <p class="text-xs text-gray-500">Hasta 6 imágenes, 5MB c/u.</p>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 border rounded-lg">Cancelar</a>
                        <button class="px-5 py-2 bg-indigo-600 text-white rounded-lg">Guardar cambios</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
