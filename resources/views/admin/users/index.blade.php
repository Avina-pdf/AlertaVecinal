<x-app-layout>
  <x-slot name="header">
    <h2 class="font-bold text-2xl text-indigo-700">Administración · Usuarios</h2>
  </x-slot>

  <div class="py-8">
    <div class="max-w-5xl mx-auto px-6 space-y-4">
      @if (session('status'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
      @endif

      <form method="GET" class="flex gap-3 items-end">
        <div class="flex-1">
          <label class="block text-sm text-gray-600">Buscar</label>
          <input type="text" name="s" value="{{ request('s') }}"
                 class="w-full border rounded px-3 py-2"
                 placeholder="Nombre o email">
        </div>
        <div>
          <label class="block text-sm text-gray-600">Rol</label>
          <select name="role" class="border rounded px-3 py-2">
            <option value="">Todos</option>
            <option value="user"  @selected(request('role')==='user')>user</option>
            <option value="admin" @selected(request('role')==='admin')>admin</option>
          </select>
        </div>
        <button class="h-10 px-4 bg-indigo-600 text-white rounded">Filtrar</button>
      </form>

      <div class="bg-white rounded-lg border overflow-hidden">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 text-gray-600">
            <tr>
              <th class="p-3 text-left">Usuario</th>
              <th class="p-3 text-left">Email</th>
              <th class="p-3 text-left">Rol</th>
              <th class="p-3 text-left">Estado</th>
              <th class="p-3 text-left">Creado</th>
              <th class="p-3 text-left">Acciones</th>
            </tr>
          </thead>
          <tbody>
          @foreach ($users as $u)
            <tr class="border-t">
              <td class="p-3">
                <div class="font-semibold text-gray-800">{{ $u->name }}</div>
                <div class="text-xs text-gray-400">#{{ $u->id }}</div>
              </td>
              <td class="p-3">{{ $u->email }}</td>
              <td class="p-3">
                <form method="POST" action="{{ route('admin.users.update-role', $u) }}" class="flex items-center gap-2">
                  @csrf @method('PATCH')
                  <select name="role" class="border rounded px-2 py-1 text-sm"
                          @disabled(auth()->id()===$u->id)>
                    <option value="user"  @selected($u->role==='user')>user</option>
                    <option value="admin" @selected($u->role==='admin')>admin</option>
                  </select>
                  <button class="px-2 py-1 bg-indigo-600 text-white rounded text-xs"
                          @disabled(auth()->id()===$u->id)>
                    Guardar
                  </button>
                </form>
              </td>
              <td class="p-3">
                <span class="px-2 py-1 rounded text-xs {{ $u->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }}">
                  {{ $u->is_active ? 'activo' : 'inactivo' }}
                </span>
              </td>
              <td class="p-3">{{ $u->created_at?->format('Y-m-d') }}</td>
              <td class="p-3">
                <div class="flex gap-2">
                  <form method="POST" action="{{ route('admin.users.toggle', $u) }}">
                    @csrf @method('PATCH')
                    <button class="px-2 py-1 rounded border text-xs">
                      {{ $u->is_active ? 'Desactivar' : 'Activar' }}
                    </button>
                  </form>

                  @if (auth()->id() !== $u->id)
                  <form method="POST" action="{{ route('admin.users.destroy', $u) }}"
                        onsubmit="return confirm('¿Eliminar usuario y su contenido?');">
                    @csrf @method('DELETE')
                    <button class="px-2 py-1 rounded bg-red-600 text-white text-xs">
                      Eliminar
                    </button>
                  </form>
                  @endif
                </div>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>

      <div>{{ $users->links() }}</div>
    </div>
  </div>
</x-app-layout>
