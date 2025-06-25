<div>
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Add Device</h4>
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
                        <!-- Device Name -->
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Device Name</label>
                                <input type="text" wire:model="name" class="form-control" required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Category</label>
                                <select wire:model="category_id" class="form-control" required>
                                    <option value="">Choose Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Model -->
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Model</label>
                                <input type="text" wire:model="model" class="form-control">
                                @error('model')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Brand -->
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Brand</label>
                                <input type="text" wire:model="brand" class="form-control">
                                @error('brand')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Serial Number -->
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Serial Number</label>
                                <input type="text" wire:model="serial_number" class="form-control" required>
                                @error('serial_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Barcode/RFID Section -->
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Barcode/RFID</label>
                                <div class="input-group">
                                    <input type="text" wire:model="barcode" class="form-control" readonly>
                                    <button type="button" wire:click="generateBarcode" class="btn btn-primary">
                                        Generate
                                    </button>
                                </div>
                                @error('barcode')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="mt-2">
                                    <canvas id="barcodeCanvas" style="display: none;"></canvas>
                                    <div id="barcodePreview" class="border p-2 bg-white"></div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">


                        <!-- Purchase Date -->
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Purchase Date</label>
                                <input type="date" wire:model="purchase_date" class="form-control" required>
                                @error('purchase_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Description -->
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea wire:model="description" class="form-control" rows="1"></textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Device Image</label>
                            <div class="image-upload">
                                <input type="file" wire:model="device_image" class="form-control" accept="image/*">
                                @error('device_image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="image-uploads">
                                    <img src="{{ asset('assets/img/icons/upload.svg') }}" alt="img">
                                    <h4>Drag and drop a file to upload</h4>
                                </div>
                                @if ($device_image)
                                    <div class="mt-2">
                                        <img src="{{ $device_image->temporaryUrl() }}" style="max-height: 100px;">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-submit me-2">Submit</button>
                        <button type="button" wire:click="resetForm" class="btn btn-cancel">Reset</button>


                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
        <script>
            document.addEventListener('livewire:initialized', () => {
                // Listen for the barcode generation event
                Livewire.on('barcodeGenerated', (data) => {
                    console.log('Generating barcode:', data);

                    try {
                        const canvas = document.getElementById('barcodeCanvas');
                        const preview = document.getElementById('barcodePreview');

                        if (!canvas || !preview) {
                            console.error('Required elements not found');
                            return;
                        }

                        // Clear previous barcode
                        preview.innerHTML = '';

                        // Generate new barcode with the device info
                        JsBarcode(canvas, data.barcodeData, {
                            format: "CODE128",
                            lineColor: "#000",
                            width: 2,
                            height: 50,
                            displayValue: true
                        });

                        // Create container for barcode and info
                        const container = document.createElement('div');
                        container.style.textAlign = 'center';
                        container.style.marginTop = '10px';

                        // Add the barcode image
                        const img = new Image();
                        img.src = canvas.toDataURL('image/png');
                        img.alt = 'Barcode';
                        img.style.height = '50px';
                        img.style.marginBottom = '10px';
                        container.appendChild(img);

                        // Add device information below the barcode
                        const deviceInfo = JSON.parse(data.barcodeData);
                        const infoDiv = document.createElement('div');
                        infoDiv.style.fontSize = '12px';
                        infoDiv.style.textAlign = 'center';

                        infoDiv.innerHTML = `
                        <div><strong>${deviceInfo.name}</strong></div>
                        <div>Model: ${deviceInfo.model || 'N/A'}</div>
                        <div>Brand: ${deviceInfo.brand || 'N/A'}</div>
                        <div>Serial: ${deviceInfo.serial}</div>
                        <div>Barcode: ${deviceInfo.barcode}</div>
                    `;

                        container.appendChild(infoDiv);
                        preview.appendChild(container);

                    } catch (error) {
                        console.error('Barcode generation failed:', error);
                    }
                });
            });
        </script>
    @endpush
</div>
