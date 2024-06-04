<?php

namespace App\Actions\Fortify;

use App\Models\ClothingPerPlayer;
use App\Models\Gender;
use App\Models\ParentPerChild;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use App\Events\SecondParentActivated;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param array<string, string> $input
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(array $input): User
    {
        //dd($input);

        $permissionPhotos = isset($input['permission_photos']) ? true : false;


        Validator::extend('past_date', function ($attribute, $value, $parameters, $validator) {
            // Controleer of de ingevoerde waarde een geldige datum is
            if (!strtotime($value)) {
                return false;
            }

            // Controleer of de datum in het verleden ligt
            return strtotime($value) <= strtotime('today');
        });

        // Validate the input data
        $customMessages = [

            'child_first_name.required' => 'Vul de voornaam van het kind in.',
            'child_first_name.max' => 'De voornaam van het kind mag niet langer zijn dan 255 tekens.',
            'child_last_name.required' => 'Vul de achternaam van het kind in.',
            'child_last_name.max' => 'De achternaam van het kind mag niet langer zijn dan 255 tekens.',
            'child_date_of_birth.required' => 'Vul de geboortedatum van het kind in.',
            'child_date_of_birth.date' => 'Voer een geldige geboortedatum voor het kind in.',
            'child_date_of_birth.past_date' => 'De geboortedatum van jouw kind moet in het verleden liggen.',
            'child_gender.required' => 'Selecteer het geslacht van het kind.',

            'parent_gender.required' => 'Selecteer het geslacht van de ouder.',
            'parent_date_of_birth.required' => 'Vul de geboortedatum van de ouder in.',
            'parent_date_of_birth.date' => 'Voer een geldige geboortedatum voor de ouder in.',
            'parent_date_of_birth.past_date' => 'De geboortedatum van de ouder moet in het verleden liggen.',
            'parent_first_name.required' => 'Vul de voornaam van de ouder in.',
            'parent_first_name.max' => 'De voornaam van de ouder mag niet langer zijn dan 255 tekens.',
            'parent_last_name.required' => 'Vul de achternaam van de ouder in.',
            'parent_last_name.max' => 'De achternaam van de ouder mag niet langer zijn dan 255 tekens.',
            'parent_email.required' => 'Vul het e-mailadres van de ouder in.',
            'parent_email.email' => 'Voer een geldig e-mailadres voor de ouder in.',
            'parent_email.max' => 'Het e-mailadres van de ouder mag niet langer zijn dan 255 tekens.',
            'parent_password.required' => 'Vul het wachtwoord van de ouder in.',
            'parent_phone_number.max' => 'Het telefoonnummer van de ouder mag niet langer zijn dan 20 tekens.',
            'parent_postal_code.max' => 'De postcode van de ouder mag niet langer zijn dan 4 cijfers',
            'parent_postal_code.numeric' => 'De postcode van de ouder moet cijfers bevatten.',
            'parent_street_number.max' => 'Het adres van de ouder mag niet langer zijn dan 255 tekens.',
            'parent_municipality.max' => 'De gemeente van de ouder mag niet langer zijn dan 255 tekens.',

            'addSecondParent.in' => 'Geef aan of er een tweede ouder moet worden toegevoegd.',
            'second_parent_date_of_birth.required_if' => 'Vul de geboortedatum van de tweede ouder in als deze wordt toegevoegd.',
            'second_parent_date_of_birth.date' => 'Voer een geldige geboortedatum voor de tweede ouder in.',
            'second_parent_date_of_birth.past_date' => 'De geboortedatum van de tweede ouder moet in het verleden liggen.',
            'second_parent_first_name.required_if' => 'Vul de voornaam van de tweede ouder in als deze wordt toegevoegd.',
            'second_parent_first_name.max' => 'De voornaam van de tweede ouder mag niet langer zijn dan 255 tekens.',
            'second_parent_last_name.required_if' => 'Vul de achternaam van de tweede ouder in als deze wordt toegevoegd.',
            'second_parent_last_name.max' => 'De achternaam van de tweede ouder mag niet langer zijn dan 255 tekens.',
            'second_parent_email.required_if' => 'Vul het e-mailadres van de tweede ouder in als deze wordt toegevoegd.',
            'second_parent_email.email' => 'Voer een geldig e-mailadres voor de tweede ouder in als deze wordt toegevoegd.',
            'second_parent_email.max' => 'Het e-mailadres van de tweede ouder mag niet langer zijn dan 255 tekens.',
            'second_parent_phone_number.required_if' => 'Vul het telefoonnummer van de tweede ouder in als deze wordt toegevoegd.',
            'second_parent_phone_number.max' => 'Het telefoonnummer van de tweede ouder mag niet langer zijn dan 20 tekens.',
            'second_parent_postal_code.required_if' => 'Vul de postcode van de tweede ouder in als deze wordt toegevoegd.',
            'second_parent_postal_code.max' => 'De postcode van de tweede ouder moet een 4 cijferige getal zijn.',
            'second_parent_postal_code.numeric' => 'De postcode van de tweede ouder moet cijfers bevatten.',
            'second_parent_street_number.required_if' => 'Vul het adres van de tweede ouder in als deze wordt toegevoegd.',
            'second_parent_street_number.max' => 'Het adres van de tweede ouder mag niet langer zijn dan 255 tekens.',
            'second_parent_municipality.required_if' => 'Vul de gemeente van de tweede ouder in als deze wordt toegevoegd.',
            'terms.accepted' => 'Accepteer de voorwaarden en privacybeleid.',
        ];

        $validatedData = Validator::make($input, [
            // Child user validation rules
            'child_first_name' => ['required', 'string', 'max:255'],
            'child_last_name' => ['required', 'string', 'max:255'],
            'child_date_of_birth' => ['required', 'date', 'past_date'],
            'child_gender' => ['required', 'integer'],
            'size_id' => ['required', 'exists:sizes,id'],

            // Parent user validation rules
            'parent_gender' => ['required', 'integer'],
            'parent_date_of_birth' => ['required', 'date', 'past_date'],
            'parent_first_name' => ['required', 'string', 'max:255'],
            'parent_last_name' => ['required', 'string', 'max:255'],
            'parent_email' => ['required', 'string', 'email', 'max:255'],
            'parent_password' => $this->passwordRules(),
            'parent_phone_number' => ['nullable','string', 'max:20'],
            'parent_postal_code' => ['numeric', 'digits:4'],
            'parent_street_number' => ['string', 'max:255'],
            'parent_municipality' => ['string', 'max:255'],


            'addSecondParent' => ['nullable', 'in:on'], // Checkbox indicating second parent addition
            'second_parent_date_of_birth' => ['nullable', 'required_if:addSecondParent,on', 'date', 'past_date'],
            'second_parent_gender' => [
                'nullable',
                function ($attribute, $value, $fail) use ($input) {
                    if (isset($input['addSecondParent']) && $input['addSecondParent'] === 'on' && !is_numeric($value)) {
                        $fail('The second parent gender field must be an integer.');
                    }
                }
            ],
            'second_parent_first_name' => ['nullable', 'required_if:addSecondParent,on', 'string', 'max:255'],
            'second_parent_last_name' => ['nullable', 'required_if:addSecondParent,on', 'string', 'max:255'],
            'second_parent_email' => ['nullable', 'required_if:addSecondParent,on', 'email', 'max:255'],
            'second_parent_phone_number' => ['nullable', 'required_if:addSecondParent,on', 'string', 'max:20'],
            'second_parent_postal_code' => ['nullable', 'required_if:addSecondParent,on', 'numeric', 'digits:4'],
            'second_parent_street_number' => ['nullable', 'required_if:addSecondParent,on', 'string', 'max:255'],
            'second_parent_municipality' => ['nullable', 'required_if:addSecondParent,on', 'string', 'max:255'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',

        ], $customMessages)->validate();




        // Create the child user
        $child = User::create([
            'first_name' => $validatedData['child_first_name'],
            'last_name' => $validatedData['child_last_name'],
            'name' => $validatedData['child_last_name'], // Concatenating first name and last name to create the full name
            'date_of_birth' => $validatedData['child_date_of_birth'],
            'gender_id' => $validatedData['child_gender'], // Assuming 'child_gender' contains the gender ID
            'password' => Hash::make($validatedData['parent_password']), // Assuming child and parent have the same password
            'phone_number' => $validatedData['parent_phone_number'],
            'postal_code' => $validatedData['parent_postal_code'],
            'street_number' => $validatedData['parent_street_number'],
            'municipality' => $validatedData['parent_municipality'],
            'permission_photos' => $permissionPhotos, // Capture the permission status
            'role_id' => 2,
            'is_registered' => 1
        ]);

        // Create the parent user
        $parent = User::create([
            'first_name' => $validatedData['parent_first_name'],
            'last_name' => $validatedData['parent_last_name'],
            'name' => $validatedData['parent_last_name'], // Concatenating first name and last name to create the full name
            'email' => $validatedData['parent_email'],
            'gender_id' => $validatedData['parent_gender'],
            'date_of_birth' => $validatedData['parent_date_of_birth'],
            'password' => Hash::make($validatedData['parent_password']),
            'phone_number' => $validatedData['parent_phone_number'],
            'postal_code' => $validatedData['parent_postal_code'],
            'street_number' => $validatedData['parent_street_number'],
            'municipality' => $validatedData['parent_municipality'],
            'permission_photos' => $permissionPhotos, // Capture the permission status
            'role_id' => 2,
            'is_registered' => 1

        ]);

        // Generate a temporary password for the second parent
        $temporaryPassword = bin2hex(random_bytes(8)); // Generates an 16-character hexadecimal string

        $hashedTemporaryPassword = bcrypt($temporaryPassword); // Hash the temporary password

        $secondParentId = null;

        if (isset($validatedData['addSecondParent']) && $validatedData['addSecondParent'] === 'on') {
            // Create the second parent user
            $second_parent = User::create([
                'first_name' => $validatedData['second_parent_first_name'],
                'last_name' => $validatedData['second_parent_last_name'],
                'name' => $validatedData['second_parent_last_name'], // Concatenating first name and last name to create the full name
                'email' => $validatedData['second_parent_email'],
                'gender_id' => $validatedData['second_parent_gender'], // Ensure that 'gender_id' is provided
                'date_of_birth' => $validatedData['second_parent_date_of_birth'],
                'password' => $hashedTemporaryPassword,
                'phone_number' => $validatedData['second_parent_phone_number'],
                'postal_code' => $validatedData['second_parent_postal_code'],
                'street_number' => $validatedData['second_parent_street_number'],
                'municipality' => $validatedData['second_parent_municipality'],
                'permission_photos' => $permissionPhotos, // Capture the permission status
                'role_id' => 2,
                'is_secondParent' => true,
                'is_registered' => 1,
                'email_verified_at' => now()
            ]);

            // Retrieve the second parent ID if it has been created
            $secondParentId = $second_parent->id;

            // Dispatch the event with the password
            event(new SecondParentActivated($second_parent, $temporaryPassword));
        }



    // Retrieve the foreign key values
        $childId = $child->id;
        $parentId = $parent->id;

        ClothingPerPlayer::create([
            'user_id' => $childId,
            'clothing_size_id' => $validatedData['size_id']
        ]);

        // Create entries in the related table using foreign key values
        $parent_per_child = ParentPerChild::create([
            'user_child_id' => $childId,
            'user_parent_id' => $parentId,
        ]);

        // Check if second parent information is provided
        if ($secondParentId) {
            // If second parent information is provided, create entry in the related table for the second parent
            $second_parent_per_child = ParentPerChild::create([
                'user_child_id' => $childId,
                'user_parent_id' => $secondParentId,
            ]);
        }
        return $parent; // Return the parent user
    }

}
