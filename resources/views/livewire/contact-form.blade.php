<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div>
        <form wire:submit.prevent="sendEmail()">
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2 md:col-span-1">
                    <x-label for="name" value="Naam"/>
                    <x-input type="text" id="name" name="name" class="block mt-1 w-full"
                             wire:model.live="name"
                             placeholder="Uw naam"/>
                    <x-input-error for="name" class="mt-2"/>
                </div>
                <div class="col-span-2 md:col-span-1">
                    <x-label for="email" value="E-mail"/>
                    <x-input type="email" id="email" name="email" placeholder="Uw e-mail"
                             wire:model.blur="email"
                             class="block mt-1 w-full"/>
                    <x-input-error for="email" class="mt-2"/>
                </div>
                <div class="col-span-2">
                    <x-label for="message" value="Bericht"/>
                    <textarea id="message" name="message" rows="5" placeholder="Uw bericht"
                              wire:model.live.debounce.1000ms="message"
                              class="block mt-1 w-full border border-gray-300 rounded-md"></textarea>
                    <x-input-error for="message" class="mt-2"/>
                </div>
                <div class="col-span-2">
                    <x-button type="submit">
                        Verstuur bericht
                    </x-button>
                </div>
            </div>
        </form>
    </div>

    <div>
        <h3 class="text-xl font-semibold mb-3">Clubinformatie</h3>
        <p class="mb-2"><strong>Adres:</strong> Blekestraat 40, 2400 Mol, België</p>
        <p class="mb-2"><strong>Telefoon:</strong> 014 81 66 33</p>
        <p class="mb-2"><strong>Email:</strong> info@kvvrauw.be</p>
        <h3 class="text-xl font-semibold mb-3 mt-4">Locatie</h3>
        <iframe class="w-full h-64" src="https://www.google.com/maps/embed/v1/place?q=Kvv+Rauw+Sport+Mol,+Blekestraat+40,+2400+Mol,+België&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8" allowfullscreen="" loading="lazy"></iframe>
    </div>
</div>
