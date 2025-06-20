<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MaintenanceSchedule;
use App\Models\Device;
use App\Models\User;
use App\Models\MaintenanceNotification;
use Illuminate\Support\Facades\Auth;

class Mantainance extends Component
{
    use WithPagination;

    // Form properties
    public $title = '';
    public $maintenance_type = 'preventive';
    public $description = '';
    public $status = 'scheduled';
    public $start_date = '';
    public $completion_date = '';
    public $attachment_path = null;

    // UI state properties
    public $statusFilter = '';
    public $sortField = 'scheduled_date';
    public $sortDirection = 'desc';
    public $currentTask = null;
    public $taskToDelete = null;
    public $showNotificationModal = false;
    public $notificationType = 'reminder';
    public $notificationSendAt = '';

    protected $rules = [
        'title' => 'required|string|max:255',
        'maintenance_type' => 'required|in:preventive,corrective,predictive,emergency',
        'description' => 'required|string',
        'status' => 'required|in:scheduled,in_progress,completed,cancelled',
        'start_date' => 'nullable|date',
        'completion_date' => 'nullable|date',
        'attachment_path' => 'nullable|file|max:10240', // 10MB max
    ];

    public function mount()
    {
        $this->scheduled_date = now()->format('Y-m-d\TH:i');
    }

    public function render()
    {
        $query = MaintenanceSchedule::orderBy($this->sortField, $this->sortDirection);

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        return view('livewire.mantainance', [
            'maintenanceTasks' => $query->paginate(10),
            'maintenanceTypes' => [
                'preventive' => 'Preventive',
                'corrective' => 'Corrective',
                'predictive' => 'Predictive',
                'emergency' => 'Emergency'
            ],
            'statuses' => [
                'scheduled' => 'Scheduled',
                'in_progress' => 'In Progress',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled'
            ]
        ]);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function setStatusFilter($status)
    {
        $this->statusFilter = $status === $this->statusFilter ? '' : $status;
        $this->resetPage();
    }

    public function saveMaintenance()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'type' => $this->maintenance_type,
            'description' => $this->description,
            'status' => $this->status,
            'start_time' => $this->start_date,
            'end_time' => $this->completion_date,
            'created_by' => Auth::id(),
        ];

        if ($this->currentTask) {
            $this->currentTask->update($data);
            $message = 'Maintenance task updated successfully!';
        } else {
            $this->currentTask = MaintenanceSchedule::create($data);
            $message = 'Maintenance task created successfully!';
        }

        $this->resetForm();
        $this->dispatch('hide-modal');
        session()->flash('message', $message);
    }

    public function viewTask($taskId)
    {
        $this->currentTask = MaintenanceSchedule::find($taskId);
        $this->dispatch('show-view-modal');
    }

    public function editTask($taskId)
    {
        $this->currentTask = MaintenanceSchedule::find($taskId);
        $this->title = $this->currentTask->title;
        $this->maintenance_type = $this->currentTask->maintenance_type;
        $this->description = $this->currentTask->description;
        $this->status = $this->currentTask->status;
        $this->start_date = $this->currentTask->start_date?->format('Y-m-d\TH:i');
        $this->completion_date = $this->currentTask->completion_date?->format('Y-m-d\TH:i');
        $this->dispatch('show-edit-modal');
    }

    public function startTask($taskId)
    {
        $task = MaintenanceSchedule::find($taskId);
        $task->update([
            'status' => 'in_progress',
            'start_date' => now()
        ]);
        session()->flash('message', 'Maintenance task started!');
    }

    public function completeTask($taskId)
    {
        $task = MaintenanceSchedule::find($taskId);
        $task->update([
            'status' => 'completed',
            'completion_date' => now(),
        ]);
        session()->flash('message', 'Maintenance task completed!');
    }

    public function cancelTask($taskId)
    {
        $task = MaintenanceSchedule::find($taskId);
        $task->update(['status' => 'cancelled']);
        session()->flash('message', 'Maintenance task cancelled!');
    }

    public function confirmDelete($taskId)
    {
        $this->taskToDelete = $taskId;
        $this->dispatch('show-delete-modal');
    }

    public function deleteTask()
    {
        MaintenanceSchedule::find($this->taskToDelete)->delete();
        $this->dispatch('hide-delete-modal');
        session()->flash('message', 'Maintenance task deleted successfully!');
    }

    public function resetForm()
    {
        $this->reset([
            'title', 'maintenance_type', 'description',
            'status', 'scheduled_date', 'start_date', 'completion_date',
            'currentTask', 'attachment_path'
        ]);
        $this->resetErrorBag();
        $this->scheduled_date = now()->format('Y-m-d\TH:i');
    }

    public function getStatusColor($status)
    {
        return match($status) {
            'scheduled' => 'info',
            'in_progress' => 'primary',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

    public function getTypeColor($type)
    {
        return match($type) {
            'preventive' => 'primary',
            'corrective' => 'warning',
            'predictive' => 'info',
            'emergency' => 'danger',
            default => 'secondary'
        };
    }
}
