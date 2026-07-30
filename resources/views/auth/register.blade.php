<x-guest-layout>
    <div>
        <!-- Title -->
        <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Register</h1>

        <!-- Subtitle -->
        <h2 class="text-md font-bold text-slate-700 mt-6">Create your account</h2>

        <!-- Description -->
        <p class="text-sm text-slate-500 mt-2 leading-relaxed">
            Thank you for choosing Nelel web applications, let's access our the best recommendation for you.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
        @csrf

        <!-- Company / Tenant Name -->
        <div>
            <input id="company_name" class="block w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:border-[#3b59dd] focus:ring focus:ring-[#3b59dd]/20 text-gray-800 placeholder-gray-400 text-sm transition duration-150 ease-in-out" type="text" name="company_name" :value="old('company_name')" required autofocus autocomplete="organization" placeholder="Your Company / Tenant Name" />
            <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
        </div>

        <!-- Name -->
        <div>
            <input id="name" class="block w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:border-[#3b59dd] focus:ring focus:ring-[#3b59dd]/20 text-gray-800 placeholder-gray-400 text-sm transition duration-150 ease-in-out" type="text" name="name" :value="old('name')" required autocomplete="name" placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <input id="email" class="block w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:border-[#3b59dd] focus:ring focus:ring-[#3b59dd]/20 text-gray-800 placeholder-gray-400 text-sm transition duration-150 ease-in-out" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="johndoe@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <input id="password" class="block w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:border-[#3b59dd] focus:ring focus:ring-[#3b59dd]/20 text-gray-800 placeholder-gray-400 text-sm transition duration-150 ease-in-out"
                            type="password"
                            name="password"
                            required autocomplete="new-password" placeholder="Password (••••••)" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <input id="password_confirmation" class="block w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:border-[#3b59dd] focus:ring focus:ring-[#3b59dd]/20 text-gray-800 placeholder-gray-400 text-sm transition duration-150 ease-in-out"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password (••••••)" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="w-full py-4 px-4 bg-[#3b59dd] hover:bg-[#2d46b9] text-white font-bold rounded-xl text-sm uppercase tracking-wider transition duration-150 ease-in-out shadow-sm shadow-[#3b59dd]/30">
                CREATE ACCOUNT
            </button>
        </div>

        <!-- OR Separator -->
        <div class="relative flex items-center justify-center my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-200/80"></div>
            </div>
            <div class="relative bg-white px-4 text-xs font-semibold uppercase text-slate-400">
                OR
            </div>
        </div>

        <!-- Social Buttons -->
        <div class="grid grid-cols-3 gap-3">
            <!-- Facebook -->
            <a href="#" class="flex items-center justify-center py-3.5 px-4 rounded-xl border border-gray-200 hover:bg-slate-50 transition duration-150 ease-in-out">
                <svg class="w-5 h-5 text-[#3b59dd]" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                </svg>
            </a>

            <!-- Twitter -->
            <a href="#" class="flex items-center justify-center py-3.5 px-4 rounded-xl border border-gray-200 hover:bg-slate-50 transition duration-150 ease-in-out">
                <svg class="w-5 h-5 text-[#1da1f2]" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.359 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.118 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.117 0 001.27 5.488 4.099 4.099 0 01-1.859-.513v.052a4.115 4.115 0 003.3 4.03 4.1 4.1 0 01-1.853.07 4.127 4.117 0 003.833 2.857 8.24 8.24 0 01-5.1 1.758 8.26 8.26 0 01-.98-.057 11.606 11.606 0 006.29 1.84" />
                </svg>
            </a>

            <!-- GitHub -->
            <a href="#" class="flex items-center justify-center py-3.5 px-4 rounded-xl border border-gray-200 hover:bg-slate-50 transition duration-150 ease-in-out">
                <svg class="w-5 h-5 text-[#24292f]" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>

        <!-- Footer -->
        <div class="text-center text-sm text-slate-500 pt-4">
            Already have an account?
            <a href="{{ route('login') }}" class="text-[#3b59dd] hover:underline font-bold">
                Login
            </a>
        </div>
    </form>
</x-guest-layout>
