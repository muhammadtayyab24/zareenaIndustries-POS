@extends('layout.app')

@section('title', 'Warehouse Inventory Management | Zareena Industries')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Warehouse Inventory Management</h4>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm border-theme-white-2" role="alert" data-auto-dismiss="5000">
                        <div class="d-inline-flex justify-content-center align-items-center thumb-xs bg-success rounded-circle mx-auto me-1">
                            <i class="fas fa-check align-self-center mb-0 text-white"></i>
                        </div>
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-theme-white-2" role="alert" data-auto-dismiss="5000">
                        <div class="d-inline-flex justify-content-center align-items-center thumb-xs bg-danger rounded-circle mx-auto me-1">
                            <i class="fas fa-xmark align-self-center mb-0 text-white"></i>
                        </div>
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Warehouse Selector -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label for="warehouse_select" class="form-label">Select Warehouse</label>
                        <select class="form-select" id="warehouse_select" name="warehouse_id">
                            <option value="">-- Select Warehouse --</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" 
                                    {{ isset($warehouse) && $warehouse->id == $wh->id ? 'selected' : '' }}>
                                    {{ $wh->name }} @if($wh->code) ({{ $wh->code }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if(isset($warehouse))
                    <div class="col-md-8">
                        <div class="mt-4">
                            <h5 class="mb-2">Warehouse Details:</h5>
                            <p class="mb-1"><strong>Name:</strong> {{ $warehouse->name }}</p>
                            @if($warehouse->code)
                                <p class="mb-1"><strong>Code:</strong> {{ $warehouse->code }}</p>
                            @endif
                            @if($warehouse->address)
                                <p class="mb-0"><strong>Address:</strong> {{ $warehouse->address }}</p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Loading Indicator -->
                <div id="loading_indicator" class="text-center py-5" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading inventory data...</p>
                </div>

                <!-- Inventory Table -->
                <div id="inventory_table_container" style="{{ !isset($products) ? 'display: none;' : '' }}">
                    <div class="mb-3" id="totals_section">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-0"><strong>Total Products:</strong> <span id="total_products">0</span></p>
                            </div>
                            <div class="col-md-6 text-end">
                                <p class="mb-0"><strong>Total Stock Value:</strong> <span id="total_stock">0.00</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table mb-0 table-centered" id="inventory_table">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Type</th>
                                    <th>Unit Type</th>
                                    <th class="text-end">Stock Quantity</th>
                                </tr>
                            </thead>
                            <tbody id="inventory_table_body">
                                @if(isset($products) && $products->count() > 0)
                                    @foreach($products as $index => $product)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $product['product_name'] }}</td>
                                            <td>{{ $product['category'] }}</td>
                                            <td>{{ $product['type'] }}</td>
                                            <td>{{ $product['unit_type'] }}</td>
                                            <td class="text-end">
                                                <span class="badge {{ $product['stock_qty_raw'] > 0 ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $product['stock_qty'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @elseif(isset($products) && $products->count() == 0)
                                    <tr>
                                        <td colspan="6" class="text-center">No products found in this warehouse.</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">Please select a warehouse to view inventory.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const warehouseSelect = document.getElementById('warehouse_select');
        const loadingIndicator = document.getElementById('loading_indicator');
        const inventoryTableContainer = document.getElementById('inventory_table_container');
        const inventoryTableBody = document.getElementById('inventory_table_body');

        warehouseSelect.addEventListener('change', function() {
            const warehouseId = this.value;
            
            if (!warehouseId) {
                inventoryTableContainer.style.display = 'none';
                inventoryTableBody.innerHTML = '<tr><td colspan="6" class="text-center">Please select a warehouse to view inventory.</td></tr>';
                const totalsSection = document.getElementById('totals_section');
                if (totalsSection) {
                    totalsSection.style.display = 'none';
                }
                return;
            }

            // Show loading indicator
            loadingIndicator.style.display = 'block';
            inventoryTableContainer.style.display = 'none';

            // Fetch inventory data via AJAX
            fetch(`/warehouses/inventory/${warehouseId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || 'Failed to load inventory data');
                    }).catch(() => {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                loadingIndicator.style.display = 'none';
                
                if (data.success) {
                    // Update warehouse details
                    const warehouseInfo = data.warehouse;
                    let warehouseDetailsHtml = `
                        <div class="mt-4">
                            <h5 class="mb-2">Warehouse Details:</h5>
                            <p class="mb-1"><strong>Name:</strong> ${warehouseInfo.name}</p>
                    `;
                    if (warehouseInfo.code) {
                        warehouseDetailsHtml += `<p class="mb-1"><strong>Code:</strong> ${warehouseInfo.code}</p>`;
                    }
                    if (warehouseInfo.address) {
                        warehouseDetailsHtml += `<p class="mb-0"><strong>Address:</strong> ${warehouseInfo.address}</p>`;
                    }
                    warehouseDetailsHtml += `</div>`;
                    
                    // Update warehouse details section
                    const detailsCol = warehouseSelect.closest('.row').querySelector('.col-md-8');
                    if (detailsCol) {
                        detailsCol.innerHTML = warehouseDetailsHtml;
                    }

                    // Update totals - check if elements exist first
                    const totalProductsEl = document.getElementById('total_products');
                    const totalStockEl = document.getElementById('total_stock');
                    if (totalProductsEl) {
                        totalProductsEl.textContent = data.total_products;
                    }
                    if (totalStockEl) {
                        totalStockEl.textContent = parseFloat(data.total_stock_value).toFixed(2);
                    }

                    // Update table body
                    if (data.products && data.products.length > 0) {
                        let tableRows = '';
                        data.products.forEach((product, index) => {
                            const badgeClass = parseFloat(product.stock_qty_raw) > 0 ? 'bg-success' : 'bg-secondary';
                            tableRows += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${product.product_name}</td>
                                    <td>${product.category}</td>
                                    <td>${product.type}</td>
                                    <td>${product.unit_type}</td>
                                    <td class="text-end">
                                        <span class="badge ${badgeClass}">${product.stock_qty}</span>
                                    </td>
                                </tr>
                            `;
                        });
                        inventoryTableBody.innerHTML = tableRows;
                    } else {
                        inventoryTableBody.innerHTML = '<tr><td colspan="6" class="text-center">No products found in this warehouse.</td></tr>';
                    }

                    // Show table and totals section
                    inventoryTableContainer.style.display = 'block';
                    const totalsSection = document.getElementById('totals_section');
                    if (totalsSection) {
                        totalsSection.style.display = 'block';
                    }
                } else {
                    alert('Error: ' + (data.message || 'Failed to load inventory data'));
                    inventoryTableContainer.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                console.error('Error message:', error.message);
                loadingIndicator.style.display = 'none';
                alert('Error: ' + (error.message || 'An error occurred while loading inventory data. Please check the console for details.'));
                inventoryTableContainer.style.display = 'none';
            });
        });

        // Auto-dismiss alerts after 5 seconds
        document.querySelectorAll('[data-auto-dismiss]').forEach(function(alert) {
            const delay = parseInt(alert.getAttribute('data-auto-dismiss'));
            setTimeout(function() {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, delay);
        });
    });
</script>
@endpush


