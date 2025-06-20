<div>
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Device Details</h4>
                <h6>{{ $device->device_name }}</h6>
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
                <ul class="nav nav-tabs nav-tabs-bottom">
                    <li class="nav-item">
                        <a href="#" class="nav-link @if($activeTab === 'view') active @endif"
                           wire:click.prevent="switchTab('view')">
                           <i class="fas fa-eye me-1"></i> View
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link @if($activeTab === 'edit') active @endif"
                           wire:click.prevent="switchTab('edit')">
                           <i class="fas fa-edit me-1"></i> Edit
                        </a>
                    </li>
                </ul>

                <div class="tab-content mt-4">
                    @if($activeTab === 'view')
                        <!-- View Tab Content -->
                        <div class="row">

                            <div class="col-md-8">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <th>Device Name</th>
                                                <td>{{ $device->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Model</th>
                                                <td>{{ $device->model ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Brand</th>
                                                <td>{{ $device->brand ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Category</th>
                                                <td>{{ $device->category->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Serial Number</th>
                                                <td>{{ $device->serial_number }}</td>
                                            </tr>
                                            <tr>
                                                <th>Description</th>
                                                <td>{{ $device->description ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Purchase Date</th>
                                                <td>{{ $device->purchase_date->format('d M Y') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>
                                                    <span class="badge bg-{{
                                                        $device->status == 'available' ? 'success' :
                                                        ($device->status == 'assigned' ? 'primary' :
                                                        ($device->status == 'maintenance' ? 'warning' : 'danger'))
                                                    }}">
                                                        {{ ucfirst($device->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @if($device->assigned_to)
                                            <tr>
                                                <th>Assigned To</th>
                                                <td>{{ $device->assignedTO->first_name }} {{ $device->assignedTO->last_name }}</td>
                                            </tr>
                                            @endif
                                            @if($device->location_id)
                                            <tr>
                                                <th>Location</th>
                                                <td>{{ $device->location->name }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <th>Created At</th>
                                                <td>{{ $device->created_at->format('d M Y') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Last Updated</th>
                                                <td>{{ $device->updated_at->format('d M Y ') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                @if ($device->image)
                                    <img src="{{ asset('storage/'.$device->image) }}"
                                         alt="{{ $device->device_name }}"
                                         class="img-fluid rounded mb-3"
                                         style="max-height: 300px;">
                                @else
                                    <img src="{{ asset('assets/img/product/product17.jpg') }}"
                                         alt="Default device image"
                                         class="img-fluid rounded mb-3"
                                         style="max-height: 300px;">
                                @endif
                            </div>
                        </div>
                    @else
                        <!-- Edit Tab Content -->
                        <form wire:submit.prevent="updateDevice">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Device Name</label>
                                        <input type="text" class="form-control" wire:model="name">
                                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Model</label>
                                        <input type="text" class="form-control" wire:model="model">
                                        @error('model') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Brand</label>
                                        <input type="text" class="form-control" wire:model="brand">
                                        @error('brand') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Category</label>
                                        <select class="form-select" wire:model="category_id">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ $category->id == $device->category_id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Serial Number</label>
                                        <input type="text" class="form-control" wire:model="serial_number">
                                        @error('serial_number') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea class="form-control" wire:model="description" rows="3"></textarea>
                                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Purchase Date</label>
                                        <input type="date" class="form-control" wire:model="purchase_date">
                                        @error('purchase_date') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="form-select" wire:model="status">
                                            <option value="available" {{ $device->status == 'available' ? 'selected' : '' }}>Available</option>
                                            <option value="assigned" {{ $device->status == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                            <option value="maintenance" {{ $device->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                            <option value="retired" {{ $device->status == 'retired' ? 'selected' : '' }}>Retired</option>
                                        </select>
                                        @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Assigned To</label>
                                        <select class="form-select" wire:model="assigned_to">
                                            <option value="">Not Assigned</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ $user->id == $device->assigned_to ? 'selected' : '' }}>
                                                    {{ $user->first_name }} {{ $user->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('assigned_to') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Location</label>
                                        <select class="form-select" wire:model="location_id">
                                            <option value="">Select Location</option>
                                            @foreach($locations as $location)
                                                <option value="{{ $location->id }}" {{ $location->id == $device->location_id ? 'selected' : '' }}>
                                                    {{ $location->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('location_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Device Image</label>
                                        <input type="file" class="form-control" wire:model="image">
                                        @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                                        @if($isUploading)
                                            <div class="mt-2 text-info">Uploading image...</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mt-3">
                                <button type="button" class="btn btn-secondary me-2" wire:click="switchTab('view')">
                                    Cancel
                                </button>
                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                    <span wire:loading.remove>Update Device</span>
                                    <span wire:loading>Updating...</span>
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
