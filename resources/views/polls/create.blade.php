<x-app-layout>
  <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Crear encuesta</h2></x-slot>

  <div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
      <form method="POST" action="{{ route('polls.store') }}" class="bg-white rounded border p-6 space-y-4">
        @csrf

        <div>
          <label class="block text-sm font-medium">Título</label>
          <input name="title" class="mt-1 w-full border rounded px-3 py-2" required value="{{ old('title') }}">
          @error('title')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="block text-sm font-medium">Descripción (opcional)</label>
          <textarea name="description" rows="3" class="mt-1 w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
        </div>

        <div>
          <label class="block text-sm font-medium">Cierra el</label>
          <input type="datetime-local" name="closes_at" class="mt-1 border rounded px-3 py-2" value="{{ old('closes_at') }}">
        </div>

        <div class="space-y-2">
          <label class="block text-sm font-medium">Opciones (mín. 2, máx. 10)</label>
          @for($i=0;$i<4;$i++)
            <input name="options[]" class="w-full border rounded px-3 py-2" placeholder="Opción {{ $i+1 }}" value="{{ old('options.'.$i) }}">
          @endfor
          <p class="text-xs text-gray-500">Puedes agregar más inputs después si lo necesitas.</p>
        </div>

        <div class="flex justify-end gap-2">
          <a href="{{ route('polls.index') }}" class="px-4 py-2 border rounded">Cancelar</a>
          <button class="px-4 py-2 bg-indigo-600 text-white rounded">Crear</button>
        </div>
      </form>
    </div>
  </div>
</x-app-layout>
