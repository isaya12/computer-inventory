<div>
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Add New User</h4>
            </div>
        </div>

        <div class="col-12">
            @if (session()->has('success'))
                <div class="alert alert-success mt-3">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <div class="card">
            <form wire:submit.prevent="save" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <!-- First Name -->
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" wire:model="first_name" class="form-control" required>
                                @error('first_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Last Name -->
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" wire:model="last_name" class="form-control" required>
                                @error('last_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Email -->
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" wire:model="email" class="form-control" required>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" wire:model="phone" class="form-control" placeholder=" example 06210004500" required>
                                @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Role</label>
                                <select wire:model="role" class="form-control" required>
                                    <option value="admin">Admin</option>
                                    <option value="it-person">IT Person</option>
                                    <option value="staff" selected>Staff</option>
                                </select>
                                @error('role')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <!-- Profile Image -->
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Profile Image</label>
                            <div class="image-upload">
                                <input type="file" wire:model="image" class="form-control" accept="image/*">
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="image-uploads">
                                    <img src="{{ asset('assets/img/icons/upload.svg') }}" alt="img">
                                    <h4>Drag and drop a file to upload</h4>
                                </div>
                                @if ($image)
                                    <div class="mt-2">
                                        <img src="{{ $image->temporaryUrl() }}" style="max-height: 100px;">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-submit me-2">Create User</button>
                        <button type="button" wire:click="resetForm" class="btn btn-cancel">Reset</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
