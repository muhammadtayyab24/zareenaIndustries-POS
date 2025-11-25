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
                margin: 10mm;
            }
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
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

            border-bottom: 2px solid #000;
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
            border: 1px solid #000;
            padding: 10px;
        }

        .invoice-details-right h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
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
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .products-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
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
            border: 1px solid #000;
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
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .amount-in-words {
            font-weight: bold;
        }

        .signatures {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            width: 45%;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 50px;
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
            <h1>Zareena Industries</h1>
            <div class="company-info">
                {{--  <p><strong>ZAREENA INDUSTRIES</strong></p>  --}}
                <p>Address: Plot #D 27 Manghopir Road S.I.T.E Karachi.</p>
                <p>Tel: +92-301-8203399 - +92-304-3558424 | E-Mail: info@zareenaindustries.com</p>
            </div>
        </div>

        <div class="invoice-details">
            <div class="invoice-details-left">
                <div style="margin-bottom: 10px;">
                    <table style="width:80%; margin:0 auto; border-collapse:collapse; background:#f0f0f0; border-radius: 5px;">
                        <tr>
                            <td colspan="2" style="text-align:center; font-size:13px; font-weight:bold; padding:5px 0 2px 0; letter-spacing:1px;">
                                PURCHASE INVOICE
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
                                {{ $purchase->created_at->format('d-m-Y') }}
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Vendor Invoice No:</span>
                    <span class="detail-value">{{ $purchase->vendor_invoice_no }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Vendor Invoice Date:</span>
                    <span
                        class="detail-value">{{ $purchase->due_date ? $purchase->due_date->format('d-m-Y') : $purchase->created_at->format('d-m-Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">GRN No:</span>
                    <span class="detail-value">{{ $purchase->grn_no ?? 'N/A' }}</span>
                </div>
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
                <div class="detail-row">
                    <span class="vendor-detail-label">Contact:</span>
                    <span>{{ $purchase->vendor->contact ?? 'N/A' }}</span>
                </div>


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
                    <th style="width: 40%;">Product Name</th>
                    <th style="width: 10%;">Unit</th>
                    <th style="width: 10%;">Quantity</th>
                    <th style="width: 12%;">Rate</th>
                    <th style="width: 13%;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchase->products as $index => $purchaseProduct)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $purchaseProduct->product->product_name ?? 'N/A' }}</td>
                        <td class="text-center">{{ $purchaseProduct->unit_type ?? 'N/A' }}</td>
                        <td class="text-center">{{ number_format($purchaseProduct->qty, 2) }}</td>
                        <td>{{ number_format($purchaseProduct->price, 2) }}</td>
                        <td>{{ number_format($purchaseProduct->total_amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-right"><strong>Total:</strong></td>
                    <td class="text-center">-</td>
                    <td class="text-center"><strong>{{ number_format($purchase->products->sum('qty'), 2) }}</strong>
                    </td>
                    <td class="text-center">-</td>
                    <td class="text-right">
                        <strong>{{ number_format($purchase->products->sum('total_amount'), 2) }}</strong>
                    </td>
                </tr>
            </tfoot>
        </table>

        <div class="summary-section">
            <div class="summary-left">
                <strong>Note:</strong>
            </div>
            <div class="summary-right">
                <table class="summary-table">
                    <tr>
                        <td>Totals (Amount):</td>
                        <td>{{ number_format($purchase->subtotal, 2) }}</td>
                    </tr>
                    @if ($purchase->type === 'tax')
                        <tr>
                            <td>Total GST:</td>
                            <td>{{ number_format($purchase->total_gst, 2) }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td>Carriage and Freight:</td>
                        <td>{{ number_format($purchase->freight_charges, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Labour Charges:</td>
                        <td>{{ number_format($purchase->labour_charges, 2) }}</td>
                    </tr>
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
