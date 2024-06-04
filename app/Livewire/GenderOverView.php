<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

class GenderOverView extends Component
{
    #[Layout('layouts.home', ['title' => 'KVV Rauw | Gender Overview', 'description' => 'Geslachten aanpassen? Hier!',
        'developer' => 'Jorrit Janssens'])]
    public function render()
    {
        return view('livewire.gender-over-view');
    }
}
