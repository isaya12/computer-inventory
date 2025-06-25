<div>
    <div class="content">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div class="page-title">
                <h4 class="mb-0">Device Borrowing</h4>
            </div>
            @if (auth()->user()->role == 'staff')
                <button wire:click="$set('showBorrowModal', true)" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#borrowDeviceModal">
                    <i class="fas fa-plus me-2"></i>Request Device
                </button>
            @endif
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

        <!-- Status Tabs -->
        <div class="card">
            <div class="card-header">
                @if (in_array(auth()->user()->role, ['admin', 'it-person']))
                    <ul class="nav nav-tabs card-header-tabs" id="statusTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="approved-tab" data-bs-toggle="tab"
                                data-bs-target="#approved-tab-pane" type="button" role="tab"
                                aria-controls="approved-tab-pane" aria-selected="true">
                                <i class="fas fa-check-circle me-1"></i> Approved
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pending-tab" data-bs-toggle="tab"
                                data-bs-target="#pending-tab-pane" type="button" role="tab"
                                aria-controls="pending-tab-pane" aria-selected="false">
                                <i class="fas fa-clock me-1"></i> Pending
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="overdue-tab" data-bs-toggle="tab"
                                data-bs-target="#overdue-tab-pane" type="button" role="tab"
                                aria-controls="overdue-tab-pane" aria-selected="false">
                                <i class="fas fa-exclamation-triangle me-1"></i> Overdue
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="returned-tab" data-bs-toggle="tab"
                                data-bs-target="#returned-tab-pane" type="button" role="tab"
                                aria-controls="returned-tab-pane" aria-selected="false">
                                <i class="fas fa-undo me-1"></i> Returned
                            </button>
                        </li>
                    </ul>
                @else
                    <h4 class="mb-0">My Borrow History</h4>
                @endif
            </div>
            <div class="card-body">
                @if (in_array(auth()->user()->role, ['admin', 'it-person']))
                    <div class="tab-content" id="statusTabsContent">
                        <!-- Approved Tab -->
                        <div class="tab-pane fade show active" id="approved-tab-pane" role="tabpanel"
                            aria-labelledby="approved-tab" tabindex="0">
                            @php
                                $approvedRequests = \App\Models\BorrowDevice::with(['device', 'user'])
                                    ->where('status', 'approved')
                                    ->orderBy('created_at', 'desc')
                                    ->get();
                            @endphp

                            @if ($approvedRequests->isEmpty())
                                <div class="alert alert-info text-center">No approved borrowings found.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Device</th>
                                                <th>Serial Number</th>
                                                <th>Borrower</th>
                                                <th>Borrow Date</th>
                                                <th>Expected Return</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($approvedRequests as $request)
                                                <tr>
                                                    <td>{{ $request->device->name }}</td>
                                                    <td>{{ $request->device->serial_number }}</td>
                                                    <td>{{ $request->user->first_name }}
                                                        {{ $request->user->last_name }}</td>
                                                    <td>{{ $request->borrowed_at->format('M d, Y') }}</td>
                                                    <td
                                                        class="{{ $request->expected_return_date->isPast() ? 'text-danger' : '' }}">
                                                        {{ $request->expected_return_date->format('M d, Y') }}
                                                    </td>
                                                    <td>
                                                        <button wire:click="viewBorrowing({{ $request->id }})"
                                                            class="btn btn-sm btn-info me-1">
                                                            <i class="fas fa-eye"></i> View
                                                        </button>
                                                        <button wire:click="prepareReturn({{ $request->id }})"
                                                            class="btn btn-sm btn-success">
                                                            <i class="fas fa-undo-alt"></i> Return
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <!-- Pending Tab -->
                        <div class="tab-pane fade" id="pending-tab-pane" role="tabpanel"
                            aria-labelledby="pending-tab" tabindex="0">
                            @php
                                $pendingRequests = \App\Models\BorrowDevice::with(['device', 'user'])
                                    ->where('status', 'pending')
                                    ->orderBy('created_at', 'desc')
                                    ->get();
                            @endphp

                            @if ($pendingRequests->isEmpty())
                                <div class="alert alert-info text-center">No pending requests found.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Device</th>
                                                <th>Borrower</th>
                                                <th>Request Date</th>
                                                <th>Expected Return</th>
                                                <th>Purpose</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pendingRequests as $request)
                                                <tr>
                                                    <td>{{ $request->device->name }}</td>
                                                    <td>{{ $request->user->first_name }}
                                                        {{ $request->user->last_name }}</td>
                                                    <td>{{ $request->created_at->format('M d, Y H:i') }}</td>
                                                    <td>{{ $request->expected_return_date->format('M d, Y') }}</td>
                                                    <td>{{ Str::limit($request->purpose, 50) }}</td>
                                                    <td>
                                                        <button wire:click="viewBorrowing({{ $request->id }})"
                                                            class="btn btn-sm btn-info me-1">
                                                            <i class="fas fa-eye"></i> View
                                                        </button>
                                                        <button wire:click="approveBorrowRequest({{ $request->id }})"
                                                            class="btn btn-sm btn-success">
                                                            <i class="fas fa-check-circle"></i> Approve
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <!-- Overdue Tab -->
                        <div class="tab-pane fade" id="overdue-tab-pane" role="tabpanel"
                            aria-labelledby="overdue-tab" tabindex="0">
                            @php
                                $overdueRequests = \App\Models\BorrowDevice::with('device')
                                    ->where('status', 'overdue')
                                    ->get();
                            @endphp

                            @if ($overdueRequests->isEmpty())
                                <div class="alert alert-info text-center">No overdue devices found.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Device</th>
                                                <th>Serial Number</th>
                                                <th>Borrow Date</th>
                                                <th>Expected Return</th>
                                                <th>Days Overdue</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($overdueRequests as $request)
                                                <tr>
                                                    <td>{{ $request->device->name }}</td>
                                                    <td>{{ $request->device->serial_number }}</td>
                                                    <td>{{ $request->borrowed_at->format('M d, Y') }}</td>
                                                    <td class="text-danger">
                                                        {{ $request->expected_return_date->format('M d, Y') }}
                                                    </td>
                                                    <td>
                                                        {{ now()->diffInDays($request->expected_return_date) }}
                                                    </td>
                                                    <td>
                                                        <button wire:click="viewBorrowing({{ $request->id }})"
                                                            class="btn btn-sm btn-info me-1">
                                                            <i class="fas fa-eye"></i> View
                                                        </button>
                                                        <button wire:click="prepareReturn({{ $request->id }})"
                                                            class="btn btn-sm btn-success">
                                                            <i class="fas fa-undo-alt"></i> Return
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <!-- Returned Tab -->
                        <div class="tab-pane fade" id="returned-tab-pane" role="tabpanel"
                            aria-labelledby="returned-tab" tabindex="0">
                            @php
                                $returnedRequests = \App\Models\BorrowDevice::with('device')
                                    ->where('status', 'returned')
                                    ->orderBy('returned_at', 'desc')
                                    ->get();
                            @endphp

                            @if ($returnedRequests->isEmpty())
                                <div class="alert alert-info text-center">No returned devices found.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Device</th>
                                                <th>Serial Number</th>
                                                <th>Borrow Date</th>
                                                <th>Return Date</th>
                                                <th>Notes</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($returnedRequests as $request)
                                                <tr>
                                                    <td>{{ $request->device->name }}</td>
                                                    <td>{{ $request->device->serial_number }}</td>
                                                    <td>{{ $request->borrowed_at }}</td>
                                                    <td>{{ $request->returned_at }}</td>
                                                    <td>{{ Str::limit($request->notes, 30) }}</td>
                                                    <td> <button wire:click="viewBorrowing({{ $request->id }})"
                                                            class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i> View
                                                        </button></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <!-- Staff Borrow History Section -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Device</th>
                                    <th>Serial Number</th>
                                    <th>Status</th>
                                    <th>Borrow Date</th>
                                    <th>Expected Return</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $userBorrowings = \App\Models\BorrowDevice::with('device')
                                        ->where('user_id', auth()->id())
                                        ->orderBy('created_at', 'desc')
                                        ->get();
                                @endphp

                                @forelse ($userBorrowings as $borrowing)
                                    <tr>
                                        <td>{{ $borrowing->device->name }}</td>
                                        <td>{{ $borrowing->device->serial_number }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $borrowing->status == 'approved'
                                                    ? 'primary'
                                                    : ($borrowing->status == 'pending'
                                                        ? 'warning'
                                                        : ($borrowing->status == 'overdue'
                                                            ? 'danger'
                                                            : ($borrowing->status == 'returned'
                                                                ? 'success'
                                                                : 'secondary'))) }}">
                                                {{ ucfirst($borrowing->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($borrowing->borrowed_at)
                                                {{ $borrowing->borrowed_at->format('M d, Y') }}
                                            @else
                                                {{ $borrowing->created_at->format('M d, Y') }}
                                            @endif
                                        </td>
                                        <td
                                            class="{{ $borrowing->expected_return_date->isPast() && $borrowing->status != 'returned' ? 'text-danger' : '' }}">
                                            {{ $borrowing->expected_return_date->format('M d, Y') }}
                                        </td>
                                        <td>
                                            <button wire:click="viewBorrowing({{ $borrowing->id }})"
                                                class="btn btn-sm btn-info me-1">
                                                <i class="fas fa-eye"></i> View
                                            </button>

                                            @if ($borrowing->status == 'pending')
                                                <button wire:click="cancelBorrowRequest({{ $borrowing->id }})"
                                                    class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times"></i> Cancel
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No borrow history found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- View Borrowing Modal -->
    <div wire:ignore.self class="modal fade" id="viewBorrowingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Borrowing Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($selectedBorrowing)
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-6">
                                    <h6>Device Information</h6>
                                    <p><strong>Name:</strong> {{ $selectedBorrowing->device->name }}</p>
                                    <p><strong>Serial Number:</strong> {{ $selectedBorrowing->device->serial_number }}
                                    </p>
                                </div>
                                <div class="col-6">
                                    <img width="100px" height="100px"
                                        src="{{ asset('storage/device-images/' . basename($selectedBorrowing->device->image)) }}"
                                        alt="">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <h6>Borrowing Details</h6>
                            <div class="row">
                                <div class="col-6">
                                    <p><strong>Status:</strong>
                                        <span
                                            class="badge bg-{{ $selectedBorrowing->status == 'approved'
                                                ? 'primary'
                                                : ($selectedBorrowing->status == 'pending'
                                                    ? 'warning'
                                                    : ($selectedBorrowing->status == 'overdue'
                                                        ? 'danger'
                                                        : ($selectedBorrowing->status == 'returned'
                                                            ? 'success'
                                                            : 'secondary'))) }}">
                                            {{ ucfirst($selectedBorrowing->status) }}
                                        </span>
                                    </p>

                                </div>
                                <div class="col-6">
                                    <p><strong>Borrow Date:</strong>
                                        {{ $selectedBorrowing->borrowed_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <p><strong>Expected Return:</strong>
                                        {{ $selectedBorrowing->expected_return_date->format('M d, Y') }}</p>
                                </div>
                                <div class="col-6">
                                    @if ($selectedBorrowing->returned_at)
                                        <p><strong>Return Date:</strong>
                                            {{ $selectedBorrowing->returned_at->format('M d, Y H') }}</p>
                                    @endif
                                </div>
                            </div>
                            <p><strong>Purpose:</strong> {{ $selectedBorrowing->purpose }}</p>
                            @if ($selectedBorrowing->notes)
                                <p><strong>Notes:</strong> {{ $selectedBorrowing->notes }}</p>
                            @endif
                        </div>
                        @if (in_array(auth()->user()->role, ['admin', 'it-person']))
                            <div class="mb-4">
                                <h6>Borrower Information</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Name:</strong> {{ $selectedBorrowing->user->first_name }}
                                            {{ $selectedBorrowing->user->last_name }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Email:</strong> {{ $selectedBorrowing->user->email }}</p>
                                        <p><strong>Phone:</strong> {{ $selectedBorrowing->user->phone }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('livewire:initialized', () => {
                // Handle all modals
                const borrowModal = new bootstrap.Modal(document.getElementById('borrowDeviceModal'));
                const returnModal = new bootstrap.Modal(document.getElementById('returnDeviceModal'));
                const viewModal = new bootstrap.Modal(document.getElementById('viewBorrowingModal'));

                Livewire.on('close-modal', (data) => {
                    switch (data.id) {
                        case 'borrowDeviceModal':
                            borrowModal.hide();
                            break;
                        case 'returnDeviceModal':
                            returnModal.hide();
                            break;
                        case 'viewBorrowingModal':
                            viewModal.hide();
                            break;
                    }
                });

                Livewire.on('open-modal', (data) => {
                    switch (data.id) {
                        case 'returnDeviceModal':
                            returnModal.show();
                            break;
                        case 'viewBorrowingModal':
                            viewModal.show();
                            break;
                    }
                });

                // Initialize tabs
                const tabElms = document.querySelectorAll('button[data-bs-toggle="tab"]');
                tabElms.forEach(tabEl => {
                    tabEl.addEventListener('shown.bs.tab', event => {
                        // You can add additional tab change logic here if needed
                    });
                });
            });
        </script>
    @endpush
</div>
