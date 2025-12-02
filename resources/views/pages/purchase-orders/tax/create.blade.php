@extends('layout.app')

@section('title', 'Create Purchase Order (Tax) | Zareena Industries')

@push('styles')
    <link href="{{ asset('assets/libs/mobius1-selectr/selectr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/vanillajs-datepicker/css/datepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        #vendorDropdown {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            background-color: #fff;
        }

        #vendorDropdown .dropdown-item {
            cursor: pointer;
            padding: 0.5rem 1rem;
        }

        #vendorDropdown .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        #warehouseDropdown {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            background-color: #fff;
        }

        #warehouseDropdown .dropdown-item {
            cursor: pointer;
            padding: 0.5rem 1rem;
        }

        #warehouseDropdown .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        #creditLedgerArea {
            border: 1px dashed #dee2e6;
            border-radius: 0.375rem;
            padding: 1rem;
            background-color: #f8f9fa;
        }

        .product-dropdown {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            background-color: #fff;
        }

        .product-dropdown .dropdown-item {
            cursor: pointer;
            padding: 0.5rem 1rem;
        }

        .product-dropdown .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .product-dropdown .dropdown-item strong {
            color: #495057;
            margin-right: 0.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                    <h4 class="card-title">Create Purchase Order (Tax)</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <form id="purchaseOrderForm">
                        @csrf
                        <input type="hidden" name="type" value="tax">

                        <!-- Top Invoice Section -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="mb-2">Invoice Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="vendor_invoice_no"
                                        name="vendor_invoice_no" placeholder="Enter Invoice Number" required>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="flex-grow-1">
                                            <label class="mb-2">PO Number</label>
                                            <input type="text" class="form-control" id="po_no" name="po_no"
                                                placeholder="Enter PO Number" maxlength="255">
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <label class="mb-2">GRN Number</label>
                                            <input type="text" class="form-control" id="grn_no" name="grn_no"
                                                placeholder="Enter GRN Number">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="mb-2">Invoice Date <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="invoice_date" name="invoice_date"
                                        placeholder="Select Invoice Date" readonly required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="mb-2">Vendor <span class="text-danger">*</span></label>
                                    <div class="input-group position-relative">
                                        <input type="text" class="form-control" id="vendor_search" name="vendor_search"
                                            placeholder="Search vendor..." autocomplete="off">
                                        <input type="hidden" id="vendor_id" name="vendor_id" required>
                                        <button type="button" class="btn btn-primary" id="addVendorBtn"
                                            title="Add New Vendor">
                                            <i class="las la-plus"></i>
                                        </button>
                                        <div id="vendorDropdown" class="dropdown-menu position-absolute w-100"
                                            style="display: none; max-height: 200px; overflow-y: auto; top: 100%; left: 0; z-index: 1000;">
                                            <!-- Vendor dropdown items will be populated here -->
                                        </div>
                            </div>
                        </div>
                        
                                <!-- Empty area for credit ledger (to be added later) -->
                                <div id="creditLedgerArea" class="mb-3" style="min-height: 100px;">
                                    <!-- Credit ledger will be added here later -->
                            </div>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <!-- Products Table -->
                        <h5 class="mb-3">Products</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="productsTable"
                                style="table-layout: fixed; width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="width: 25%;">Product</th>
                                        <th style="width: 10%;">Unit Type</th>
                                        <th style="width: 8%;">Qty</th>
                                        <th style="width: 10%;">Rate</th>
                                        <th style="width: 10%;">GST %</th>
                                        <th style="width: 12%;">Exc Sales Tax Amount</th>
                                        <th style="width: 12%;">GST Amount</th>
                                        <th style="width: 13%;">Total Amount</th>
                                        <th style="width: 7%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="productsTableBody">
                                    <!-- Product rows will be added here -->
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm mt-2" id="addProductBtn">
                            <i class="las la-plus"></i> Add Product
                        </button>
                        
                        <hr class="my-4">
                        
                        <!-- Totals Section -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="mb-2">Warehouse</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control" id="warehouse_search"
                                            placeholder="Search warehouse..." autocomplete="off">
                                        <input type="hidden" id="warehouse_id" name="warehouse_id" value="">
                                        <div id="warehouseDropdown" class="dropdown-menu position-absolute w-100"
                                            style="display: none; max-height: 200px; overflow-y: auto; top: 100%; left: 0; z-index: 1000;">
                                            <!-- Warehouse options -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <td><strong>Subtotal:</strong></td>
                                        <td class="text-end" id="subtotal">0.00</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total GST:</strong></td>
                                        <td class="text-end" id="totalGst">0.00</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Adv. Inc. Tax:</strong></td>
                                        <td class="text-end">
                                            <div class="d-flex gap-2">
                                                <input type="number" class="form-control text-end" id="adv_inc_tax_percentage"
                                                    name="adv_inc_tax_percentage" value="0" min="0" max="100" step="0.01"
                                                    placeholder="%" style="width: 50%;">
                                                <input type="number" class="form-control text-end" id="adv_inc_tax_amount"
                                                    name="adv_inc_tax_amount" value="0" min="0" step="0.01" readonly
                                                    placeholder="Amount" style="width: 50%;">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Carriage and Freight:</strong></td>
                                        <td class="text-end">
                                            <input type="number" class="form-control text-end" id="freight_charges"
                                                name="freight_charges" value="0" min="0" step="0.01"
                                                style="width: 100%;">
                                        </td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td><strong>Grand Total:</strong></td>
                                        <td class="text-end"><strong id="grandTotal">0.00</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-12 text-end">
                                {{--  <button type="button" class="btn btn-secondary" id="printInvoiceBtn">
                                    <i class="las la-print"></i> Print Invoice
                                </button>  --}}
                                <button type="submit" class="btn btn-primary" id="saveBtn">
                                    <i class="las la-save"></i> Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Vendor Modal -->
    <div class="modal fade" id="addVendorModal" tabindex="-1" aria-labelledby="addVendorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addVendorModalLabel">Add New Vendor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addVendorForm">
                        @csrf
                        <div class="mb-3">
                            <label for="vendor_name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="vendor_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="vendor_type" class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="vendor_type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="Cash">Cash</option>
                                <option value="Credit">Credit</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="vendor_contact" class="form-label">Contact</label>
                            <input type="text" class="form-control" id="vendor_contact" name="contact">
                        </div>
                        <div class="mb-3">
                            <label for="vendor_ntn" class="form-label">NTN</label>
                            <input type="text" class="form-control" id="vendor_ntn" name="ntn">
                        </div>
                        <div class="mb-3">
                            <label for="vendor_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="vendor_email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="vendor_address" class="form-label">Address</label>
                            <textarea class="form-control" id="vendor_address" name="address" rows="3"></textarea>
                        </div>
                        <input type="hidden" name="status" value="1">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveVendorBtn">
                        <i class="las la-save"></i> Save Vendor
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="successModalLabel">
                        <i class="las la-check-circle"></i> Success
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <i class="las la-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h4>Purchase Order Created Successfully!</h4>
                    <p class="text-muted">Your purchase order has been saved successfully.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        onclick="window.location.href='{{ route('purchase-orders.tax.index') }}'">
                        <i class="las la-list"></i> View All Orders
                    </button>
                    <button type="button" class="btn btn-primary" id="printInvoiceModalBtn">
                        <i class="las la-print"></i> Print Invoice
                    </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/libs/mobius1-selectr/selectr.min.js') }}"></script>
    <script src="{{ asset('assets/libs/vanillajs-datepicker/js/datepicker-full.min.js') }}"></script>
<script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Invoice Date Datepicker
            const invoiceDatePicker = new Datepicker(document.getElementById('invoice_date'), {
                format: 'yyyy-mm-dd',
                autohide: true
            });

            // Set default to today's date for invoice date
            const today = new Date();
            const todayStr = today.getFullYear() + '-' +
                String(today.getMonth() + 1).padStart(2, '0') + '-' +
                String(today.getDate()).padStart(2, '0');
            document.getElementById('invoice_date').value = todayStr;

            // Vendor Search Functionality
            const vendorSearch = document.getElementById('vendor_search');
            const vendorIdInput = document.getElementById('vendor_id');
            const vendorDropdown = document.getElementById('vendorDropdown');
            const vendors = @json($vendors);
            const warehousesList = @json($warehouses);
            let filteredVendors = vendors;

            // Filter vendors based on search
            function filterVendors(query) {
                if (!query || query.trim() === '') {
                    filteredVendors = vendors;
                    vendorDropdown.style.display = 'none';
                    return;
                }

                const searchTerm = query.toLowerCase();
                filteredVendors = vendors.filter(vendor =>
                    vendor.name.toLowerCase().includes(searchTerm) ||
                    (vendor.contact && vendor.contact.toLowerCase().includes(searchTerm)) ||
                    (vendor.email && vendor.email.toLowerCase().includes(searchTerm))
                );

                displayVendorDropdown();
            }

            // Display vendor dropdown
            function displayVendorDropdown() {
                if (filteredVendors.length === 0) {
                    vendorDropdown.innerHTML = '<div class="dropdown-item-text text-muted">No vendors found</div>';
                } else {
                    vendorDropdown.innerHTML = filteredVendors.map(vendor =>
                        `<a class="dropdown-item" href="#" data-vendor-id="${vendor.id}" data-vendor-name="${vendor.name}">${vendor.name}</a>`
                    ).join('');
                }

                vendorDropdown.style.display = 'block';

                // Add click handlers to dropdown items
                vendorDropdown.querySelectorAll('.dropdown-item').forEach(item => {
                    item.addEventListener('click', function(e) {
                        e.preventDefault();
                        const vendorId = this.dataset.vendorId;
                        const vendorName = this.dataset.vendorName;
                        vendorSearch.value = vendorName;
                        vendorIdInput.value = vendorId;
                        vendorDropdown.style.display = 'none';
                    });
                });
            }

            // Handle vendor search input
            vendorSearch.addEventListener('input', function() {
                filterVendors(this.value);
            });

            // Hide dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!vendorSearch.contains(e.target) && !vendorDropdown.contains(e.target)) {
                    vendorDropdown.style.display = 'none';
                }
            });

            // Clear vendor selection when search is cleared
            vendorSearch.addEventListener('blur', function() {
                setTimeout(() => {
                    if (!vendorIdInput.value) {
                        vendorSearch.value = '';
                    }
                }, 200);
            });

            // Warehouse Search Functionality
            const warehouseSearch = document.getElementById('warehouse_search');
            const warehouseIdInput = document.getElementById('warehouse_id');
            const warehouseDropdown = document.getElementById('warehouseDropdown');
            let filteredWarehouses = warehousesList;

            if (warehouseSearch && warehouseDropdown) {
                // Populate search field if a warehouse is already selected (e.g., old input)
                if (warehouseIdInput.value) {
                    const existingWarehouse = warehousesList.find(
                        warehouse => warehouse.id == warehouseIdInput.value
                    );
                    if (existingWarehouse) {
                        warehouseSearch.value = existingWarehouse.name;
                    }
                }

                function filterWarehouses(query) {
                    if (!query || query.trim() === '') {
                        filteredWarehouses = warehousesList;
                        warehouseDropdown.style.display = 'none';
                        return;
                    }

                    const term = query.toLowerCase();
                    filteredWarehouses = warehousesList.filter(warehouse =>
                        warehouse.name.toLowerCase().includes(term) ||
                        warehouse.id.toString().includes(term)
                    );

                    displayWarehouseDropdown();
                }

                function displayWarehouseDropdown() {
                    if (filteredWarehouses.length === 0) {
                        warehouseDropdown.innerHTML =
                            '<div class="dropdown-item-text text-muted">No warehouses found</div>';
                    } else {
                        warehouseDropdown.innerHTML = filteredWarehouses.map(warehouse =>
                            `<a class="dropdown-item" href="#" data-warehouse-id="${warehouse.id}" data-warehouse-name="${warehouse.name}">
                                ${warehouse.name}
                            </a>`
                        ).join('');
                    }

                    warehouseDropdown.style.display = 'block';

                    warehouseDropdown.querySelectorAll('.dropdown-item').forEach(item => {
                        item.addEventListener('click', function(e) {
                            e.preventDefault();
                            const id = this.dataset.warehouseId;
                            const name = this.dataset.warehouseName;
                            warehouseIdInput.value = id;
                            warehouseSearch.value = name;
                            warehouseDropdown.style.display = 'none';
                        });
                    });
                }

                warehouseSearch.addEventListener('input', function() {
                    filterWarehouses(this.value);
                });

                document.addEventListener('click', function(e) {
                    if (!warehouseSearch.contains(e.target) && !warehouseDropdown.contains(e.target)) {
                        warehouseDropdown.style.display = 'none';
                    }
                });

                warehouseSearch.addEventListener('blur', function() {
                    setTimeout(() => {
                        if (!warehouseIdInput.value) {
                            warehouseSearch.value = '';
                        }
                    }, 200);
                });
            }

            // Add Vendor Modal
            const addVendorBtn = document.getElementById('addVendorBtn');
            const addVendorModal = new bootstrap.Modal(document.getElementById('addVendorModal'));
            const saveVendorBtn = document.getElementById('saveVendorBtn');
            const addVendorForm = document.getElementById('addVendorForm');

            addVendorBtn.addEventListener('click', function() {
                addVendorForm.reset();
                addVendorModal.show();
            });

            // Save new vendor
            saveVendorBtn.addEventListener('click', async function() {
                const formData = new FormData(addVendorForm);

                // Validation
                if (!formData.get('name') || !formData.get('type')) {
                    alert('Please fill in all required fields');
                    return;
                }

                saveVendorBtn.disabled = true;
                saveVendorBtn.innerHTML = '<i class="las la-spinner la-spin"></i> Saving...';

                try {
                    const response = await fetch('{{ route('vendors.store') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    let result;
                    try {
                        result = await response.json();
                    } catch (e) {
                        // If response is not JSON, it might be a redirect
                        if (response.redirected) {
                            alert('Vendor created successfully! Please refresh the page.');
                            location.reload();
                            return;
                        }
                        throw new Error('Invalid response from server');
                    }

                    if (response.ok && result.success) {
                        // Add new vendor to the list
                        const vendor = result.vendor || result.data;
                        const newVendor = {
                            id: vendor.id,
                            name: vendor.name,
                            contact: vendor.contact || '',
                            email: vendor.email || ''
                        };
                        vendors.push(newVendor);

                        // Select the newly created vendor
                        vendorSearch.value = newVendor.name;
                        vendorIdInput.value = newVendor.id;

                        // Close modal
                        addVendorModal.hide();
                        addVendorForm.reset();

                        alert('Vendor created successfully!');
                    } else {
                        let errorMsg = 'Error creating vendor';
                        if (result.errors) {
                            errorMsg = Object.values(result.errors).flat().join('\n');
                        } else if (result.message) {
                            errorMsg = result.message;
                        }
                        alert(errorMsg);
                    }
                } catch (error) {
                    alert('An error occurred while creating vendor');
                    console.error('Error:', error);
                } finally {
                    saveVendorBtn.disabled = false;
                    saveVendorBtn.innerHTML = '<i class="las la-save"></i> Save Vendor';
                }
            });

            // Products data
            const products = @json($products);
            let productRowIndex = 0;

            // Add product row
            function addProductRow() {
                const tbody = document.getElementById('productsTableBody');
                const row = document.createElement('tr');
                row.id = `productRow_${productRowIndex}`;

                row.innerHTML = `
            <td>
                <div class="position-relative">
                    <input type="text" class="form-control product-search" data-index="${productRowIndex}" placeholder="Search product by code or name..." autocomplete="off">
                    <input type="hidden" class="product-id-input" name="products[${productRowIndex}][product_id]" value="">
                    <input type="hidden" class="product-code-display" name="products[${productRowIndex}][product_code]" value="">
                    <input type="hidden" class="product-name-display" name="products[${productRowIndex}][product_name]" value="">
                    <div class="product-dropdown dropdown-menu position-absolute w-100" style="display: none; max-height: 200px; overflow-y: auto; top: 100%; left: 0; z-index: 1000;">
                        <!-- Product dropdown items will be populated here -->
                    </div>
                </div>
            </td>
            <td>
                <input type="text" class="form-control unit-type" name="products[${productRowIndex}][unit_type]" readonly>
            </td>
            <td>
                <input type="number" class="form-control qty" name="products[${productRowIndex}][qty]" value="0" min="0" step="0.01" data-index="${productRowIndex}">
            </td>
            <td>
                <input type="number" class="form-control price" name="products[${productRowIndex}][price]" value="0" min="0" step="0.01" data-index="${productRowIndex}">
            </td>
            <td>
                <input type="number" class="form-control gst-percentage" name="products[${productRowIndex}][gst_percentage]" value="0" min="0" max="100" step="0.01" data-index="${productRowIndex}">
            </td>
            <td>
                <input type="text" class="form-control net-amount" name="products[${productRowIndex}][net_amount]" value="0.00" readonly>
            </td>
            <td>
                <input type="text" class="form-control gst-amount" name="products[${productRowIndex}][gst_amount]" value="0.00" readonly>
            </td>
            <td>
                <input type="text" class="form-control total-amount" name="products[${productRowIndex}][total_amount]" value="0.00" readonly>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-product" data-index="${productRowIndex}">
                    <i class="las la-times"></i>
                </button>
            </td>
        `;

                tbody.appendChild(row);

                // Product search functionality (similar to vendor search)
                const productSearchInput = row.querySelector('.product-search');
                const productDropdown = row.querySelector('.product-dropdown');
                const productIdInput = row.querySelector('.product-id-input');
                const productCodeDisplay = row.querySelector('.product-code-display');
                const productNameDisplay = row.querySelector('.product-name-display');
                const unitTypeInput = row.querySelector('.unit-type');
                let filteredProducts = products;

                // Filter products based on search (by ID or name)
                function filterProducts(query, index) {
                    if (!query || query.trim() === '') {
                        filteredProducts = products;
                        productDropdown.style.display = 'none';
                        return;
                    }

                    const searchTerm = query.toLowerCase();
                    filteredProducts = products.filter(product =>
                        product.id.toString().includes(searchTerm) ||
                        product.product_name.toLowerCase().includes(searchTerm)
                    );

                    displayProductDropdown(index);
                }

                // Display product dropdown
                function displayProductDropdown(index) {
                    if (filteredProducts.length === 0) {
                        productDropdown.innerHTML =
                            '<div class="dropdown-item-text text-muted">No products found</div>';
                    } else {
                        productDropdown.innerHTML = filteredProducts.map(product =>
                            `<a class="dropdown-item" href="#" data-product-id="${product.id}" data-product-code="${product.id}" data-product-name="${product.product_name}" data-unit-type="${product.unit_type || ''}">
                                <strong>${product.id}</strong> - ${product.product_name}
                            </a>`
                        ).join('');
                    }

                    productDropdown.style.display = 'block';

                    // Add click handlers to dropdown items
                    productDropdown.querySelectorAll('.dropdown-item').forEach(item => {
                        item.addEventListener('click', function(e) {
                            e.preventDefault();
                            const productId = this.dataset.productId;
                            const productCode = this.dataset.productCode;
                            const productName = this.dataset.productName;
                            const unitType = this.dataset.unitType || '';

                            // Update all fields
                            productSearchInput.value = `${productCode} - ${productName}`;
                            productIdInput.value = productId;
                            productCodeDisplay.value = productCode;
                            productNameDisplay.value = productName;
                            unitTypeInput.value = unitType;

                            productDropdown.style.display = 'none';
                            calculateRowTotal(index);
                        });
                    });
                }

                // Handle product search input
                productSearchInput.addEventListener('input', function() {
                    const index = parseInt(this.dataset.index);
                    filterProducts(this.value, index);
                });

                // Hide dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!productSearchInput.contains(e.target) && !productDropdown.contains(e.target)) {
                        productDropdown.style.display = 'none';
                    }
                });

                // Clear product selection when search is cleared
                productSearchInput.addEventListener('blur', function() {
                    setTimeout(() => {
                        if (!productIdInput.value) {
                            productSearchInput.value = '';
                        }
                    }, 200);
                });

                // Handle qty, price, and gst_percentage changes
                row.querySelector('.qty').addEventListener('input', function() {
                    calculateRowTotal(parseInt(this.dataset.index));
                });

                row.querySelector('.price').addEventListener('input', function() {
                    calculateRowTotal(parseInt(this.dataset.index));
                });

                row.querySelector('.gst-percentage').addEventListener('input', function() {
                    calculateRowTotal(parseInt(this.dataset.index));
                });

                // Handle remove button
                row.querySelector('.remove-product').addEventListener('click', function() {
                    const index = parseInt(this.dataset.index);
                    document.getElementById(`productRow_${index}`).remove();
                    calculateTotals();
                });

                productRowIndex++;
            }

            // Calculate row total (for tax invoices)
            function calculateRowTotal(index) {
                const row = document.getElementById(`productRow_${index}`);
                if (!row) return;

                const qty = parseFloat(row.querySelector('.qty').value) || 0;
                const price = parseFloat(row.querySelector('.price').value) || 0;
                const gstPercentage = parseFloat(row.querySelector('.gst-percentage').value) || 0;

                // Net amount = qty * price
            const netAmount = qty * price;
            
                // GST amount = (net amount * gst percentage) / 100
            const gstAmount = (netAmount * gstPercentage) / 100;
                
                // Total amount = net amount + gst amount
            const totalAmount = netAmount + gstAmount;
            
                row.querySelector('.net-amount').value = netAmount.toFixed(2);
                row.querySelector('.gst-amount').value = gstAmount.toFixed(2);
                row.querySelector('.total-amount').value = totalAmount.toFixed(2);
                calculateTotals();
            }

            // Calculate totals
            function calculateTotals() {
                let subtotal = 0;
                let totalGst = 0;
                
                document.querySelectorAll('#productsTableBody tr').forEach(row => {
                    const netAmount = parseFloat(row.querySelector('.net-amount').value) || 0;
                    const gstAmount = parseFloat(row.querySelector('.gst-amount').value) || 0;
                    subtotal += netAmount;
                    totalGst += gstAmount;
                });

                // Calculate Adv. Inc. Tax
                const advIncTaxPercentage = parseFloat(document.getElementById('adv_inc_tax_percentage').value) || 0;
                const advIncTaxAmount = (subtotal * advIncTaxPercentage) / 100;
                document.getElementById('adv_inc_tax_amount').value = advIncTaxAmount.toFixed(2);

                const freightCharges = parseFloat(document.getElementById('freight_charges').value) || 0;
                const grandTotal = subtotal + totalGst + advIncTaxAmount + freightCharges;

                document.getElementById('subtotal').textContent = subtotal.toFixed(2);
                document.getElementById('totalGst').textContent = totalGst.toFixed(2);
                document.getElementById('grandTotal').textContent = grandTotal.toFixed(2);
            }

            // Add product button
            document.getElementById('addProductBtn').addEventListener('click', function() {
                addProductRow();
            });

            // Adv. Inc. Tax and Freight charges change
            document.getElementById('adv_inc_tax_percentage').addEventListener('input', calculateTotals);
            document.getElementById('freight_charges').addEventListener('input', calculateTotals);

            // Form submission
            document.getElementById('purchaseOrderForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                // Validation
                const vendorIdInput = document.getElementById('vendor_id');
                if (!vendorIdInput || !vendorIdInput.value) {
                    alert('Please select a vendor');
                    return;
                }

                if (!document.getElementById('vendor_invoice_no').value.trim()) {
                    alert('Please enter vendor invoice number');
                    return;
                }

                const productRows = document.querySelectorAll('#productsTableBody tr');
                if (productRows.length === 0) {
                    alert('Please add at least one product');
                    return;
                }

                // Validate products
                let isValid = true;
                productRows.forEach((row, index) => {
                    const productIdInput = row.querySelector('.product-id-input');
                    const productId = productIdInput ? productIdInput.value : '';
                    const qty = parseFloat(row.querySelector('.qty').value) || 0;
                    const price = parseFloat(row.querySelector('.price').value) || 0;

                    if (!productId) {
                        alert(`Please select a product for row ${index + 1}`);
                        isValid = false;
                        return;
                    }
                    if (qty <= 0) {
                        alert(`Please enter valid quantity for row ${index + 1}`);
                        isValid = false;
                        return;
                    }
                    if (price <= 0) {
                        alert(`Please enter valid price for row ${index + 1}`);
                        isValid = false;
                        return;
                    }
                });

                if (!isValid) return;

                // Prepare form data
                const formData = new FormData(this);
                const data = {};

                // Convert FormData to object
                for (let [key, value] of formData.entries()) {
                    if (key.startsWith('products[')) {
                        const match = key.match(/products\[(\d+)\]\[(\w+)\]/);
                        if (match) {
                            const index = match[1];
                            const field = match[2];
                            if (!data.products) data.products = [];
                            if (!data.products[index]) data.products[index] = {};
                            data.products[index][field] = value;
                        }
                    } else {
                        data[key] = value;
                    }
                }

                // Convert products array
                if (data.products) {
                    data.products = Object.values(data.products);
                }

                // Submit
                const saveBtn = document.getElementById('saveBtn');
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<i class="las la-spinner la-spin"></i> Saving...';

                try {
                    const response = await fetch('{{ route('purchase-orders.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();

                    if (result.success) {
                        // Store purchase ID for printing
                        const purchaseId = result.data?.id || result.data?.purchase_id || result.data
                            ?.data?.id;
                        if (purchaseId) {
                            window.purchaseId = purchaseId;
                        } else {
                            // Try to extract from nested data
                            const data = result.data?.data || result.data;
                            if (data && data.id) {
                                window.purchaseId = data.id;
                            }
                        }

                        // Show success modal
                        const successModal = new bootstrap.Modal(document.getElementById(
                            'successModal'));
                        successModal.show();

                        // Reset form
                        document.getElementById('purchaseOrderForm').reset();
                        document.getElementById('productsTableBody').innerHTML = '';
                        productRowIndex = 0;
                        addProductRow();

                        // Reset save button
                        saveBtn.disabled = false;
                        saveBtn.innerHTML = '<i class="las la-save"></i> Save Only';
                } else {
                        alert(result.message || 'Error creating purchase order');
                        saveBtn.disabled = false;
                        saveBtn.innerHTML = '<i class="las la-save"></i> Save Only';
                    }
                } catch (error) {
                    alert('An error occurred while creating purchase order');
                    console.error('Error:', error);
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<i class="las la-save"></i> Save Only';
                }
            });

            // Print invoice button (from form)
            document.getElementById('printInvoiceBtn').addEventListener('click', function() {
                if (window.purchaseId) {
                    window.open(`{{ url('purchase-orders') }}/${window.purchaseId}/print`, '_blank');
                } else {
                    alert('Please save the purchase order first');
                }
            });

            // Print invoice button (from success modal)
            document.getElementById('printInvoiceModalBtn').addEventListener('click', function() {
                if (window.purchaseId) {
                    window.open(`{{ url('purchase-orders') }}/${window.purchaseId}/print`, '_blank');
                }
            });

            // Add initial product row
            addProductRow();
});
</script>
@endpush
