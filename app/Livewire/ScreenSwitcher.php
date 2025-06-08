<?php
// app/Livewire/ScreenSwitcher.php
namespace App\Livewire;

use Livewire\Component;

class ScreenSwitcher extends Component
{
    public $currentScreen = 'dashboard'; // dashboard, profile, settings

    public function showDashboard()
    {
        $this->currentScreen = 'dashboard';
    }

    public function showProfile()
    {
        $this->currentScreen = 'profile';
    }

    public function showSettings()
    {
        $this->currentScreen = 'settings';
    }

    public function render()
    {
        return view('livewire.screen-switcher');
    }
}