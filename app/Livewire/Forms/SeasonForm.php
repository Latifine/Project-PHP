<?php

namespace App\Livewire\Forms;

use App\Enum\EmailType;
use App\Events\MailToUser;
use App\Models\Registration;
use App\Models\Season;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SeasonForm extends Form
{
    public $errorMessage = [];
    public $id = null;
    #[Validate('required|date', as: 'Start datum')]
    public $date_start = null;
    #[Validate('required|date|after:date_start', as: 'datum na start datum')]
    public $date_end = null;

    // get all children with their parent
    public function getChildrenWithParent()
    {
        $childrenWithParent = User::join('parents_per_child', 'users.id', '=', 'parents_per_child.user_child_id')
            ->leftJoin('users as parents', 'parents_per_child.user_parent_id', '=', 'parents.id')
            ->select(
                'users.id as id',
                'users.first_name as first_name',
                'users.name as name',
                DB::raw('GROUP_CONCAT(DISTINCT parents.email) as parent_email')
            )
            ->groupBy('users.id', 'users.first_name', 'users.name')
            ->orderBy('users.id')
            ->get();

        return $childrenWithParent;
    }

    // create a new registration for all children
    public function createRegistrations($season){
        // Validate the form
        $this->validate();
        // Reset error message
        $this->errorMessage = [];

        // Get all children with their parent
        $children = $this->getChildrenWithParent();

        // Loop through all children
        foreach ($children as $child) {
            // Check if a registration for this user and season already exists
            $existingRegistration = Registration::where('season_id', $season->id && 'user_id', $child->id)
                ->first();

            // If no registration exists, create a new one
            if (!$existingRegistration) {
                // Only create a new registration if it doesn't exist
                $registration = Registration::create([
                    'paid' => false,
                    'season_id' => $season->id,
                    'user_id' => $child->id,
                ]);

                // Send email to the parent for a payment request
                event(new MailToUser($child->id, $registration,
                    EmailType::REGISTRATION_PAID_REQUEST,
                    'Betaling lidgeld verzoek',
                    'U bent aangemeld voor het nieuwe seizoen. Gelieve het lidgeld te betalen.'
                ));
            } else {
                array_push($this->errorMessage, "De registratie " . $child->first_name . " - " . $child->name ." met deze datum bestond al en is dus niet toegevoegd.");
            }
        }
    }

    // create season
    public function create()
    {
        // Validate the form
        $this->validate();
        $this->errorMessage = [];
        // Check if a season with these start and end dates already exists
        $existingSeason = Season::where('date_start', $this->date_start)
            ->where('date_end', $this->date_end)
            ->first();

        if (!$existingSeason) {
            $this->validate();
            return Season::create([
                'date_start' => $this->date_start,
                'date_end' => $this->date_end,
            ]);
        } else {
            array_push($this->errorMessage, "Een seizoen met deze start en eind datum bestaat al.");
            return;
        }
    }

    // update the selected season
    public function update(Season $season) {
        // Validate the form
        $this->validate();
        $season->update([
            'date_start' => $season->date_start,
            'date_end' => $season->date_end,
        ]);
    }

    // delete the selected season
    public function delete($id)
    {
        $season= Season::findOrFail($id);
        $season->delete();
    }
}
