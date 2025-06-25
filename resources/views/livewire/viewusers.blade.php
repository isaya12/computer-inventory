<div>
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>User List</h4>
                <h6>Manage your users</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('newuser') }}" class="btn btn-added" >
                    <img src="{{ asset('assets/img/icons/plus.svg') }}" alt="img" class="me-1">
                    Add New User
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
                <ul class="nav nav-tabs nav-tabs-bottom mb-4">
                    <li class="nav-item">
                        <a href="#" class="nav-link @if($statusFilter === '') active @endif"
                           wire:click.prevent="setStatusFilter('')">
                           All Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link @if($statusFilter === 'active') active @endif"
                           wire:click.prevent="setStatusFilter('active')">
                           Active
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link @if($statusFilter === 'banned') active @endif"
                           wire:click.prevent="setStatusFilter('banned')">
                           Banned
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link @if($roleFilter === 'admin') active @endif"
                           wire:click.prevent="$set('roleFilter', 'admin')">
                           Admins
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link @if($roleFilter === 'staff') active @endif"
                           wire:click.prevent="$set('roleFilter', 'staff')">
                           Staff
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link @if($roleFilter === 'it-person') active @endif"
                           wire:click.prevent="$set('roleFilter', 'it-person')">
                           Technician
                        </a>
                    </li>
                </ul>


                <div class="table-responsive">
                    <table class="table datanew">
                        <thead>
                            <tr>
                                <th wire:click="sortBy('first_name')" style="cursor: pointer;">
                                    Name
                                    @if($sortField === 'first_name')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th wire:click="sortBy('email')" style="cursor: pointer;">
                                    Email
                                    @if($sortField === 'email')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th>Phone</th>
                                <th wire:click="sortBy('role')" style="cursor: pointer;">
                                    Role
                                    @if($sortField === 'role')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td class="productimgname">
                                        <a href="javascript:void(0);" class="product-img">
                                            @if ($user->image)
                                                <img src="{{ asset('storage/' . $user->image) }}" alt="user">
                                            @else
                                                <img src="{{ asset('assets/img/user.png') }}" alt="user">
                                            @endif
                                        </a>
                                        <a href="javascript:void(0);">{{ $user->first_name }} {{ $user->last_name }}</a>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>
                                        <span class="badge
                                            @if($user->role == 'admin') bg-danger
                                            @elseif($user->role == 'facult') bg-primary
                                            @elseif($user->role == 'it-person') bg-success
                                            @else bg-secondary
                                            @endif">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a class="me-3" href="javascript:void(0);" wire:click="viewUser({{ $user->id }})">
                                            <img src="{{ asset('assets/img/icons/eye.svg') }}" alt="img" title="View">
                                        </a>
                                        <a class="me-3" href="javascript:void(0);" wire:click="confirmDelete({{ $user->id }})">
                                            <img src="{{ asset('assets/img/icons/delete.svg') }}" alt="img" title="Delete">
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No users found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>



  <!-- Delete Confirmation Modal -->
<div wire:ignore.self class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true" wire:model="showDeleteModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="closeModal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this user? This action cannot be undone.</p>
                @error('delete')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="closeModal">Cancel</button>
                <button type="button" class="btn btn-danger" wire:click="deleteUser">
                    <span wire:loading.remove>Delete User</span>
                    <span wire:loading>Deleting...</span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

        // Show modal when event is triggered
        Livewire.on('showDeleteModal', () => {
            deleteModal.show();
        });

        // Hide modal when event is triggered
        Livewire.on('hideDeleteModal', () => {
            deleteModal.hide();
        });

        // Close modal when backdrop is clicked
        document.getElementById('deleteModal').addEventListener('hidden.bs.modal', function () {
            @this.hideDeleteModal();
        });
    });
</script>
@endpush
</div>
