<?php

namespace App\Livewire;
use App\Livewire\Forms\KindToevoegen;
use App\Models\Gender;
use App\Models\ParentPerChild;
use App\Models\Season;
use App\Models\Size;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

class Kinderen extends Component
{
    public $showModal = false;
    public KindToevoegen $form;

    #[Layout('layouts.app', ['title' => 'Mijn Kinderen', 'description' => 'Overzicht kinderen',
        'developer' => 'Mohammed Hamioui'])]
    public function render()
    {
        // Retrieve the ID of the currently logged-in parent
        $parentId = Auth::user()->id;
        // Retrieve the child IDs associated with the parent
        $parentChildren = ParentPerChild::where('user_parent_id', $parentId)->pluck('user_child_id')->toArray();
        // Retrieve the children based on the fetched child IDs
        $children = User::whereIn('id', $parentChildren)
            ->with(['clothingPerPlayer.clothingSize.size'])
            ->get();
        // Retrieve sizes
        $sizes = Size::all();
        // Retrieve genders
        $genders = Gender::all();
        // Pass the children, sizes, and genders data to the view for rendering
        return view('livewire.kinderen', compact('children', 'sizes', 'genders'));
    }

    public function newChild()
    {
        $this->form->reset();
        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function createChild()
    {
        $this->form->createChild();
        $this->form->reset();
        $this->showModal = false;
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "Het kind is toegevoegd.",
            'icon' => 'success',
        ]);
    }

    public function editChild(User $child)
    {
        $this->resetErrorBag();
        $this->form->read($child);
        $this->showModal = true;
    }

    public function updateChild(User $child)
    {
        $this->form->updateChild($child);
        $this->showModal = false;
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "De gegevens van het kind zijn bijgewerkt.",
            'icon' => 'success',
        ]);
    }

    #[On('delete-child')]
    public function deleteChild(User $user)
    {
        // Delete the related records in the parents_per_child table
        ParentPerChild::where('user_child_id', $user->id)->delete();

        // Delete the related clothing entries
        $user->clothingPerPlayer()->delete();

        // Now delete the child user
        $user->delete();

        // Dispatch a success toast message
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "Het kind is verwijderd.",
            'icon' => 'success',
        ]);
    }




}
