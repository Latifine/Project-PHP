<?php

namespace App\Livewire\Trainer;

use App\Enum\EmailType;
use App\Events\MailToUser;
use App\Models\ParentPerChild;
use App\Models\Registration;
use App\Models\User;
use App\Models\Season;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Livewire\Forms\RegistrationForm;
use function Laravel\Prompts\select;

class UsersRegistration extends Component
{
    public RegistrationForm $form;

    public $search;
    public $switchPaid = false;
    public $showModal = false;
    public $registrations;


    #[Layout('layouts.app', ['title' => 'Registraties', 'description' => 'Beheer hier het lidgeld van spelers.',
        'developer' => 'Gilles Bosmans en Mohammed Hamioui'])]

    // Add new registration
    public function newRegistration()
    {
        $this->form->reset();
        $this->resetErrorBag();
        $this->showModal = true;
    }

    // Create new registration
    public function createRegistration()
    {
        $newRegistration = $this->form->create();
        if(!$newRegistration) {
            return;
        }
        $this->showModal = false;
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "De registratie voor speler is toegevoegd",
            'icon' => 'success',
        ]);
    }

    // delete a registration
    #[On('delete-registarion')]
    public function deleteRegistration($id)
    {
        $this->form->delete($id);

        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "De registratie is verwijderd.",
            'icon' => 'success',
        ]);
    }

    // set registration paid and send email to user
    public function setRegistrationPaid(Registration $registration)
    {
        if ($registration->paid == 0) {
            $registration->paid = 1;
        } elseif ($registration->paid == 1) {
            $registration->paid = 0;
        }

        $registration->update([
            'paid' => $registration->paid,
        ]);
        if ($registration->paid){
            // Send email to the parent about the payment
            event(new MailToUser($registration->user_id, $registration,
                EmailType::REGISTRATION_PAID,
                'Betaling lidgeld gelukt',
                'Uw betaling voor het lidgeld is in orde.'
            ));
        }
    }

    // get all children with parent emails
    public function getChildrenWithParentEmails()
    {
        $childrenWithParentEmails = User::join('parents_per_child', 'users.id', '=', 'parents_per_child.user_child_id')
            ->leftJoin('registrations', 'users.id', '=', 'registrations.user_id')
            ->leftJoin('seasons', 'registrations.season_id', '=', 'seasons.id')
            ->leftJoin('users as parents', 'parents_per_child.user_parent_id', '=', 'parents.id')
            ->select(
                'users.id as user_id',
                'users.first_name as first_name',
                'users.name as name',
                'registrations.id as registration_id',
                'seasons.id as season_id',
                'seasons.date_start',
                'seasons.date_end',
                'registrations.paid',
                'parents.email as parent_email'
            )
            ->orderBy('seasons.date_start');

        return $childrenWithParentEmails;
    }

    public function render()
    {
        $childparentsQuery = ParentPerChild::orderBy('id');
        $registrationUsers = $this->getChildrenWithParentEmails()->get();
        $seasons = Season::orderBy('date_start')->get();

        if ($this->search) {
            $childparentsQuery->whereHas('child', function($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('name', 'like', '%' . $this->search . '%');
            });
        }

        $registrationUsersFilter = $this->getChildrenWithParentEmails()->where('registrations.paid', '=', $this->switchPaid)
            ->where(DB::raw("CONCAT(users.first_name, ' ', users.name)"), 'like', "%{$this->search}%")
            ->get();

        return view('livewire.trainer.users-registration', compact('registrationUsersFilter', 'registrationUsers', 'seasons'));
    }
}
