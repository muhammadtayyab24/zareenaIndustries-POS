<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Invoice - {{ $purchase->vendor_invoice_no }}</title>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            @page {
                margin: 1mm;
            }

            /* Force colors to print */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 10px;
            background: #fff;
        }

        .invoice-container {
            max-width: 210mm;
            margin: 0 auto;
            background: white;
            padding: 20px;
        }

        .invoice-header {
            text-align: center;
            padding-bottom: 10px;
        }

        .invoice-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .company-info {
            margin-top: 10px;
        }

        .company-info p {
            margin: 2px 0;
        }

        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }

        .invoice-details-left,
        .invoice-details-right {
            width: 48%;
        }

        .detail-row {
            display: flex;
            margin-bottom: 8px;
        }

        .detail-label {
            font-weight: bold;
            width: 150px;
        }

        .detail-value {
            flex: 1;
        }

        .vendor-info {
            margin: 20px 0;
            padding: 10px;
        }

        .invoice-details-right h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            text-transform: uppercase;
            padding-bottom: 5px;
        }

        .vendor-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .vendor-detail-item {
            margin-bottom: 5px;
        }

        .vendor-detail-label {
            font-weight: bold;
            display: inline-block;
            width: 100px;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .products-table th,
        .products-table td {
            padding: 8px;
            text-align: left;
        }

        .products-table th {
            background-color: #f0f0f0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
            font-weight: bold;
            text-align: center;
        }

        .products-table tr {
            border-bottom: 1px solid #000;
        }

        .products-table tfoot tr {
            border-bottom: none;
        }

        .products-table td {
            text-align: right;
        }

        .products-table td:first-child,
        .products-table td:nth-child(2) {
            text-align: left;
        }

        .products-table .text-center {
            text-align: center;
        }

        .summary-section {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        .summary-left {
            width: 50%;
        }

        .summary-right {
            width: 45%;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 8px;
        }

        .summary-table td:first-child {
            font-weight: bold;
            width: 60%;
        }

        .summary-table td:last-child {
            text-align: right;
            width: 40%;
        }

        .summary-table .total-row {
            background-color: #f0f0f0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
            font-weight: bold;
        }

        .summary-table tr {
            border-bottom: 1px solid #000;
        }

        .summary-table tr:last-child {
            border-bottom: none;
        }

        .amount-in-words {
            font-weight: bold;
        }

        .signatures {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            width: 45%;
            text-align: center;
        }

        .signature-line {
            margin-top: 30px;
            padding-top: 5px;
        }

        .print-button {
            text-align: center;
            margin-bottom: 20px;
        }

        .print-button button {
            padding: 10px 30px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .print-button button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="print-button no-print">
            <button onclick="window.print()">
                <i class="las la-print"></i> Print Invoice
            </button>
        </div>

        <div class="invoice-header">
            @if($purchase->company)
                @if($purchase->company->logo)
                    <img src="{{ Storage::url($purchase->company->logo) }}" alt="Logo" style="max-height: 80px; margin-bottom: 10px;">
                @endif
                <h1>{{ strtoupper($purchase->company->name) }}</h1>
                <div class="company-info">
                    @if($purchase->company->address)
                        <p>{{ $purchase->company->address }}</p>
                    @endif
                    <p>
                        @if($purchase->company->tel_no)Tel: {{ $purchase->company->tel_no }}@endif
                        @if($purchase->company->mobile_no && $purchase->company->tel_no) | @endif
                        @if($purchase->company->mobile_no)Mobile: {{ $purchase->company->mobile_no }}@endif
                        @if($purchase->company->email) | E-Mail: {{ $purchase->company->email }}@endif
                    </p>
                    @if ($purchase->type === 'tax')
                        @if($purchase->company->ntn || $purchase->company->strn)
                            <p>
                                @if($purchase->company->ntn)N.T.N.: {{ $purchase->company->ntn }}@endif
                                @if($purchase->company->ntn && $purchase->company->strn) | @endif
                                @if($purchase->company->strn)S.T.R.N: {{ $purchase->company->strn }}@endif
                            </p>
                        @endif
                    @endif
                </div>
            @else
                <h1>ZAREENA INDUSTRIES</h1>
                <div class="company-info">
                    <p>D-27 Manghopir Road, S.I.T.E. Karachi.</p>
                    <p>Tel: 92-021-32588033 | E-Mail: info@zareenaindustries.com</p>
                    @if ($purchase->type === 'tax')
                        <p>N.T.N.: 3238408-4 | S.T.R.N: 17-00-3238-408-14</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="invoice-details">
            <div class="invoice-details-left">
                <div style="margin-bottom: 10px;">
                    <table
                        style="width:100%; margin:0 auto; border-collapse:collapse; background:#f0f0f0 !important; border-radius: 5px; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; color-adjust: exact !important;">
                        <tr>
                            <td colspan="2"
                                style="text-align:center; font-size:13px; font-weight:bold; padding:5px 0 2px 0; letter-spacing:1px;">
                                @if ($purchase->type === 'tax')
                                    PURCHASE INVOICE (SALES TAX)
                                @else
                                    PURCHASE INVOICE
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="width:40%; text-align:center; font-size:11px; font-style:italic;">
                                Invoice No.
                            </td>
                            <td style="width:40%; text-align:center; font-size:11px; font-style:italic;">
                                Invoice Date
                            </td>
                        </tr>
                        <tr>
                            <td style="width:40%; text-align:center; font-size:12px; font-weight:bold;">
                                {{ $purchase->id }}
                            </td>
                            <td style="width:40%; text-align:center; font-size:12px; font-weight:bold;">
                                @if ($purchase->type === 'tax' && $purchase->due_date)
                                    {{ $purchase->due_date->format('d-m-Y') }}
                                @else
                                    {{ $purchase->created_at->format('d-m-Y') }}
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Vendor Invoice No:</span>
                    <span class="detail-value">{{ $purchase->vendor_invoice_no }}</span>
                </div>
                @if ($purchase->type === 'tax')
                    <div class="detail-row">
                        <span class="detail-label">Purchase Order No:</span>
                        <span class="detail-value"> _______________________ </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Purchase Order Date:</span>
                        <span class="detail-value"> _______________________ </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Delivery Challan No:</span>
                        <span class="detail-value"> _______________________ </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Delivery Challan Date:</span>
                        <span class="detail-value"> _______________________ </span>
                    </div>
                    {{--  <div class="detail-row">
                        <span class="detail-label">Due Date:</span>
                        <span class="detail-value"> _______________________ </span>
                    </div>  --}}
                @else
                    <div class="detail-row">
                        <span class="detail-label">PO Number:</span>
                        <span class="detail-value">{{ $purchase->po_no ?? ' _______________________ ' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">GRN Number:</span>
                        <span class="detail-value">{{ $purchase->grn_no ?? ' _______________________ ' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Vendor Invoice Date:</span>
                        <span
                            class="detail-value">{{ $purchase->due_date ? $purchase->due_date->format('d-m-Y') : $purchase->created_at->format('d-m-Y') ?? ' _______________________ ' }}</span>
                    </div>
                @endif
            </div>
            <div class="invoice-details-right">
                <h3>Vendor Details</h3>
                <div class="detail-row">
                    <span class="vendor-detail-label">Code:</span>
                    <span>{{ $purchase->vendor->id ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="vendor-detail-label">Name:</span>
                    <span>{{ $purchase->vendor->name ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="vendor-detail-label">Address:</span>
                    <span>{{ $purchase->vendor->address ?? 'N/A' }}</span>
                </div>
                @if ($purchase->type === 'tax')
                    <div class="detail-row">
                        <span class="vendor-detail-label">Phone:</span>
                        <span>{{ $purchase->vendor->contact ?? '' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="vendor-detail-label">N.T.N.:</span>
                        <span>{{ $purchase->vendor->ntn ?? '' }}</span>
                    </div>
                    {{--  <div class="detail-row">
                        <span class="vendor-detail-label">C.N.I.C. No.:</span>
                        <span></span>
                    </div>  --}}
                    <div class="detail-row">
                        <span class="vendor-detail-label">S.T.R.N.:</span>
                        <span>{{ $purchase->vendor->strn ?? '' }}</span>
                    </div>
                @else
                    <div class="detail-row">
                        <span class="vendor-detail-label">Phone:</span>
                        <span>{{ $purchase->vendor->contact ?? '' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="vendor-detail-label">N.T.N.:</span>
                        <span>{{ $purchase->vendor->ntn ?? '' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="vendor-detail-label">C.N.I.C. No.:</span>
                        <span></span>
                    </div>
                    <div class="detail-row">
                        <span class="vendor-detail-label">S.T.R.N.:</span>
                        <span>{{ $purchase->vendor->strn ?? '' }}</span>
                    </div>
                @endif


                {{--  <div class="detail-row">
                    <span class="detail-label">Purchase invoice No:</span>
                    <span class="detail-value">{{ $purchase->po_no ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Purchase invoice Date:</span>
                    <span
                        class="detail-value">{{ $purchase->po_no ? $purchase->created_at->format('d-m-Y') : 'N/A' }}</span>
                </div>  --}}
                {{--  @if ($purchase->credit_term)
                <div class="detail-row">
                    <span class="detail-label">Credit Term:</span>
                    <span class="detail-value">{{ $purchase->credit_term }}</span>
                </div>
                @endif  --}}
            </div>
        </div>



        <table class="products-table">
            <thead>
                <tr>
                    <th style="width: 5%;">S.No.</th>
                    <th style="width: @if ($purchase->type === 'tax') 25% @else 40% @endif;">Description</th>
                    <th style="width: 8%;">Quantity</th>
                    <th style="width: 8%;">Unit</th>
                    <th style="width: 10%;">Rate</th>
                    @if ($purchase->type === 'tax')
                        <th style="width: 12%;">Amt. Exc. Sales Tax</th>
                        <th colspan="2" style="width: 10%;">Sales Tax Rate/Amount</th>
                        <th style="width: 10%;">Amt. Inc. Sales Tax</th>
                    @else
                        <th style="width: 13%;">Amount</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($purchase->products as $index => $purchaseProduct)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $purchaseProduct->product->product_name ?? 'N/A' }}</td>
                        <td class="text-center">{{ number_format($purchaseProduct->qty, 2) }}</td>
                        <td class="text-center">{{ $purchaseProduct->unit_type ?? 'N/A' }}</td>
                        <td>{{ number_format($purchaseProduct->price, 2) }}</td>
                        @if ($purchase->type === 'tax')
                            <td>{{ number_format($purchaseProduct->net_amount ?? $purchaseProduct->qty * $purchaseProduct->price, 2) }}
                            </td>
                            <td class="text-center">{{ number_format($purchaseProduct->gst_percentage ?? 0, 2) }}%</td>
                            <td>{{ number_format($purchaseProduct->gst_amount ?? 0, 2) }}</td>
                            <td>{{ number_format($purchaseProduct->total_amount, 2) }}</td>
                        @else
                            <td>{{ number_format($purchaseProduct->total_amount, 2) }}</td>
                        @endif
                    </tr>
                @endforeach

            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-right"><strong>Total:</strong></td>
                    <td class="text-center"><strong>{{ number_format($purchase->products->sum('qty'), 2) }}</strong>
                    </td>
                    <td class="text-center">-</td>
                    <td class="text-center">-</td>
                    @if ($purchase->type === 'tax')
                        <td class="text-right"><strong>{{ number_format($purchase->subtotal, 2) }}</strong></td>
                        {{--  <td class="text-center">-</td>  --}}
                        <td colspan="2" class="text-center"><strong>{{ number_format($purchase->total_gst, 2) }}</strong></td>
                        <td class="text-right">
                            <strong>{{ number_format($purchase->products->sum('total_amount'), 2) }}</strong></td>
                    @else
                        <td class="text-right">
                            <strong>{{ number_format($purchase->products->sum('total_amount'), 2) }}</strong></td>
                    @endif
                </tr>
            </tfoot>
        </table>

        <div class="summary-section">
            <div class="summary-left">
                <strong>Note:</strong>
            </div>
            <div class="summary-right">
                <table class="summary-table">
                    @if ($purchase->type === 'tax')
                        <tr>
                            <td>Adv. Inc. Tax:</td>
                            <td>{{ number_format($purchase->adv_inc_tax_percentage ?? 0, 2) }}% Rs.
                                {{ number_format($purchase->adv_inc_tax_amount ?? 0, 2) }}</td>
                        </tr>
                        {{--  <tr>
                            <td>Carriage and Freight:</td>
                            <td>Rs. {{ number_format($purchase->freight_charges, 2) }}</td>
                        </tr>  --}}
                    @else
                        <tr>
                            <td>Totals (Amount):</td>
                            <td>{{ number_format($purchase->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Carriage and Freight:</td>
                            <td>{{ number_format($purchase->freight_charges, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Labour Charges:</td>
                            <td>{{ number_format($purchase->labour_charges, 2) }}</td>
                        </tr>
                    @endif
                    <tr class="total-row">
                        <td>Net Total:</td>
                        <td>Rs. {{ number_format($purchase->grand_total, 2) }}</td>
                    </tr>
                </table>
                <div class="amount-in-words">
                    <strong>Amount in Words:</strong>
                    @php
                        function numberToWords($number)
                        {
                            $ones = [
                                '',
                                'One',
                                'Two',
                                'Three',
                                'Four',
                                'Five',
                                'Six',
                                'Seven',
                                'Eight',
                                'Nine',
                                'Ten',
                                'Eleven',
                                'Twelve',
                                'Thirteen',
                                'Fourteen',
                                'Fifteen',
                                'Sixteen',
                                'Seventeen',
                                'Eighteen',
                                'Nineteen',
                            ];
                            $tens = [
                                '',
                                '',
                                'Twenty',
                                'Thirty',
                                'Forty',
                                'Fifty',
                                'Sixty',
                                'Seventy',
                                'Eighty',
                                'Ninety',
                            ];

                            $number = (int) $number;
                            $paise = round(($number - floor($number)) * 100);

                            if ($number == 0) {
                                return 'Zero';
                            }

                            $result = '';

                            // Crores
                            if ($number >= 10000000) {
                                $crores = (int) ($number / 10000000);
                                $result .= numberToWords($crores) . ' Crore ';
                                $number %= 10000000;
                            }
                            if ($number >= 100000) {
                                $lakhs = (int) ($number / 100000);
                                $result .= numberToWords($lakhs) . ' Lakh ';
                                $number %= 100000;
                            }
                            if ($number >= 1000) {
                                $thousands = (int) ($number / 1000);
                                $result .= numberToWords($thousands) . ' Thousand ';
                                $number %= 1000;
                            }

                            // Hundreds
                            if ($number >= 100) {
                                $hundreds = (int) ($number / 100);
                                $result .= $ones[$hundreds] . ' Hundred ';
                                $number %= 100;
                            }

                            // Tens and Ones
                            if ($number >= 20) {
                                $tensDigit = (int) ($number / 10);
                                $result .= $tens[$tensDigit] . ' ';
                                $number %= 10;
                            }

                            if ($number > 0) {
                                $result .= $ones[$number] . ' ';
                            }

                            $result = trim($result);

                            if ($paise > 0) {
                                $result .= ' and ' . numberToWords($paise) . ' Paisas';
                            }

                            return $result;
                        }
                    @endphp
                    Rupees {{ ucwords(numberToWords($purchase->grand_total)) }} Only
                </div>
            </div>
        </div>
        <br>
        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line">
                    <div style="margin-bottom: 5px;">
                        <span style="font-size: 18px;">__________________________ </span>
                    </div>
                    <strong>Signature</strong>
                </div>
                <p style="text-align: left;">Printed By: <strong>{{ Auth::user()->name ?? 'N/A' }}</strong></p>
                <p style="text-align: left;">Printed On: <strong>{{ now()->format('d/m/Y h:i:s A') }}</strong></p>
            </div>
            <div class="signature-box">
            </div>
        </div>
    </div>
    {{--  <div style="text-align: center;font-size: 10px; color: #666; position: fixed; bottom: 10px; left: 0; width: 100%;">
        <p>Developed by: <strong>MTS</strong> | Contact: <strong>0301-8203399</strong></p>
    </div>  --}}
    <script>
        // Auto print when page loads, then auto close page after printing
        window.onload = function() {
            window.print();
        };
        window.onafterprint = function() {
            window.close();
        };
    </script>
</body>

</html>
