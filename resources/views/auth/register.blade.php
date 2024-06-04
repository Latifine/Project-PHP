<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <h1 class="m-4 text-center">Gegevens Kind</h1>
            <div class="grid grid-cols-2 gap-4">
                <!-- Child Information -->
                <div class="mt-4">
                    <x-label for="first_name" value="{{ __('Voornaam') }}"></x-label>
                    <x-input id="first_name" class="block mt-1 w-full" type="text" name="child_first_name" :value="old('child_first_name')"
                             required autofocus autocomplete="given-name" />
                </div>

                <div class="mt-4">
                    <x-label for="name" value="{{ __('Naam') }}"></x-label>
                    <x-input id="name" class="block mt-1 w-full" type="text" name="child_last_name" :value="old('child_last_name')"
                             required autocomplete="family-name" />
                </div>

                <div class="mt-4">
                    <x-label for="date_of_birth" value="{{ __('Geboortedatum') }}"></x-label>
                    <x-input id="date_of_birth" class="block mt-1 w-full" type="date" name="child_date_of_birth" :value="old('child_date_of_birth')" required />
                </div>

                <div class="mt-4">
                    <x-label for="size" value="{{ __('Maat') }}"></x-label>
                    <x-tmk.form.select id="size" name="size_id" class="block mt-1 w-full">
                        <option value="">Selecteer Maat</option>
                        @foreach($sizes as $size)
                            <option value="{{ $size->id }}" {{ $size->id == old('size_id') ? 'selected' : '' }}>
                                {{ $size->size }}
                            </option>
                        @endforeach
                    </x-tmk.form.select>
                </div>

                <div class="mt-4">
                    <x-label for="gender" value="{{ __('Geslacht') }}"></x-label>
                    <x-tmk.form.select id="child_gender" class="block mt-1 w-full" name="child_gender" required>
                        <option value="%">Selecteer Geslacht</option>
                        @foreach($genders as $gender)
                            <option value="{{ $gender->id }}" {{ $gender->id == old('child_gender_id') ? 'selected' : '' }}>
                                {{ $gender->gender }}
                            </option>
                        @endforeach
                    </x-tmk.form.select>
                </div>
            </div>

            <hr class="mt-4">
            <h1 class="m-4 text-center">Gegevens Ouder</h1>

            <div class="grid grid-cols-2 gap-4">
                <div class="mt-4">
                    <x-label for="parent_first_name" value="{{ __('Voornaam') }}"></x-label>
                    <x-input id="parent_first_name" class="block mt-1 w-full" type="text" name="parent_first_name" :value="old('parent_first_name')"
                             required autofocus autocomplete="given-name"></x-input>
                </div>

                <div class="mt-4">
                    <x-label for="parent_last_name" value="{{ __('Naam') }}"></x-label>
                    <x-input id="parent_last_name" class="block mt-1 w-full" type="text" name="parent_last_name" :value="old('parent_last_name')"
                             required autocomplete="family-name"></x-input>
                </div>

                <div class="mt-4">
                    <x-label for="parent_street_number" value="{{ __('Straat en huisnummer') }}"></x-label>
                    <x-input id="parent_street_number" class="block mt-1 w-full" type="text" name="parent_street_number" :value="old('parent_street_number')" required></x-input>
                </div>

                <div class="mt-4">
                    <x-label for="parent_postal_code" value="{{ __('Postcode') }}"></x-label>
                    <x-input id="parent_postal_code" class="block mt-1 w-full" type="text" name="parent_postal_code" :value="old('parent_postal_code')" required></x-input>
                </div>

                <div class="mt-4">
                    <x-label for="parent_municipality" value="{{ __('Gemeente') }}"></x-label>
                    <x-input id="parent_municipality" class="block mt-1 w-full" type="text" name="parent_municipality" :value="old('parent_municipality')" required></x-input>
                </div>

                <div class="mt-4">
                    <x-label for="parent_phone_number" value="{{ __('Telefoonnummer') }}"></x-label>
                    <x-input id="parent_phone_number" class="block mt-1 w-full" type="text" name="parent_phone_number" :value="old('parent_phone_number')"></x-input>
                </div>

                <div class="mt-4">
                    <x-label for="date_of_birth" value="{{ __('Geboortedatum') }}"></x-label>
                    <x-input id="date_of_birth" class="block mt-1 w-full" type="date" name="parent_date_of_birth" :value="old('parent_date_of_birth')" required />
                </div>

                <div class="mt-4">
                    <x-label for="gender" value="{{ __('Geslacht') }}"></x-label>
                    <x-tmk.form.select id="parent_gender" class="block mt-1 w-full" name="parent_gender" required>
                        <option value="%">Selecteer Geslacht</option>
                        @foreach($genders as $gender)
                            <option value="{{ $gender->id }}" {{ $gender->id == old('parent_gender_id') ? 'selected' : '' }}>
                                {{ $gender->gender }}
                            </option>
                        @endforeach
                    </x-tmk.form.select>
                </div>
            </div>



            <div class="mt-4">
                <x-label for="parent_email" value="{{ __('E-mail') }}"></x-label>
                <x-input id="parent_email" class="block mt-1 w-full" type="email" name="parent_email" :value="old('parent_email')" required autocomplete="username"></x-input>
            </div>

            <div class="mt-4">
                <x-label for="parent_password" value="{{ __('Wachtwoord') }}"></x-label>
                <x-input id="parent_password" class="block mt-1 w-full" type="password" name="parent_password" required autocomplete="new-password"></x-input>
            </div>

            <div class="mt-4">
                <x-label for="parent_password_confirmation" value="{{ __('Bevestig Wachtwoord') }}"></x-label>
                <x-input id="parent_password_confirmation" class="block mt-1 w-full" type="password" name="parent_password_confirmation" required autocomplete="new-password"></x-input>
            </div>

            <div class="mt-2">
                <x-checkbox id="permission_photos" name="permission_photos"></x-checkbox>
                <label for="permission_photos">Ik geef toestemming foto's te publiceren van mijn zoon/dochter </label>
            </div>


            <hr class="mt-4">

            <div class="mt-2">
                <x-checkbox id="addSecondParent" value="{{ __('1') }}" :value="old('addSecondParent')" name="addSecondParent" onchange="toggleSecondParentSection()"></x-checkbox>
                <label for="addSecondParent">Tweede ouder toevoegen</label>
            </div>

            <div id="secondParentSection" style="display: none;">
                <h1 class="m-4 text-center">Gegevens tweede ouder</h1>

                <div class="grid grid-cols-2 gap-4">
                    <div class="mt-4">
                        <x-label for="second_parent_first_name" value="{{ __('Voornaam') }}" />
                        <x-input id="second_parent_first_name" class="block mt-1 w-full" type="text" name="second_parent_first_name" :value="old('second_parent_first_name')"
                                  autofocus autocomplete="given-name" />
                    </div>

                    <div class="mt-4">
                        <x-label for="second_parent_last_name" value="{{ __('Naam') }}" />
                        <x-input id="second_parent_last_name" class="block mt-1 w-full" type="text" name="second_parent_last_name" :value="old('second_parent_last_name')"
                                  autocomplete="family-name" />
                    </div>

                    <div class="mt-4">
                        <x-label for="second_parent_street_number" value="{{ __('straat en huisnummer') }}" />
                        <x-input id="second_parent_street_number" class="block mt-1 w-full" type="text" name="second_parent_street_number" :value="old('second_parent_street_number')"  />
                    </div>

                    <div class="mt-4">
                        <x-label for="second_parent_postal_code" value="{{ __('Postcode') }}" />
                        <x-input id="second_parent_postal_code" class="block mt-1 w-full" type="text" name="second_parent_postal_code" :value="old('second_parent_postal_code')"  />
                    </div>

                    <div class="mt-4">
                        <x-label for="second_parent_municipality" value="{{ __('Gemeente') }}" />
                        <x-input id="second_parent_municipality" class="block mt-1 w-full" type="text" name="second_parent_municipality" :value="old('second_parent_municipality')"  />
                    </div>

                    <div class="mt-4">
                        <x-label for="second_parent_phone_number" value="{{ __('Telefoonnummer') }}" />
                        <x-input id="second_parent_phone_number" class="block mt-1 w-full" type="text" name="second_parent_phone_number" :value="old('second_parent_phone_number')" />
                    </div>

                    <div class="mt-4">
                        <x-label for="date_of_birth" value="{{ __('Geboortedatum') }}"></x-label>
                        <x-input id="date_of_birth" class="block mt-1 w-full" type="date" name="second_parent_date_of_birth" :value="old('second_parent_date_of_birth')"  />
                    </div>

                    <div class="mt-4">
                        <x-label for="gender" value="{{ __('Geslacht') }}"></x-label>
                        <x-tmk.form.select id="second_parent_gender" class="block mt-1 w-full" name="second_parent_gender" >
                            <option value="%">Selecteer Geslacht</option>
                            @foreach($genders as $gender)
                                <option value="{{ $gender->id }}" {{ $gender->id == old('second_parent_gender_id') ? 'selected' : '' }}>
                                    {{ $gender->gender }}
                                </option>
                            @endforeach
                        </x-tmk.form.select>
                    </div>
                </div>

                <div class="mt-4">
                    <x-label for="second_parent_email" value="{{ __('E-mailadres') }}" />
                    <x-input id="second_parent_email" class="block mt-1 w-full" type="email" name="second_parent_email" :value="old('second_parent_email')"  autocomplete="username" />
                </div>
            </div>

            <script>
                function toggleSecondParentSection() {
                    var checkBox = document.getElementById("addSecondParent");
                    var secondParentSection = document.getElementById("secondParentSection");
                    if (checkBox.checked == true) {
                        secondParentSection.style.display = "block";
                    } else {
                        secondParentSection.style.display = "none";
                    }
                }
            </script>


        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required></x-checkbox>

                            <div class="ms-2">
                                {!! __('Ik ga akkoord met de :terms_of_service en :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Algemene Voorwaarden').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacybeleid').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('Al geregistreerd?') }}
                </a>

                <x-button class="ms-4">
                    {{ __('Registreer') }}
                </x-button>
            </div>
        </form>

    </x-authentication-card>
</x-guest-layout>
