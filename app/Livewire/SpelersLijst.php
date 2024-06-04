<?php

namespace App\Livewire;

use App\Models\ParentPerChild;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;


class SpelersLijst extends Component
{
    public $search = '';

    private static function fetchNames($childparents)
    {
        $childParentsMap = [];

        foreach ($childparents as $childparent) {
            $childId = $childparent->user_child_id;
            $parentId = $childparent->user_parent_id;

            if (!isset($childParentsMap[$childId]['child_name'])) {
                $child = User::find($childId);
                $childParentsMap[$childId]['child_name'] = $child ? $child->first_name . " " . $child->name : 'Unknown Child';
            }

            $parent = User::find($parentId);
            $parentName = $parent ? $parent->first_name . " " . $parent->name : 'Unknown Parent';

            $childParentsMap[$childId]['parent_names'][] = $parentName;
        }

        $childparents = [];
        foreach ($childParentsMap as $childId => $data) {
            $childName = $data['child_name'];
            $parentNames = implode(', ', $data['parent_names']);
            $childparents[] = (object) [
                'child_name' => $childName,
                'parent_names' => $parentNames
            ];
        }

        return $childparents;
    }

    #[Layout('layouts.app', ['title' => 'Spelers', 'description' => 'Bekijk al de spelers',
        'developer' => 'Illias Latifine'])]
    public function render()
    {
        $childparentsQuery = ParentPerChild::orderBy('id');

        if ($this->search) {
            $childparentsQuery->whereHas('child', function($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('name', 'like', '%' . $this->search . '%');
            });
        }

        $childparents = $childparentsQuery->get();
        $childparents = self::fetchNames($childparents);

        return view('livewire.spelers-lijst', compact('childparents'));
    }
}
