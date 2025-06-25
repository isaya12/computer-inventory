<div class="">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>User Details</h4>
                <h6>Manage user account and related information</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <!-- Tab Bar -->
                <ul class="nav nav-tabs nav-tabs-bottom mt-4" id="userTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $activeTab === 'info' ? 'active' : '' }}"
                            wire:click.prevent="selectTab('info')" type="button">
                            <i class="fas fa-info-circle me-1"></i> Basic Info
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $activeTab === 'update' ? 'active' : '' }}"
                            wire:click.prevent="selectTab('update')" type="button">
                            <i class="fas fa-edit me-1"></i> Update Profile
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $activeTab === 'security' ? 'active' : '' }}"
                            wire:click.prevent="selectTab('security')" type="button">
                            <i class="fas fa-shield-alt me-1"></i> Security
                        </button>
                    </li>

                </ul>

                <!-- Tab Content -->
                <div class="tab-content pt-3">
                    <!-- Basic Info Tab -->
                    <div class="tab-pane fade {{ $activeTab === 'info' ? 'show active' : '' }}" id="info-tab"
                        role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th width="30%">Full Name</th>
                                                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <td>{{ $user->email }}</td>
                                            </tr>
                                            <tr>
                                                <th>Phone</th>
                                                <td>{{ $user->phone ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Role</th>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }}">
                                                        {{ ucfirst($user->role) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>
                                                    @if ($user->is_banned)
                                                        <span class="badge bg-danger">Banned</span>
                                                    @else
                                                        <span class="badge bg-success">Active</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Registered</th>
                                                <td>{{ $user->created_at->format('M d, Y h:i A') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Last Updated</th>
                                                <td>{{ $user->updated_at->format('M d, Y h:i A') }}</td>
                                            </tr>
                                            @if ($user->department)
                                                <tr>
                                                    <th>Department</th>
                                                    <td>{{ $user->department }}</td>
                                                </tr>
                                            @endif
                                            @if ($user->bio)
                                                <tr>
                                                    <th>Bio</th>
                                                    <td>{{ $user->bio }}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Profile Picture</h5>
                                    </div>
                                    <div class="card-body text-center">
                                        <label for="profilePhoto" style="cursor: pointer;">
                                            <img src="{{ asset('storage/' . $user->image) }}" alt="Profile"
                                                class="img-fluid "
                                                style="width: 200px; height: 200px; object-fit: cover;">
                                            <div class="mt-3">
                                                <span class="btn btn-sm btn-primary">
                                                    <i class="fas fa-camera me-1"></i> Change Photo
                                                </span>
                                            </div>
                                        </label>
                                        <input type="file" id="profilePhoto" wire:model="photo" accept="image/*"
                                            class="d-none">

                                        @if ($photo)
                                            <div class="mt-3">
                                                <button wire:click="updateProfilePhoto" class="btn btn-success">
                                                    <span wire:loading.remove wire:target="updateProfilePhoto">
                                                        <i class="fas fa-save me-1"></i> Save Photo
                                                    </span>
                                                    <span wire:loading wire:target="updateProfilePhoto">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                        Uploading...
                                                    </span>
                                                </button>
                                                <button wire:click="$set('photo', null)"
                                                    class="btn btn-outline-secondary ms-2">
                                                    Cancel
                                                </button>
                                            </div>
                                            <div class="mt-2">
                                                <small class="text-muted">Preview:</small>
                                                <img src="{{ $photo->temporaryUrl() }}" class="img-thumbnail mt-1"
                                                    style="max-height: 100px;">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Update Profile Tab -->
                    <div class="tab-pane fade {{ $activeTab === 'update' ? 'show active' : '' }}" id="update-tab"
                        role="tabpanel">
                        <form wire:submit.prevent="updateUser">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>First Name <span class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('first_name') is-invalid @enderror"
                                            wire:model="first_name">
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Last Name <span class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('last_name') is-invalid @enderror"
                                            wire:model="last_name">
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            wire:model="email">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            wire:model="phone">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Role <span class="text-danger">*</span></label>
                                        <select class="form-select @error('role') is-invalid @enderror"
                                            wire:model="role">
                                            <option value="admin">Administrator</option>
                                            <option value="staff">Staff</option>
                                            <option value="it-person">Technician</option>
                                        </select>
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-md-12 mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Update Profile
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" wire:click="cancelEdit">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Security Tab -->
                    <div class="tab-pane fade {{ $activeTab === 'security' ? 'show active' : '' }}" id="security-tab"
                        role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Account Status</h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($user->is_banned)
                                            <p class="text-danger">This account is currently banned.</p>
                                            <button wire:click="unbanUser" wire:loading.attr="disabled"
                                                class="btn btn-success">
                                                <span wire:loading.remove wire:target="unbanUser">
                                                    <i class="fas fa-check-circle me-1"></i> Unban Account
                                                </span>
                                                <span wire:loading wire:target="unbanUser">
                                                    <span class="spinner-border spinner-border-sm" role="status"
                                                        aria-hidden="true"></span>
                                                    Processing...
                                                </span>
                                            </button>
                                        @else
                                            <p class="text-success">This account is active.</p>
                                            <button wire:click="banUser" wire:loading.attr="disabled"
                                                class="btn btn-warning">
                                                <span wire:loading.remove wire:target="banUser">
                                                    <i class="fas fa-ban me-1"></i> Ban Account
                                                </span>
                                                <span wire:loading wire:target="banUser">
                                                    <span class="spinner-border spinner-border-sm" role="status"
                                                        aria-hidden="true"></span>
                                                    Processing...
                                                </span>
                                            </button>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
