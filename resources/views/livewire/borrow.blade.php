<div>
    <div class="content">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div class="page-title">
                <h4 class="mb-0">Device Borrowing System</h4>
            </div>
            <button wire:click="$set('showBorrowModal', true)" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#borrowDeviceModal">
                <i class="fas fa-plus me-2"></i>Request Device
            </button>
        </div>

        <!-- Borrow Device Modal -->
        <div wire:ignore.self class="modal fade" id="borrowDeviceModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Request Device Borrow</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="requestBorrow">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Select Device</label>
                                        <select wire:model="selectedDevice" class="form-select" required>
                                            <option value="">-- Select Device --</option>
                                            @foreach ($devices as $device)
                                                <option value="{{ $device->id }}">{{ $device->name }}
                                                    ({{ $device->serial_number }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('selectedDevice')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Expected Return Date</label>
                                        <input type="date" wire:model="expectedReturnDate" class="form-control"
                                            required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                        @error('expectedReturnDate')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Purpose</label>
                                        <textarea wire:model="purpose" class="form-control" rows="3" required></textarea>
                                        @error('purpose')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <span wire:loading.remove wire:target="requestBorrow">Submit Request</span>
                                    <span wire:loading wire:target="requestBorrow"
                                        class="spinner-border spinner-border-sm"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Return Device Modal -->
        <div wire:ignore.self class="modal fade" id="returnDeviceModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Return Device</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="submitReturn">
                            <div class="mb-3">
                                <label class="form-label">Return Notes</label>
                                <textarea wire:model="returnNotes" class="form-control" rows="3" required></textarea>
                                @error('returnNotes')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <span wire:loading.remove wire:target="submitReturn">Submit Return</span>
                                    <span wire:loading wire:target="submitReturn"
                                        class="spinner-border spinner-border-sm"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Borrowed Devices Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">My Borrowed Devices</h5>
            </div>
            <div class="card-body">
                @if ($borrowings->isEmpty())
                    <div class="alert alert-info">You don't have any active borrowings.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Device</th>
                                    <th>Serial Number</th>
                                    <th>Borrow Date</th>
                                    <th>Expected Return</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($borrowings as $borrowing)
                                    <tr>
                                        <td>{{ $borrowing->device->device_name }}</td>
                                        <td>{{ $borrowing->device->serial_number }}</td>
                                        <td>{{ $borrowing->borrowed_at->format('M d, Y') }}</td>
                                        <td
                                            class="{{ $borrowing->expected_return_date->isPast() ? 'text-danger' : '' }}">
                                            {{ $borrowing->expected_return_date->format('M d, Y') }}
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $borrowing->status == 'approved' ? 'primary' : ($borrowing->status == 'overdue' ? 'danger' : 'secondary') }}">
                                                {{ ucfirst($borrowing->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($borrowing->status == 'approved')
                                                <button wire:click="prepareReturn({{ $borrowing->id }})"
                                                    class="btn btn-sm btn-success">
                                                    <i class="fas fa-undo-alt"></i> Return
                                                </button>
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

        <!-- Pending Requests Card -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title">My Pending Requests</h5>
            </div>
            <div class="card-body">
                @php
                    $pendingRequests = \App\Models\BorrowDevice::with('device')
                        ->where('user_id', auth()->id())
                        ->where('status', 'pending')
                        ->get();
                @endphp

                @if ($pendingRequests->isEmpty())
                    <div class="alert alert-info">You don't have any pending requests.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Device</th>
                                    <th>Request Date</th>
                                    <th>Expected Return</th>
                                    <th>Purpose</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendingRequests as $request)
                                    <tr>
                                        <td>{{ $request->device->device_name }}</td>
                                        <td>{{ $request->created_at->format('M d, Y') }}</td>
                                        <td>{{ $request->expected_return_date->format('M d, Y') }}</td>
                                        <td>{{ Str::limit($request->purpose, 50) }}</td>
                                        <td>
                                            <button wire:click="cancelBorrowRequest({{ $request->id }})"
                                                class="btn btn-sm btn-danger">
                                                <i class="fas fa-times"></i> Cancel
                                            </button>
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

    @push('scripts')
        <script>
            document.addEventListener('livewire:initialized', () => {
                // Handle modals
                const borrowModal = new bootstrap.Modal(document.getElementById('borrowDeviceModal'));
                const returnModal = new bootstrap.Modal(document.getElementById('returnDeviceModal'));

                Livewire.on('close-modal', (data) => {
                    if (data.id === 'borrowDeviceModal') {
                        borrowModal.hide();
                    } else if (data.id === 'returnDeviceModal') {
                        returnModal.hide();
                    }
                });

                Livewire.on('open-modal', (data) => {
                    if (data.id === 'returnDeviceModal') {
                        returnModal.show();
                    }
                });
            });
        </script>
    @endpush
</div>
