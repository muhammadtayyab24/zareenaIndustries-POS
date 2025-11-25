@extends('layout.app')

@section('title', 'Purchase Orders (Non-Tax) | Zareena Industries')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Purchase Orders (Non-Tax)</h4>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('purchase-orders.non-tax.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-1"></i> Create New Purchase Order
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table mb-0 table-centered">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>PO No</th>
                                <th>Vendor</th>
                                <th>Vendor Invoice No</th>
                                <th>Warehouse</th>
                                <th>Subtotal</th>
                                <th>Grand Total</th>
                                <th>Date</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchases as $purchase)
                                <tr>
                                    <td>{{ $purchase->id }}</td>
                                    <td>{{ $purchase->po_no ?? 'N/A' }}</td>
                                    <td>{{ $purchase->vendor->name ?? 'N/A' }}</td>
                                    <td>{{ $purchase->vendor_invoice_no }}</td>
                                    <td>{{ $purchase->warehouse->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($purchase->subtotal, 2) }}</td>
                                    <td><strong>{{ number_format($purchase->grand_total, 2) }}</strong></td>
                                    <td>{{ $purchase->created_at->format('Y-m-d') }}</td>
                                    <td class="text-end">
                                        {{--  <a href="{{ route('purchase-orders.show', $purchase->id) }}" 
                                           class="btn btn-sm btn-info" data-bs-toggle="tooltip" 
                                           data-bs-placement="top" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>  --}}
                                        <a href="{{ route('purchase-orders.print', $purchase->id) }}" 
                                           class="btn btn-sm btn-primary" data-bs-toggle="tooltip" 
                                           data-bs-placement="top" title="Print" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No purchase orders found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($purchases->hasPages())
                    <div class="mt-3">
                        {{ $purchases->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endpush

