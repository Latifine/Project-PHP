<?php

namespace App\Livewire\Forms;

use App\Enum\EmailType;
use App\Events\MailToUsers;
use App\Models\TrainingMatch;
use Carbon\Carbon;
use Livewire\Attributes\Validate;
use Livewire\Form;

class MatchTrainingForm extends Form
{
    public $id = null;
    #[Validate('required|date|after:today', as: 'datum')]
    public $date = null;
    #[Validate('required', as: 'tijd')]
    public $time = null;
    public $address = null;
    public $home = 0;
    public $clothes_wash = null;
    public $is_match = 0;
    public $opponent = null;
    public $preparation = null;
    public $endDate = null;
    public $day = null;
    public $trainingTime = null;


    // read the selected matchtraining
    public function read($matchTraining)
    {
        $this->id = $matchTraining->id;
        $this->date = $matchTraining->date;
        $this->time = $matchTraining->time;
        $this->address = $matchTraining->address;
        $this->home = $matchTraining->home;
        $this->is_match = $matchTraining->is_match;
        $this->opponent = $matchTraining->opponent;
        $this->preparation = $matchTraining->preparation;
    }

    // create a new match or training
    public function create()
    {
        $this->validate();
        TrainingMatch::create([
            'date' => $this->date,
            'time' => $this->time,
            'address' => $this->address,
            'home' => $this->home,
            'is_match' => $this->is_match,
            'opponent' => $this->opponent,
            'preparation' => $this->preparation,
        ]);
    }

    public function createAllTrainings()
    {
        $this->validate([
            'endDate' => 'required|date|after:today',
            'day' => 'required',
            'trainingTime' => 'required',
        ]);

        $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate);
        $selectedDay = strtolower($this->day); // Convert day to lowercase

        // Calculate the difference in weeks between today and the end date
        $diffInWeeks = Carbon::now()->diffInWeeks($endDate);

        // Loop to create one training per week
        for ($i = 0; $i <= $diffInWeeks; $i++) {
            // Calculate the date of the next occurrence of the selected day
            $nextTrainingDate = Carbon::now()->next($selectedDay)->addWeeks($i);

            // Create the training match
            TrainingMatch::create([
                'date' => $nextTrainingDate,
                'time' => $this->trainingTime,
                'is_match' => 0,
            ]);
        }
    }

    // update an existing match or training
    public function update(TrainingMatch $matchTraining)
    {
        $this->validate();
        $matchTraining->update([
            'date' => $this->date,
            'time' => $this->time,
            'address' => $this->address,
            'home' => $this->home,
            'is_match' => $this->is_match,
            'opponent' => $this->opponent,
            'preparation' => $this->preparation,
        ]);
    }

    // delete an existing match or training
    public function delete($id)
    {
        $matchTraining= TrainingMatch::findOrFail($id);
        $matchTraining->delete();
        event(new MailToUsers($matchTraining, EmailType::TRAINING_CANCELLED ,
            'Training of wedstrijd afgelast',
            'De training of wedstrijd is afgelast. Bekijk de website voor meer informatie.'
        ));
    }
}
