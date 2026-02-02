<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="bg-[url('/images/wallpaper7.jpg')] bg-center bg-top bg-fixed bg-cover min-h-screen">
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <!-- Profile Content Container with Semi-Transparent Background -->
            <div class="bg-white/90 backdrop-blur-sm rounded-xl shadow-xl p-6 sm:p-8 mb-8">
                <div class="max-w-4xl mx-auto">
                @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                    <div class="mb-10">
                        @livewire('profile.update-profile-information-form')
                    </div>
                                
                    @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()) || 
                         Laravel\Fortify\Features::canManageTwoFactorAuthentication() ||
                         Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                        <hr class="border-gray-200 my-8">
                    @endif
                @endif

                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                    <div class="mb-10">
                        @livewire('profile.update-password-form')
                    </div>
                                
                    @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication() ||
                         Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                        <hr class="border-gray-200 my-8">
                    @endif
                @endif

                @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                    <div class="mb-10">
                        @livewire('profile.two-factor-authentication-form')
                    </div>
                                
                    @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                        <hr class="border-gray-200 my-8">
                    @endif
                @endif

                <div class="mb-10">
                    @livewire('profile.logout-other-browser-sessions-form')
                </div>

                @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                    <div class="mb-10">
                        @livewire('profile.delete-user-form')
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
