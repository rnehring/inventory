<nav class="bg-gray-800">
    <div class="mx-auto max-w-9xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center">
                <div class="shrink-0">
                    <a href="/"><img class="size-8" src="{{URL::asset('/images/andronaco_industries_logo.png')}}" alt="Andronaco Industries"></a>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        @auth
                            {{-- Manager-only links --}}
                            @if( Auth::user()->user_type->canManageUsers() )
                                <x-nav-link href="/dashboard" :active="request()->is('dashboard')">
                                    <x-ri-dashboard-line class="w-4 h-4 inline-block mr-2" />Dashboard
                                </x-nav-link>
                                <x-nav-link href="/data" :active="request()->is('data')">
                                    <x-ri-database-line class="w-4 h-4 inline-block mr-2"/>Data
                                </x-nav-link>
                            @endif

                            {{-- All authenticated users --}}
                            <x-nav-link href="/count" :active="request()->is('count')">
                                <x-tabler-numbers class="w-4 h-4 inline-block mr-2"/>Count
                            </x-nav-link>
                            <x-nav-link href="/notag" :active="request()->is('notag')">
                                <x-tabler-tag class="w-4 h-4 inline-block mr-2"/>No Tag
                            </x-nav-link>

                            {{-- Manager-only links --}}
                            @if( Auth::user()->user_type->canManageUsers() )
                                <x-nav-link href="/upload" :active="request()->is('upload')">
                                    <x-tabler-upload class="w-4 h-4 inline-block mr-2"/>Upload
                                </x-nav-link>
                                <x-nav-link href="/users" :active="request()->is('users')">
                                    <x-tabler-users class="w-4 h-4 inline-block mr-2" />Users
                                </x-nav-link>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="ml-4 flex items-center md:ml-6">
                    @guest
                        <x-nav-link href="/">Login</x-nav-link>
                        <div>
                            <button type="button" class="relative flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                <span class="absolute -inset-1.5"></span>
                                <img class="size-8 rounded-full" src="{{URL::asset('/images/default-avatar.png')}}" alt="avatar">
                            </button>
                        </div>
                    @endguest

                    @auth
                        <form method="POST" action="/logout">
                            @csrf
                            <x-form-submit class="mt-4">Logout</x-form-submit>
                        </form>
                        <x-avatar>{{ Auth::user()->initials }}</x-avatar>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>
