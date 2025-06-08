<!-- resources/views/livewire/screen-switcher.blade.php -->
<div class="screen-switcher">
    <!-- Navigation Buttons -->
    <div class="flex space-x-4 mb-6">
        <button wire:click="showDashboard" 
                class="px-4 py-2 rounded {{ $currentScreen === 'dashboard' ? 'bg-blue-500 text-white' : 'bg-gray-200' }}">
            Dashboard
        </button>
        <button wire:click="showProfile"
                class="px-4 py-2 rounded {{ $currentScreen === 'profile' ? 'bg-blue-500 text-white' : 'bg-gray-200' }}">
            Profile
        </button>
        <button wire:click="showSettings"
                class="px-4 py-2 rounded {{ $currentScreen === 'settings' ? 'bg-blue-500 text-white' : 'bg-gray-200' }}">
            Settings
        </button>
    </div>

    <!-- Screen Content -->
    <div class="screen-content p-4 border rounded-lg">
        @if($currentScreen === 'dashboard')
            <div class="dashboard-screen">
                <h2 class="text-xl font-bold mb-4">Dashboard Screen</h2>
                <p>Welcome to your dashboard! Here are your recent activities...</p>
                <!-- Dashboard content here -->
            </div>
        @elseif($currentScreen === 'profile')
            <div class="profile-screen">
                <h2 class="text-xl font-bold mb-4">Profile Screen</h2>
                <div class="flex items-center space-x-4">
                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16" />
                    <div>
                        <h3 class="font-medium">John Doe</h3>
                        <p>john@example.com</p>
                    </div>
                </div>
                <!-- Profile content here -->
            </div>
        @elseif($currentScreen === 'settings')
            <div class="settings-screen">
                <h2 class="text-xl font-bold mb-4">Settings Screen</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block mb-1">Notification Preferences</label>
                        <select class="w-full p-2 border rounded">
                            <option>Email notifications</option>
                            <option>Push notifications</option>
                        </select>
                    </div>
                    <!-- More settings here -->
                </div>
            </div>
        @endif
    </div>
</div>