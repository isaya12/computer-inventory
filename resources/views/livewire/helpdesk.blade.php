<div>
    <div class="content">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div class="page-title">
                <h4 class="mb-0">Help Desk / Tickets</h4>
            </div>
            <div class="col-auto ps-0">
                <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#createTicketModal">
                    <img src="{{ asset('assets/img/icons/plus1.svg') }}" alt="Create" class="me-2">
                    Create New Ticket
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                @if ($tickets->isEmpty())
                    <div class="alert alert-info text-center">You have not created any tickets yet.</div>
                @else
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Ticket ID</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tickets as $ticket)
                                    <tr>
                                        <td>{{ $ticket->ticket_id }}</td>
                                        <td>{{ $ticket->subject }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $ticket->status == 'open'
                                                    ? 'primary'
                                                    : ($ticket->status == 'pending'
                                                        ? 'warning'
                                                        : ($ticket->status == 'resolved'
                                                            ? 'success'
                                                            : 'secondary')) }}">
                                                {{ ucfirst($ticket->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $ticket->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="#" wire:click.prevent="viewTicket({{ $ticket->id }})"
                                                class="text-info me-2">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if ($ticket->status == 'open')
                                                <a href="#"
                                                    wire:click.prevent="confirmDelete({{ $ticket->id }})"
                                                    class="text-danger">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Create Ticket Modal -->
    <div wire:ignore.self class="modal fade" id="createTicketModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="createTicket">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Subject</label>
                                    <input type="text" class="form-control" wire:model="subject" required>
                                    @error('subject')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control" rows="5" wire:model="description" required></textarea>
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Attachments</label>
                                    <input type="file" class="form-control" wire:model="attachments" multiple>
                                    @error('attachments')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <small class="text-muted">You can upload multiple files (Max: 5MB each)</small>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <span wire:loading.remove wire:target="createTicket">Submit Ticket</span>
                                <span wire:loading wire:target="createTicket"
                                    class="spinner-border spinner-border-sm"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- View Ticket Modal -->
    <div wire:ignore.self class="modal fade" id="viewTicketModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ticket Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($selectedTicket)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Ticket ID</label>
                                    <p class="form-control-static">{{ $selectedTicket->ticket_id }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Status</label>
                                    <p class="form-control-static">
                                        <span
                                            class="badge bg-{{ $selectedTicket->status == 'open'
                                                ? 'primary'
                                                : ($selectedTicket->status == 'pending'
                                                    ? 'warning'
                                                    : ($selectedTicket->status == 'resolved'
                                                        ? 'success'
                                                        : 'secondary')) }}">
                                            {{ ucfirst($selectedTicket->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Subject</label>
                                    <p class="form-control-static">{{ $selectedTicket->subject }}</p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Description</label>
                                    <div class="border p-3 rounded bg-light">
                                        {!! nl2br(e($selectedTicket->description)) !!}
                                    </div>
                                </div>
                            </div>
                            @if ($selectedTicket->attachment)
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Attachment</label>
                                        <div>
                                            <a href="{{ Storage::url($selectedTicket->attachment) }}" target="_blank"
                                                class="btn btn-outline-primary">
                                                <i class="fas fa-paperclip me-2"></i> View Attachment
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <!-- User Information Section -->
                            @if (in_array(auth()->user()->role, ['admin', 'it-person']))
                                <div class="col-12">
                                    @if (auth()->id() == $selectedTicket->user_id)
                                        <div class="">
                                            <h6 class="mb-0 text-black">User Information</h6>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-2">
                                                    <label class="form-label fw-bold">Name</label>
                                                    <p>{{ $selectedTicket->user->first_name }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-2">
                                                    <label class="form-label fw-bold">Email</label>
                                                    <p>{{ $selectedTicket->user->email }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-2">
                                                    <label class="form-label fw-bold">Phone</label>
                                                    <p>{{ $selectedTicket->user->phone ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Created At</label>
                                    <p class="form-control-static">{{ $selectedTicket->created_at->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>
                            @if ($selectedTicket->resolved_at)
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Resolved At</label>
                                        <p class="form-control-static">
                                            {{ $selectedTicket->resolved_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            @endif

                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div wire:ignore.self class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this ticket? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteTicket">
                        <span wire:loading.remove wire:target="deleteTicket">Delete</span>
                        <span wire:loading wire:target="deleteTicket" class="spinner-border spinner-border-sm"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('livewire:initialized', () => {
                // Handle create ticket modal
                const createModal = new bootstrap.Modal(document.getElementById('createTicketModal'));

                // Handle view ticket modal
                const viewModal = new bootstrap.Modal(document.getElementById('viewTicketModal'));

                // Handle delete confirmation modal
                const deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));

                Livewire.on('close-modal', (data) => {
                    switch (data.id) {
                        case 'createTicketModal':
                            createModal.hide();
                            break;
                        case 'viewTicketModal':
                            viewModal.hide();
                            break;
                        case 'confirmDeleteModal':
                            deleteModal.hide();
                            break;
                    }
                });

                Livewire.on('open-modal', (data) => {
                    switch (data.id) {
                        case 'viewTicketModal':
                            viewModal.show();
                            break;
                        case 'confirmDeleteModal':
                            deleteModal.show();
                            break;
                    }
                });
            });
        </script>
    @endpush
</div>
