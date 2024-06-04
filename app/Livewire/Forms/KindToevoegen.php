<?php

namespace App\Livewire\Forms;

use App\Models\ParentPerChild;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Carbon\Carbon;


class KindToevoegen extends Form
{
    public $id = null;
    #[Validate('required', as: 'first name')]
    public $first_name = null;
    #[Validate('required', as: 'last name')]
    public $name = null;
    #[Validate('required', 'date', as: 'date of birth')]
    public $date_of_birth = null;
    #[Validate('required', as: 'size')]
    public $size_id = null;
    #[Validate('required', as: 'gender')]
    public $gender_id = null;

    // Method to read the selected child
    public function read(User $child)
    {
        $this->id = $child->id;
        $this->first_name = $child->first_name;
        $this->name = $child->name;
        $this->date_of_birth = $child->date_of_birth;
        $this->size_id = $child->clothingPerPlayer->first()->clothingSize->id ?? null; // Assuming one clothing size per child
        $this->gender_id = $child->gender_id;
        $this->street_number = $child->street_number; // Populate street_number if needed

    }

    // Method to create a new child
    public function createChild()
    {
        $this->validate();

        // Retrieve the parent's data
        $parent = Auth::user();


        // Create the child record
        $child = User::create([
            'first_name' => $this->first_name,
            'name' => $this->name,
            'date_of_birth' => $this->date_of_birth,
            'size_id' => $this->size_id,
            'gender_id' => $this->gender_id,
            'street_number' => $parent->street_number, // Use parent's street number
            'postal_code' => $parent->postal_code, // Use parent's postal code
            'municipality' => $parent->municipality, // Use parent's municipality
            'role_id' => $parent->role_id, // Use parent's role ID
            'password' => $parent->password,
            'is_registered' => 1 // Assuming the child is registered upon creation
        ]);

        $child->clothingPerPlayer()->create([
            'clothing_size_id' => $this->size_id, // Associate with the selected size
        ]);

        // Create an entry in the ParentPerChild table to link the child to the parent
        ParentPerChild::create([
            'user_child_id' => $child->id, // Use the ID of the newly created child
            'user_parent_id' => $parent->id, // Use the ID of the parent
        ]);

        // Check if there's a second parent registered
        $secondParent = User::where('is_secondParent', true)->first();
        if ($secondParent) {
            // Create an entry in the ParentPerChild table for the second parent
            ParentPerChild::create([
                'user_child_id' => $child->id,
                'user_parent_id' => $secondParent->id,
            ]);
        }
    }


    public function updateChild(User $child)
    {
        // Validate the form fields
        $this->validate();

        // Update the child's details
        $child->update([
            'first_name' => $this->first_name,
            'name' => $this->name,
            'date_of_birth' => $this->date_of_birth,
            'size_id' => $this->size_id,
            'gender_id' => $this->gender_id,
        ]);

        // Check if the child has a clothing entry
        $clothingPerPlayer = $child->clothingPerPlayer;

        if ($clothingPerPlayer->isNotEmpty()) {
            // Update each existing clothing entry
            $clothingPerPlayer->each(function ($clothing) {
                $clothing->update([
                    'clothing_size_id' => $this->size_id,
                ]);
            });
        } else {
            // Create a new clothing entry
            $child->clothingPerPlayer()->create([
                'clothing_size_id' => $this->size_id,
            ]);
        }
    }

    // Method to delete the selected child
    public function deleteChild(User $child)
    {
        $child->delete();
    }
}
