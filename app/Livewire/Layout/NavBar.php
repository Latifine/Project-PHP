<?php

namespace App\Livewire\Layout;

use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;

class NavBar extends Component
{

    public $avatar;

    #[On('refresh-navigation-menu')]
    public function render()
    {
        if (auth()->user()) {
            $this->avatar = 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->first_name);
            if (auth()->user()->profile_photo_path) {
                if (Storage::disk('public')->exists(auth()->user()->profile_photo_path))
                    $this->avatar = asset('storage/' . auth()->user()->profile_photo_path);
            }
        }
        return view('livewire.layout.nav-bar');
    }
}
