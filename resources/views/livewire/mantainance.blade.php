<div>
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Maintenance Management</h4>
                <h6>Schedule and track maintenance tasks</h6>
            </div>
            <div class="page-btn">
                <button class="btn btn-added" wire:click="$set('currentTask', null)" data-bs-toggle="modal"
                    data-bs-target="#editModal">
                    <i class="fas fa-plus me-1"></i> Schedule Maintenance
                </button>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="btn-group" role="group">
                            <button type="button"
                                class="btn btn-outline-secondary @if ($statusFilter === '') active @endif"
                                wire:click="setStatusFilter('')">All</button>
                            @foreach ($statuses as $key => $status)
                                <button type="button"
                                    class="btn btn-outline-secondary @if ($statusFilter === $key) active @endif"
                                    wire:click="setStatusFilter('{{ $key }}')">{{ $status }}</button>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-6">

                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th wire:click="sortBy('title')" style="cursor: pointer;">
                                    Task
                                    @if ($sortField === 'title')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th wire:click="sortBy('maintenance_type')" style="cursor: pointer;">
                                    Type
                                    @if ($sortField === 'maintenance_type')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th>Start Date</th>
                                <th wire:click="sortBy('scheduled_date')" style="cursor: pointer;">
                                    Scheduled
                                    @if ($sortField === 'scheduled_date')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>

                                <th wire:click="sortBy('status')" style="cursor: pointer;">
                                    Status
                                    @if ($sortField === 'status')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($maintenanceTasks as $task)
                                <tr>
                                    <td>{{ $task->title }}</td>
                                    <td>
                                        <span class="badge bg-{{ $this->getTypeColor($task->maintenance_type) }}">
                                            {{ $maintenanceTypes[$task->type] ?? $task->type }}
                                        </span>
                                    </td>
                                    <td>{{$task->start_time }}</td>
                                    <td>
                                        {{-- {{ $task->scheduled_date->format('M d, Y h:i A') }} --}}
                                        @if ($task->start_date)
                                            <br><small class="text-muted">Started:
                                                {{ $task->end_time }}</small>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="badge bg-{{ $this->getStatusColor($task->status) }}">
                                            {{ $statuses[$task->status] ?? $task->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info"
                                                wire:click="viewTask({{ $task->id }})" data-bs-toggle="modal"
                                                data-bs-target="#viewModal">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary"
                                                wire:click="editTask({{ $task->id }})" data-bs-toggle="modal"
                                                data-bs-target="#editModal">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @if ($task->status === 'scheduled')
                                                <button class="btn btn-sm btn-success"
                                                    wire:click="startTask({{ $task->id }})">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                            @elseif($task->status === 'in_progress')
                                                <button class="btn btn-sm btn-success"
                                                    wire:click="completeTask({{ $task->id }})">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                            <button class="btn btn-sm btn-danger"
                                                wire:click="confirmDelete({{ $task->id }})" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No maintenance tasks found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $maintenanceTasks->links() }}
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Maintenance Task Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($currentTask)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Title</label>
                                    <p class="form-control-plaintext">{{ $currentTask->title }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Type</label>
                                    <p class="form-control-plaintext">
                                        <span
                                            class="badge bg-{{ $this->getTypeColor($currentTask->maintenance_type) }}">
                                            {{ $maintenanceTypes[$currentTask->type] ?? $currentTask->type }}
                                        </span>
                                    </p>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge bg-{{ $this->getStatusColor($currentTask->status) }}">
                                            {{ $statuses[$currentTask->status] ?? $currentTask->status }}
                                        </span>
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Scheduled Date</label>
                                    <p class="form-control-plaintext">
                                        {{ $currentTask->start_time }}</p>
                                </div>
                                    <div class="mb-3">
                                        <label class="form-label">End Date</label>
                                        <p class="form-control-plaintext">
                                            {{ $currentTask->end_time }}</p>
                                    </div>
                                    @if ($currentTask->start_time && $currentTask->end_time)
                                    <div class="mb-3">
                                        <label class="form-label">Maintenance Duration</label>
                                        <p class="form-control-plaintext">
                                            @php
                                                $start = \Carbon\Carbon::parse($currentTask->start_time);
                                                $end = \Carbon\Carbon::parse($currentTask->end_time);
                                                $duration = $start->diff($end);
                                            @endphp

                                            @if ($duration->d > 0)
                                                {{ $duration->d }} day{{ $duration->d > 1 ? 's' : '' }}
                                            @endif

                                            @if ($duration->h > 0)
                                                {{ $duration->h }} hour{{ $duration->h > 1 ? 's' : '' }}
                                            @endif

                                            @if ($duration->i > 0 && $duration->d == 0)
                                                {{ $duration->i }} minute{{ $duration->i > 1 ? 's' : '' }}
                                            @endif

                                            ({{ $start->format('M d, Y') }} to {{ $end->format('M d, Y') }})
                                        </p>
                                    </div>
                                @elseif ($currentTask->start_time)
                                    <div class="mb-3">
                                        <label class="form-label">Started On</label>
                                        <p class="form-control-plaintext">
                                            {{ $currentTask->start_time->format('M d, Y h:i A') }}
                                            (In progress)
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <p class="form-control-plaintext">{{ $currentTask->description }}</p>
                                </div>
                                @if ($currentTask->solution)
                                    <div class="mb-3">
                                        <label class="form-label">Solution</label>
                                        <p class="form-control-plaintext">{{ $currentTask->solution }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Notifications Section -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6>Scheduled Notifications</h6>
                                <button class="btn btn-sm btn-primary float-end"
                                    wire:click="showNotificationModal('reminder')" data-bs-toggle="modal"
                                    data-bs-target="#notificationModal">
                                    <i class="fas fa-plus"></i> Add Notification
                                </button>
                            </div>
                            <div class="card-body">
                                @if ($currentTask->notifications->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Type</th>
                                                    <th>Scheduled Time</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($currentTask->notifications as $notification)
                                                    <tr>
                                                        <td>{{ ucfirst($notification->type) }}</td>
                                                        <td>{{ $notification->send_at }}</td>
                                                        <td>
                                                            <span
                                                                class="badge bg-{{ $notification->is_sent ? 'success' : 'warning' }}">
                                                                {{ $notification->is_sent ? 'Sent' : 'Pending' }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-danger">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted">No notifications scheduled for this task.</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit Maintenance Task Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $currentTask ? 'Edit' : 'Create' }} Maintenance Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click="resetForm"></button>
                </div>
                <form wire:submit.prevent="saveMaintenance">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" wire:model="title">
                                    @error('title')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Maintenance Type <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" wire:model="maintenance_type">
                                        @foreach ($maintenanceTypes as $key => $type)
                                            <option value="{{ $key }}">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                    @error('maintenance_type')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select" wire:model="status">
                                        @foreach ($statuses as $key => $status)
                                            <option value="{{ $key }}">{{ $status }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Scheduled Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" wire:model="start_date">
                                    @error('start_date') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Completion Date</label>
                                    <input type="date" class="form-control" wire:model="completion_date">
                                    @error('completion_date') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control" rows="3" wire:model="description"></textarea>
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            wire:click="resetForm">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this maintenance task? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteTask"
                        data-bs-dismiss="modal">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Notification Schedule Modal -->
    <div class="modal fade" id="notificationModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Schedule Notification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="scheduleNotification">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Notification Type</label>
                            <select class="form-select" wire:model="notificationType">
                                <option value="reminder">Reminder</option>
                                <option value="start">Task Start</option>
                                <option value="end">Task Completion</option>
                                <option value="cancellation">Cancellation</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Send At</label>
                            <input type="datetime-local" class="form-control" wire:model="notificationSendAt">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Schedule Notification</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('livewire:init', () => {
                // Listen for Livewire events to reset forms when modals are closed
                $('#viewModal, #editModal, #deleteModal, #notificationModal').on('hidden.bs.modal', function() {
                    @this.resetForm();
                });

                // Listen for status changes to show/hide date fields
                Livewire.on('statusChanged', (status) => {
                    // You can add JavaScript logic here if needed
                });
            });
        </script>
    @endpush
</div>
