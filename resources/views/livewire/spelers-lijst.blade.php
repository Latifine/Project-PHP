<div>

    <x-tmk.section class="mb-4 flex gap-2">
        <div class="flex-1 items-center">
            <x-input id="search" type="text" placeholder="Naam kind"
                     wire:model.live.debounce.500ms="search"
                     class="w-full h-12 shadow-md placeholder-gray-300"/>
        </div>


    </x-tmk.section>


    <x-tmk.section>
        <table class="text-center w-full border border-gray-300">
            <colgroup>
                <col class="">
                <col class="">
            </colgroup>
            <thead>
            <tr class="bg-gray-100 text-gray-700 ">
                <th>Kind</th>
                <th>Ouder(s)</th>
            </tr>
            </thead>
            <tbody>
            @forelse($childparents as $childparent)
                <tr class="border-t border-gray-300 [&>td]:p-2">
                    <td>{{ $childparent->child_name }}</td>
                    <td>{{ $childparent->parent_names }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="border-t border-gray-300 p-4 text-center text-gray-500">
                        <div class="font-bold italic text-sky-800">Geen spelers gevonden</div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </x-tmk.section>
</div>
