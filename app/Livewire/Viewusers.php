<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Viewusers extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'first_name';
    public $sortDirection = 'asc';
    public $roleFilter = '';
    public $statusFilter = '';
    public $showDeleteModal = false;
    public $userIdToDelete = null;

    public function setStatusFilter($status)
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function render()
    {
        $currentUserId = Auth::id(); // Get the ID of the currently authenticated user

        $users = User::query()
            ->where('id', '!=', $currentUserId) // Exclude the current user
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('first_name', 'like', '%'.$this->search.'%')
                      ->orWhere('last_name', 'like', '%'.$this->search.'%')
                      ->orWhere('email', 'like', '%'.$this->search.'%')
                      ->orWhere('phone', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->roleFilter, function ($query) {
                $query->where('role', $this->roleFilter);
            })
            ->when($this->statusFilter, function ($query) {
                if ($this->statusFilter === 'active') {
                    $query->where('is_active', true);
                } elseif ($this->statusFilter === 'banned') {
                    $query->where('is_banned', true);
                }
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.viewusers', [
            'users' => $users,
            'roles' => ['admin', 'it-person', 'staff']
        ]);
    }

    public function confirmDelete($userId)
    {
        $this->userIdToDelete = $userId;
        $this->showDeleteModal = true;
        $this->dispatch('showDeleteModal'); // Dispatch event to show modal
    }

    public function deleteUser()
    {
        if ($this->userIdToDelete == Auth::id()) {
            $this->addError('delete', 'You cannot delete your own account.');
            $this->hideDeleteModal();
            return;
        }

        $user = User::find($this->userIdToDelete);
        if ($user) {
            $user->delete();
            $this->hideDeleteModal(); // This will close the modal
            session()->flash('message', 'User deleted successfully.');

            // Refresh the user list (optional)
            $this->resetPage();
        }
    }
    public function viewUser($userId)
{
    return redirect()->route('userdetails', $userId);
}

    public function closeModal()
    {
        $this->showDeleteModal = false;
        $this->userIdToDelete = null;
        $this->dispatch('hideDeleteModal');
    }

    protected $listeners = [
        'showDeleteModal' => 'showDeleteModal',
        'hideDeleteModal' => 'hideDeleteModal',
    ];

    public function showDeleteModal()
    {
        $this->showDeleteModal = true;
    }

    public function hideDeleteModal()
    {
        $this->showDeleteModal = false;
    }

}
