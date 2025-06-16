<div>
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
                <ul class="nav nav-tabs nav-tabs-bottom mt-4">
                    <li class="nav-item">
                        <a href="#info-tab" class="nav-link active" data-bs-toggle="tab">
                            <i class="fas fa-info-circle me-1"></i> Basic Info
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#update-tab" class="nav-link" data-bs-toggle="tab">
                            <i class="fas fa-edit me-1"></i> Update Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#security-tab" class="nav-link" data-bs-toggle="tab">
                            <i class="fas fa-shield-alt me-1"></i> Security
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#notifications-tab" class="nav-link" data-bs-toggle="tab">
                            <i class="fas fa-bell me-1"></i> Send Email
                        </a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content pt-3">
                    <!-- Basic Info Tab -->
                    <div class="tab-pane fade show active" id="info-tab">
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
                                        <img src="{{ $user->profile_photo_url }}" alt="Profile"
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
                    <div class="tab-pane fade" id="update-tab">
                        <form wire:submit.prevent="updateUser">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>First Name <span class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('user.first_name') is-invalid @enderror"
                                            wire:model.defer="user.first_name">
                                        @error('user.first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Last Name <span class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('user.last_name') is-invalid @enderror"
                                            wire:model.defer="user.last_name">
                                        @error('user.last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('user.email') is-invalid @enderror"
                                            wire:model.defer="user.email">
                                        @error('user.email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text" class="form-control @error('user.phone') is-invalid @enderror"
                                            wire:model.defer="user.phone">
                                        @error('user.phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Role <span class="text-danger">*</span></label>
                                        <select class="form-select @error('user.role') is-invalid @enderror"
                                            wire:model.defer="user.role">
                                            <option value="admin">Administrator</option>
                                            <option value="staff">Staff Member</option>
                                            <option value="it-person">IT Technician</option>
                                            <option value="user">Regular User</option>
                                        </select>
                                        @error('user.role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Department</label>
                                        <input type="text" class="form-control" wire:model.defer="user.department">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Bio</label>
                                        <textarea class="form-control" rows="3" wire:model.defer="user.bio"></textarea>
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
                    <div class="tab-pane fade" id="security-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Password Reset</h5>
                                    </div>
                                    <div class="card-body">
                                        <form wire:submit.prevent="resetPassword">
                                            <div class="form-group">
                                                <label>New Password</label>
                                                <input type="password"
                                                    class="form-control @error('new_password') is-invalid @enderror"
                                                    wire:model.defer="new_password">
                                                @error('new_password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-2">
                                                <label>Confirm Password</label>
                                                <input type="password" class="form-control"
                                                    wire:model.defer="new_password_confirmation">
                                            </div>
                                            <div class="mt-3">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-key me-1"></i> Reset Password
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Account Status</h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($user->is_banned)
                                            <p class="text-danger">This account is currently banned.</p>
                                            <button wire:click="unbanUser" class="btn btn-success">
                                                <i class="fas fa-check-circle me-1"></i> Unban Account
                                            </button>
                                        @else
                                            <p class="text-success">This account is active.</p>
                                            <button wire:click="banUser" class="btn btn-warning">
                                                <i class="fas fa-ban me-1"></i> Ban Account
                                            </button>
                                        @endif

                                        <hr>

                                        <button wire:click="confirmDelete" class="btn btn-danger mt-2">
                                            <i class="fas fa-trash-alt me-1"></i> Delete Account Permanently
                                        </button>
                                    </div>
                                </div>
                            </div>
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
                    {{-- <form wire:submit.prevent="updateProfilePhoto">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="profilePhoto" class="form-label">Select Image</label>
                            <input class="form-control" type="file" id="profilePhoto" wire:model="image">
                            @error('image') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        @if ($photo)
                            <div class="mt-2">
                                <h6>Preview:</h6>
                                <img src="{{ $photo->temporaryUrl() }}" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <span wire:loading.remove wire:target="updateProfilePhoto">Upload</span>
                            <span wire:loading wire:target="updateProfilePhoto">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Uploading...
                            </span>
                        </button>
                    </div>
                </form> --}}
                </div>
            </div>
        </div>
    </div>
</div>
