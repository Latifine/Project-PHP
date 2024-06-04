<?php

namespace App\Livewire;

use App\Models\TrainingMatch;
use DateTime;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Kalender extends Component
{
    #[Layout('layouts.app',
        ['title' => 'Kalender', 'description' => 'Welcome to our shop',
            'developer' => 'Illias Latifine'])]
    public $events = [];

    public function mount()
    {
        $this->fetchEvents();
    }

    public function fetchEvents()
    {
        // Fetch events from the TrainingMatch model
        $trainingMatches = TrainingMatch::all();

        // Transform fetched data into the format required by FullCalendar
        foreach ($trainingMatches as $match) {
            $eventName = $match->is_match ? 'Wedstrijd: ' . $match->title : 'Training: ' . $match->title;

            // Convert the date to a DateTime object to format it
            $startDateTime = new DateTime($match->time);

            // Format the date to include only hour and minute
            $hour = $startDateTime->format('H:i');

            $this->events[] = [
                'title' => $eventName . $match->address . " " . $hour,
                'start' => $match->date,
                'color' => $match->is_match ? 'red' : 'black',
                // Add more attributes as needed
            ];
        }
    }

    public function render()
    {
        return view('livewire.kalender');
    }
}
