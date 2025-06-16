<div>
    <div class="content">
        <div class="row">
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="dash-widget">
                    <div class="dash-widgetimg">
                        <span><i class="fas fa-laptop-code fa-2x" style="color: #4e73df;"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5><span class="counters" data-count="{{ $totalDevices }}">{{ $totalDevices }}</span></h5>
                        <h6>Total Device</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="dash-widget dash1">
                    <div class="dash-widgetimg">
                        <i class="fas fa-user-check" style="font-size: 2rem; color: #1c61c8;"></i>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5><span class="counters" data-count="{{ $assignedDevices }}">{{ $assignedDevices }}</span>
                        </h5>
                        <h6>Assigned device </h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="dash-widget dash2">
                    <div class="dash-widgetimg">
                        <i class="fas fa-box-open" style="font-size: 2rem; color: #36b9cc;"></i>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5><span class="counters" data-count="{{ $availableDevices }}">{{ $availableDevices }}</span>
                        </h5>
                        <h6>Available Device</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="dash-widget dash3">
                    <div class="dash-widgetimg">
                        <i class="fas fa-tools fa-2x" style="color: #3e5af6;"></i>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5><span class="counters"
                                data-count="{{ $maintenanceDevices }}">{{ $maintenanceDevices }}</span></h5>
                        <h6>Maintainance</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12 d-flex">
                <div class="dash-count">
                    <div class="dash-counts">
                        <h4>{{ $usersCount ?? '0' }}</h4>
                        <h5>User</h5>
                    </div>
                    <div class="dash-imgs">
                        <i data-feather="user"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12 d-flex">
                <div class="dash-count das1">
                    <div class="dash-counts">
                        <h4>{{ $staffCount ?? '0' }}</h4>
                        <h5>Staff</h5>
                    </div>
                    <div class="dash-imgs">
                        <i data-feather="user-check"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12 d-flex">
                <div class="dash-count das2">
                    <div class="dash-counts">
                        <h4>100</h4>
                        <h5>available device</h5>
                    </div>
                    <div class="dash-imgs">
                        <i data-feather="file-text"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12 d-flex">
                <div class="dash-count das3">
                    <div class="dash-counts">
                        <h4>105</h4>
                        <h5>Device assigned</h5>
                    </div>
                    <div class="dash-imgs">
                        <i data-feather="file"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-7 col-sm-12 col-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">device</h5>
                        <div class="graph-sets">
                            <ul>
                                <li>
                                    <span>Available</span>
                                </li>
                                <li>
                                    <span>Assigned</span>
                                </li>
                            </ul>
                            <div class="dropdown">
                                <button class="btn btn-white btn-sm dropdown-toggle" type="button"
                                    id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    2025 <img src="assets/img/icons/dropdown.svg" alt="img" class="ms-2">
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item">2025</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item">2024</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item">2023</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="sales_charts"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-sm-12 col-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Recently Added Devices</h4>
                        <div class="dropdown">
                            <a href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false" class="dropset">
                                <i class="fa fa-ellipsis-v"></i>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li>
                                    <a href="{{ route('device') }}" class="dropdown-item">Device List</a>
                                </li>
                                <li>
                                    <a href="{{ route('newdevice') }}" class="dropdown-item">Add Device</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive dataview">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Device</th>
                                        <th>Serial Number</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentDevices as $index => $device)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="deviceimgname">
                                            <a href="{{ route('device', $device->id) }}" class="product-img">
                                                @if($device->device_image)
                                                    <img src="{{ asset('storage/' . $device->device_image) }}" alt="device" style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <img src="{{ asset('assets/img/product/product17.jpg') }}" alt="device" style="width: 50px; height: 50px; object-fit: cover;">
                                                @endif
                                            </a>
                                            <a href="{{ route('device', $device->id) }}">{{ $device->device_name }}</a>
                                        </td>
                                        <td>{{ $device->serial_number }}</td>
                                        <td>
                                            @if($device->status == 'available')
                                                <span class="badge bg-success">Available</span>
                                            @elseif($device->status == 'assigned')
                                                <span class="badge bg-primary">Assigned</span>
                                            @elseif($device->status == 'maintenance')
                                                <span class="badge bg-warning">Maintenance</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($device->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No recently added devices</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-body">
                <h4 class="card-title">Assigned device</h4>
                <div class="table-responsive dataview">
                    <table class="table datatable ">
                        <thead>
                            <tr>
                                <th>SNo</th>
                                <th>device Code</th>
                                <th>device Name</th>
                                <th>Brand Name</th>
                                <th>Category Name</th>
                                <th>Expiry Date</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td>4</td>
                                <td><a href="javascript:void(0);">IT0004</a></td>
                                <td class="productimgname">
                                    <a class="product-img" href="productlist.html">
                                        <img src="assets/img/product/product5.jpg" alt="product">
                                    </a>
                                    <a href="productlist.html">Avocat</a>
                                </td>
                                <td>N/D</td>
                                <td>Fruits</td>
                                <td>20-11-2022</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
