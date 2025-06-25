<div>
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Device List</h4>
                <h6>Manage your devices</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('newdevice') }}" class="btn btn-added">
                    <img src="{{ asset('assets/img/icons/plus.svg') }}" alt="img" class="me-1">
                    Add New Device
                </a>
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
                <ul class="nav nav-tabs nav-tabs-bottom ">
                    <li class="nav-item">
                        <a href="#" class="nav-link @if($statusFilter == '') active @endif"
                           wire:click.prevent="setStatusFilter('')">
                           All Devices
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link @if($statusFilter == 'assigned') active @endif"
                           wire:click.prevent="setStatusFilter('assigned')">
                           Assigned
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link @if($statusFilter == 'available') active @endif"
                           wire:click.prevent="setStatusFilter('available')">
                           Available
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link @if($statusFilter == 'retired') active @endif"
                           wire:click.prevent="setStatusFilter('retired')">
                           Retired
                        </a>
                    </li>
                </ul>
                <!-- Rest of your table code remains the same -->
                <div class="table-responsive py-4">
                    <table class="table datanew">
                        <thead>
                            <tr>
                                <th>
                                    <label class="checkboxs">
                                        <input type="checkbox" id="select-all">
                                        <span class="checkmarks"></span>
                                    </label>
                                </th>
                                <th wire:click="sortBy('device_name')" style="cursor: pointer;">
                                    Device Name
                                    @if($sortField === 'device_name')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th wire:click="sortBy('category')" style="cursor: pointer;">
                                    Category
                                    @if($sortField === 'category')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th>Serial Number</th>
                                <th wire:click="sortBy('purchase_date')" style="cursor: pointer;">
                                    Purchase Date
                                    @if($sortField === 'purchase_date')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($devices as $device)
                                <tr>
                                    <td>
                                        <label class="checkboxs">
                                            <input type="checkbox">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </td>
                                    <td class="productimgname">
                                        <a href="javascript:void(0);" class="product-img">
                                            @if ($device->image)
                                                <img src="{{ asset('storage/device-images/' . basename($device->image)) }}" alt="product">
                                            @else
                                                <img src="{{ asset('assets/img/product/devices.webp') }}" alt="product">
                                            @endif
                                        </a>
                                        <a href="javascript:void(0);">{{ $device->name }}</a>
                                    </td>
                                    <td>{{ $device->category->name }}</td>
                                    <td>{{ $device->serial_number }}</td>
                                    <td>{{ \Carbon\Carbon::parse($device->purchase_date)->format('d M Y') }}</td>
                                    <td>
                                        {{ $device->status }}
                                    </td>
                                    <td>{{ $device->created_by ?? 'Admin' }}</td>
                                    <td style="display: flex; align-items: center; gap: 10px;">
                                        @if($device->status != 'assigned')
                                        <a class="me-3" href="javascript:void(0);" wire:click="assignDevice({{ $device->id }})">
                                            <i class="fas fa-user-plus" title="Assign to User"></i>
                                        </a>
                                     @endif
                                        <a class="me-3" href="javascript:void(0);" wire:click="viewDevice({{ $device->id }})">
                                            <img src="{{ asset('assets/img/icons/eye.svg') }}" alt="View">
                                        </a>
                                        <a href="javascript:void(0);" wire:click="confirmDelete({{ $device->id }})">
                                            <img src="{{ asset('assets/img/icons/delete.svg') }}" alt="Delete" title="Delete">
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No devices found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $devices->links() }}
                </div>
            </div>
        </div>
    </div>
    @if ($showAssignModal)
    <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign Device</h5>
                    <button type="button" wire:click="$set('showAssignModal', false)" class="close">×</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Select User</label>
                        <select class="form-control" wire:model="userId">
                            <option value="">Select User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                            @endforeach
                        </select>
                        @error('userId') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="$set('showAssignModal', false)" class="btn btn-secondary">Cancel</button>
                    <button type="button" wire:click="completeAssignment" class="btn btn-primary">Assign</button>
                </div>
            </div>
        </div>


    </div>
@endif
@if ($showDeleteModal)
<div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" wire:click="$set('showDeleteModal', false)" class="close">×</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this device? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="$set('showDeleteModal', false)" class="btn btn-secondary">Cancel</button>
                <button type="button" wire:click="deleteDevice" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>
@endif
</div>
