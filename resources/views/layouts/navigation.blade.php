<nav x-data="{ open: false }" class="bg-gradient-to-r from-[#1E3A8A] via-[#22C55E] to-[#F3F4F6] shadow-xl border-b border-transparent transition-all duration-500">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center space-x-6">
                <!-- Logo -->
                <div class="shrink-0 flex items-center animate-fade-in">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-10 w-auto fill-current text-white drop-shadow-lg transition-transform duration-300 hover:scale-110" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white font-semibold hover:bg-[#22C55E]/30 px-3 py-2 rounded transition-all duration-300 ease-in-out hover:scale-105">
                        {{ __('Feed') }}
                    </x-nav-link>
                    <x-nav-link :href="route('reports.map')" :active="request()->routeIs('reports.map')" class="text-white font-semibold hover:bg-[#22C55E]/30 px-3 py-2 rounded transition-all duration-300 ease-in-out hover:scale-105">
                        {{ __('Mapa Reportes') }}
                    </x-nav-link>
                    <x-nav-link :href="route('notifications.index')" :active="request()->routeIs('notifications.index')" class="text-white font-semibold hover:bg-[#22C55E]/30 px-3 py-2 rounded transition-all duration-300 ease-in-out hover:scale-105">
                        <span class="relative inline-flex items-center">
                            🔔 {{ __('Notificaciones') }}
                            <span id="notif-badge"
                                class="ml-1 inline-flex items-center justify-center min-w-[18px] h-[18px] text-[11px] rounded-full bg-white text-[#1E3A8A] font-bold px-1.5 shadow animate-bounce"
                                style="display: {{ auth()->user()->unreadNotifications()->count() ? 'inline-flex' : 'none' }}">
                                {{ auth()->user()->unreadNotifications()->count() }}
                            </span>
                        </span>
                    </x-nav-link>
                    <x-nav-link :href="route('polls.index')" :active="request()->routeIs('polls.*')" class="text-white font-semibold hover:bg-[#22C55E]/30 px-3 py-2 rounded transition-all duration-300 ease-in-out hover:scale-105">
                        {{ __('Encuestas') }}
                    </x-nav-link>
                    @if(auth()->check() && auth()->user()->isAdmin())
  <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
    Admin
  </x-nav-link>
@endif

                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-bold rounded-md text-white bg-[#22C55E]/30 hover:bg-[#22C55E]/50 focus:outline-none transition-all duration-300 ease-in-out hover:scale-105">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-[#22C55E]/30 focus:outline-none transition-all duration-300 ease-in-out hover:scale-110">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-[#F3F4F6]/90 backdrop-blur-md shadow-lg rounded-b-lg animate-slide-down">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-[#1E3A8A] font-bold hover:bg-[#22C55E]/20 px-4 py-2 rounded transition-all duration-300 ease-in-out hover:scale-105">
                {{ __('Feed') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('reports.map')" :active="request()->routeIs('reports.map')" class="text-[#1E3A8A] font-bold hover:bg-[#22C55E]/20 px-4 py-2 rounded transition-all duration-300 ease-in-out hover:scale-105">
                {{__('Mapa Reportes') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('notifications.index')" :active="request()->routeIs('notifications.index')" class="text-[#1E3A8A] font-bold hover:bg-[#22C55E]/20 px-4 py-2 rounded transition-all duration-300 ease-in-out hover:scale-105">
                {{__('Notificaciones') }}
            </x-responsive-nav-link>
            <x-nav-link :href="route('polls.index')" :active="request()->routeIs('polls.*')" class="text-[#1E3A8A] font-bold hover:bg-[#22C55E]/20 px-4 py-2 rounded transition-all duration-300 ease-in-out hover:scale-105">
                        {{ __('Encuestas') }}
                    </x-nav-link>
        </div>
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-[#22C55E]/30">
            <div class="px-4">
                <div class="font-bold text-base text-[#1E3A8A]">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-[#22C55E]">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-[#1E3A8A] font-bold hover:bg-[#22C55E]/20 px-4 py-2 rounded transition-all duration-300 ease-in-out hover:scale-105">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();" class="text-[#1E3A8A] font-bold hover:bg-[#22C55E]/20 px-4 py-2 rounded transition-all duration-300 ease-in-out hover:scale-105">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-10px);}
            to { opacity: 1; transform: translateY(0);}
        }
        .animate-fade-in {
            animation: fade-in 0.8s cubic-bezier(.4,0,.2,1) both;
        }
        @keyframes slide-down {
            from { opacity: 0; transform: translateY(-20px);}
            to { opacity: 1; transform: translateY(0);}
        }
        .animate-slide-down {
            animation: slide-down 0.5s cubic-bezier(.4,0,.2,1) both;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0);}
            50% { transform: translateY(-6px);}
        }
        .animate-bounce {
            animation: bounce 1s infinite;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const badge = document.getElementById('notif-badge');
            const url = "{{ route('notifications.count') }}";
            async function tick() {
                try {
                    const res = await fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await res.json();
                    if (!badge) return;
                    if (data.count > 0) {
                        badge.style.display = 'inline-flex';
                        badge.textContent = data.count;
                        badge.classList.add('animate-bounce');
                    } else {
                        badge.style.display = 'none';
                        badge.classList.remove('animate-bounce');
                    }
                } catch (e) { /* noop */ }
            }
            tick();
            setInterval(tick, 20000);
        });
    </script>
</nav>
