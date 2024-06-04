<?php

namespace App\Livewire;

use App\Models\TrainingMatch;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Home extends Component
{
    #[Layout('layouts.home', ['title' => 'KVV Rauw | Home', 'description' => 'Welkom op onze homepagina.',
'developer' => 'Jorrit Leysen', 'hideSubtitle' => true ])]
    public function render()
    {
            $query = TrainingMatch::where('date', '>=', Carbon::now())
                ->where('is_match', true)
                ->orderBy('date', 'asc')
                ->orderBy('time', 'asc')
                ->take(3)
                ->get();

        $matches = $query;
        return view('home', compact('matches'));
    }
}
