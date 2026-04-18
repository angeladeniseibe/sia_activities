<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">

    @php
        use App\Models\User;

        // Total avatars available
        $totalAvatars = 20;

        // Get all user IDs sorted
        $allUserIds = User::orderBy('id')->pluck('id')->toArray();

        // Find current user's index
        $userIndex = array_search(Auth::id(), $allUserIds);

        // Handle edge case if not found
        $userIndex = $userIndex === false ? 0 : $userIndex;

        // Compute avatar number (1–20)
        $avatarNumber = ($userIndex % $totalAvatars) + 1;
    @endphp

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- Left Side -->
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('images/logo.png') }}" 
                             alt="Smart Energy Monitoring Logo" 
                             class="h-10 w-auto object-contain">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" 
                        :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="route('customers.index')" 
                        :active="request()->routeIs('customers.*')">
                        {{ __('Customer') }}
                    </x-nav-link>

                    <x-nav-link :href="route('products.index')" 
                        :active="request()->routeIs('products.*')">
                        {{ __('Electric Usage') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Right Side (Desktop) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">

                    <!-- Trigger -->
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">

                            <!-- Avatar chagened-->
                           <img src="{{ Auth::user()->avatar 
                                ? asset('storage/' . Auth::user()->avatar) 
                                : asset('avatars/defaultprofile.png') }}" 
                                class="h-8 w-8 rounded-full border-2 border-green-500" 
                                alt="User Avatar">
                            <!-- Name -->   
                            <div class="ms-2 text-gray-700">
                                {{ Auth::user()->name }}
                            </div>

                            <!-- Arrow -->
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" 
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" 
                                        clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <!-- Dropdown Content -->
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                         this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>

                </x-dropdown>
            </div>

            <!-- Mobile Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open"
                    class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                    
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" 
                            class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}" 
                            class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">

        <!-- Desktop Navigation Links -->
<div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">

    <!-- Dashboard -->
    <x-nav-link 
        :href="route('dashboard')" 
        :active="request()->routeIs('dashboard')">
        {{ __('Dashboard') }}
    </x-nav-link>

    <!-- Customer -->
    <x-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.index')">
    Customer
</x-nav-link>

    <!-- Plants -->
    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.index')">
    Plants
</x-nav-link>
</div>

        <!-- User Info -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4 flex items-center space-x-3">

                <!-- SAME avatar logic -->
                <img src="{{ asset('avatars/avatar' . $avatarNumber . '.png') }}" 
                     class="h-10 w-10 rounded-full border-2 border-green-500" 
                     alt="User Avatar">

                <div>
                    <div class="text-base font-medium text-gray-800">
                        {{ Auth::user()->name }}
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ Auth::user()->email }}
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

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