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
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $activeTab === 'email' ? 'active' : '' }}"
                            wire:click.prevent="selectTab('email')" type="button">
                            <i class="fas fa-envelope me-1"></i> Send Email
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
                                        <img src="{{ $user->profile_image_url }}" alt="Profile"
                                            class="img-fluid rounded-circle"
                                            style="width: 150px; height: 150px; object-fit: cover;">
                                        <div class="mt-3">
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#uploadPhotoModal">
                                                <i class="fas fa-camera me-1"></i> Change Photo
                                            </button>
                                        </div>
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
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
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

                    <!-- Email Tab -->
                    <div class="tab-pane fade {{ $activeTab === 'email' ? 'show active' : '' }}" id="email-tab"
                        role="tabpanel">
                        <form wire:submit.prevent="sendEmail">
                            <div class="form-group">
                                <label>Subject</label>
                                <input type="text" class="form-control @error('emailSubject') is-invalid @enderror"
                                    wire:model="emailSubject">
                                @error('emailSubject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mt-3">
                                <label>Message</label>
                                <textarea class="form-control @error('emailMessage') is-invalid @enderror" rows="5" wire:model="emailMessage"></textarea>
                                @error('emailMessage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i> Send Email
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <!-- Photo Upload Modal -->
<div class="modal fade" id="uploadPhotoModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Profile Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="updateProfilePhoto">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="profilePhoto" class="form-label">Select Image</label>
                        <input class="form-control" type="file" id="profilePhoto" wire:model="photo" accept="image/*">
                        @error('photo')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    @if ($photo)
                        <div class="mt-2">
                            <h6>Preview:</h6>
                            <img src="{{ $photo->temporaryUrl() }}" class="img-thumbnail"
                                style="max-height: 200px;">
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <span wire:loading.remove wire:target="updateProfilePhoto">Upload</span>
                        <span wire:loading wire:target="updateProfilePhoto">
                            <span class="spinner-border spinner-border-sm" role="status"
                                aria-hidden="true"></span>
                            Uploading...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
