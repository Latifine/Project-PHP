<?php

namespace App\Livewire\Forms;

use App\Enum\EmailType;
use App\Events\MailToUser;
use App\Events\MailToUsers;
use App\Models\Registration;
use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Form;
use function PHPUnit\Framework\isNull;

class RegistrationForm extends Form
{
    public $errorMessage = [];
    public $id = null;
    public $paid = false;
    #[Validate('required', as: 'Seizoen selecteren')]
    public $season_id = null;
    #[Validate('required|exists:users,id', as: 'Gebruiker selecteren')]
    public $user_id = null;

    // create a new registration payment
    public function create(){
        // Validate the form
        $this->validate();
        // Reset error message
        $this->errorMessage = [];

            // Check if a registration for this user and season already exists
            $existingRegistration = Registration::where('season_id', $this->season_id)
                ->where('user_id', $this->user_id)
                ->first();

            // If no registration exists, create a new one
            if (!$existingRegistration) {
                // Only create a new registration if it doesn't exist
                $registration =  Registration::create([
                    'paid' => $this->paid,
                    'season_id' => $this->season_id,
                    'user_id' => $this->user_id,
                ]);
                // Send email to the parent about the payment
                event(new MailToUser($this->user_id, $registration,
                    $this->paid ? EmailType::REGISTRATION_PAID : EmailType::REGISTRATION_PAID_REQUEST,
                    $this->paid ? 'Betaling lidgeld gelukt' : 'Betaling lidgeld verzoek',
                    $this->paid ? "Uw betaling voor het lidgeld is in orde." : "U bent aangemeld voor het nieuwe seizoen. Gelieve het lidgeld te betalen."));
                return $registration;
            } else {
                // If a registration already exists, return an error message
                array_push($this->errorMessage, 'Een registratie voor deze gebruiker en dit seizoen bestaat al.');
                return;
            }
    }

    // update the selected user
    public function update(Registration $registration) {
        // Validate the form
        $this->validate();
        $registration->update([
            'paid' => $registration->paid,
            'season_id' => $registration->season_id,
            'user_id' => $registration->user_id,
        ]);
    }

    // delete the selected record
    public function delete($id)
    {
        $registration= Registration::findOrFail($id);
        $registration->delete();
    }
}
