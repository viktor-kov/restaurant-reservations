<nav x-data="{ open: false }">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="hidden sm:flex sm:items-center">
                <x-nav-link
                    :href="route('homepage')"
                >
                    {{ __('New Reservation') }}
                </x-nav-link>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @can('admin')
                    <x-nav-link
                        :href="route('admin.reservations.list')"
                        :active="request()->routeIs('admin.reservations.list')"
                    >
                        {{ __('All Reservations') }}
                    </x-nav-link>
                @endcan

                @can('customer')
                    <x-nav-link
                        :href="route('customer.reservations.list')"
                        :active="request()->routeIs('customer.reservations.list')"
                    >
                        {{ __('My Reservations') }}
                    </x-nav-link>
                @endcan

                @auth
                    <div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-nav-link
                                :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                            >
                                {{ __('Log Out') }}
                            </x-nav-link>
                        </form>
                    </div>
                @endauth

                @guest
                    <x-nav-link
                        :href="route('login')"
                    >
                        {{ __('Log in') }}
                    </x-nav-link>

                    <x-nav-link
                        :href="route('register')"
                    >
                        {{ __('Register') }}
                    </x-nav-link>
                @endguest
            </div>
            <div class="flex justify-end w-full sm:hidden">
                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t bg-white border-gray-200">
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
