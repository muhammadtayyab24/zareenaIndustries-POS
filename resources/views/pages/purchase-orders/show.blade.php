@extends('layout.app')

@section('title', 'Purchase Order Details | Zareena Industries')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Purchase Order Details</h4>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $purchase->type)) }}</p>
                        <p><strong>Vendor:</strong> {{ $purchase->vendor->name ?? 'N/A' }}</p>
                        <p><strong>Warehouse:</strong> {{ $purchase->warehouse->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Vendor Invoice No:</strong> {{ $purchase->vendor_invoice_no }}</p>
                        <p><strong>PO No:</strong> {{ $purchase->po_no ?? 'N/A' }}</p>
                        <p><strong>GRN No:</strong> {{ $purchase->grn_no ?? 'N/A' }}</p>
                        <p><strong>Credit Term:</strong> {{ $purchase->credit_term ?? 'N/A' }}</p>
                        <p><strong>Due Date:</strong> {{ $purchase->due_date ? $purchase->due_date->format('Y-m-d') : 'N/A' }}</p>
                    </div>
                </div>
                
                <hr>
                
                <h5 class="mb-3">Products</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Unit Type</th>
                                <th>Qty</th>
                                <th>Price</th>
                                @if($purchase->type === 'tax')
                                    <th>GST %</th>
                                    <th>Net Amount</th>
                                    <th>GST Amount</th>
                                @endif
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchase->products as $product)
                                <tr>
                                    <td>{{ $product->product->product_name ?? 'N/A' }}</td>
                                    <td>{{ $product->unit_type ?? 'N/A' }}</td>
                                    <td>{{ number_format($product->qty, 2) }}</td>
                                    <td>{{ number_format($product->price, 2) }}</td>
                                    @if($purchase->type === 'tax')
                                        <td>{{ $product->gst_percentage ? number_format($product->gst_percentage, 2) . '%' : 'N/A' }}</td>
                                        <td>{{ number_format($product->net_amount, 2) }}</td>
                                        <td>{{ number_format($product->gst_amount, 2) }}</td>
                                    @endif
                                    <td>{{ number_format($product->total_amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-6 offset-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <td><strong>Subtotal:</strong></td>
                                <td class="text-end">{{ number_format($purchase->subtotal, 2) }}</td>
                            </tr>
                            @if($purchase->type === 'tax')
                                <tr>
                                    <td><strong>Total GST:</strong></td>
                                    <td class="text-end">{{ number_format($purchase->total_gst, 2) }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td><strong>Labour Charges:</strong></td>
                                <td class="text-end">{{ number_format($purchase->labour_charges, 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Freight Charges:</strong></td>
                                <td class="text-end">{{ number_format($purchase->freight_charges, 2) }}</td>
                            </tr>
                            <tr class="table-primary">
                                <td><strong>Grand Total:</strong></td>
                                <td class="text-end"><strong>{{ number_format($purchase->grand_total, 2) }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-12 text-end">
                        <a href="{{ $purchase->type === 'tax' ? route('purchase-orders.tax.index') : route('purchase-orders.non-tax.index') }}" 
                           class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

