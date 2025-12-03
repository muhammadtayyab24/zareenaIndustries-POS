@extends('layout.app')

@section('title', 'Sales Orders (Tax) | Zareena Industries')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Sales Orders (Tax)</h4>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('sales-orders.tax.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-1"></i> Create New Sales Order
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table mb-0 table-centered">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice No</th>
                                <th>Customer</th>
                                <th>PO No</th>
                                <th>Warehouse</th>
                                <th>Subtotal</th>
                                <th>Total GST</th>
                                <th>Grand Total</th>
                                <th>Date</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                                <tr>
                                    <td>{{ $sale->invoice_no }}</td>
                                    <td>{{ $sale->customer->name ?? 'N/A' }}</td>
                                    <td>{{ $sale->po_no ?? 'N/A' }}</td>
                                    <td>{{ $sale->warehouse->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($sale->subtotal, 2) }}</td>
                                    <td>{{ number_format($sale->total_gst, 2) }}</td>
                                    <td><strong>{{ number_format($sale->grand_total, 2) }}</strong></td>
                                    <td>{{ $sale->created_at->format('Y-m-d') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('sales-orders.print', $sale->id) }}" 
                                           class="btn btn-sm btn-primary" data-bs-toggle="tooltip" 
                                           data-bs-placement="top" title="Print" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No sales orders found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($sales->hasPages())
                    <div class="mt-3">
                        {{ $sales->links() }}
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

