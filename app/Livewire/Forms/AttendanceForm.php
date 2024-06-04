<?php

namespace App\Livewire\Forms;

use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AttendanceForm extends Form
{
    public $id = null;
    #[Validate('required', as: 'kind')]
    public $user_id = null;
    #[Validate('required', as: 'activiteit')]
    public $activity_id = null;
    public $is_announced_absent = null;
    public $reason = null;
    public $unannounced_absent = null;

    // read the selected attendance
    public function read($attendance)
    {
        $this->id = $attendance->id;
        $this->user_id = $attendance->user_id;
        $this->activity_id = $attendance->activity_id;
        $this->is_announced_absent = $attendance->is_announced_absent;
        $this->reason = $attendance->reason;
        $this->unannounced_absent = $attendance->unannounced_absent;
    }

    // create a new attendance
    public function create()
    {
        $this->validate();
//        dd($this->activity_id, $this->reason);
        Attendance::create([
            'user_id' => $this->user_id,
            'activity_id' => $this->activity_id,
            'is_announced_absent' => 1,
            'reason' => $this->reason,
            'unannounced_absent' => 0,
        ]);
    }

    // update an existing attendance
    public function update(Attendance $attendance)
    {
        $this->validate();
        $attendance->update([
            'user_id' => $this->user_id,
            'activity_id' => $this->activity_id,
            'is_announced_absent' => $this->is_announced_absent,
            'reason' => $this->reason,
            'unannounced_absent' => $this->unannounced_absent,
        ]);
    }

    // delete an existing attendance
    public function delete($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();
    }
}
