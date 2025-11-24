@extends('layout.app')

@section('title', 'Create Purchase Order (Tax) | Zareena Industries')

@push('styles')
<!-- Element UI CSS -->
<link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
@endpush

@section('content')
<div id="app">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create Purchase Order (Tax)</h4>
                </div>
                <div class="card-body">
                    <el-form ref="form" :model="form" label-width="150px" label-position="left">
                        <!-- Header Fields -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <el-form-item label="Vendor" required>
                                    <el-select v-model="form.vendor_id" placeholder="Select Vendor" filterable style="width: 100%">
                                        <el-option
                                            v-for="vendor in vendors"
                                            :key="vendor.id"
                                            :label="vendor.name"
                                            :value="vendor.id">
                                        </el-option>
                                    </el-select>
                                </el-form-item>
                            </div>
                            <div class="col-md-6">
                                <el-form-item label="Warehouse">
                                    <el-select v-model="form.warehouse_id" placeholder="Select Warehouse" filterable clearable style="width: 100%">
                                        <el-option
                                            v-for="warehouse in warehouses"
                                            :key="warehouse.id"
                                            :label="warehouse.name"
                                            :value="warehouse.id">
                                        </el-option>
                                    </el-select>
                                </el-form-item>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <el-form-item label="Vendor Invoice No" required>
                                    <el-input v-model="form.vendor_invoice_no" placeholder="Enter Vendor Invoice No"></el-input>
                                </el-form-item>
                            </div>
                            <div class="col-md-6">
                                <el-form-item label="PO No">
                                    <el-input v-model="form.po_no" placeholder="Enter PO No"></el-input>
                                </el-form-item>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <el-form-item label="GRN No">
                                    <el-input v-model="form.grn_no" placeholder="Enter GRN No"></el-input>
                                </el-form-item>
                            </div>
                            <div class="col-md-4">
                                <el-form-item label="Credit Term">
                                    <el-select v-model="form.credit_term" placeholder="Select Credit Term" clearable filterable style="width: 100%">
                                        <el-option label="Net 15" value="Net 15"></el-option>
                                        <el-option label="Net 30" value="Net 30"></el-option>
                                        <el-option label="Net 45" value="Net 45"></el-option>
                                        <el-option label="Net 60" value="Net 60"></el-option>
                                        <el-option label="Net 90" value="Net 90"></el-option>
                                        <el-option label="Due on Receipt" value="Due on Receipt"></el-option>
                                        <el-option label="Cash on Delivery" value="Cash on Delivery"></el-option>
                                    </el-select>
                                </el-form-item>
                            </div>
                            <div class="col-md-4">
                                <el-form-item label="Due Date">
                                    <el-date-picker
                                        v-model="form.due_date"
                                        type="date"
                                        placeholder="Select Due Date"
                                        style="width: 100%"
                                        format="yyyy-MM-dd"
                                        value-format="yyyy-MM-dd">
                                    </el-date-picker>
                                </el-form-item>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <!-- Products Table -->
                        <h5 class="mb-3">Products</h5>
                        <el-table :data="form.products" border style="width: 100%" class="mb-3">
                            <el-table-column label="Product" width="250">
                                <template slot-scope="scope">
                                    <el-select
                                        v-model="scope.row.product_id"
                                        placeholder="Select Product"
                                        filterable
                                        remote
                                        :remote-method="(query) => searchProducts(query, scope.$index)"
                                        :loading="productLoading"
                                        @change="onProductChange(scope.$index)"
                                        style="width: 100%">
                                        <el-option
                                            v-for="product in filteredProducts"
                                            :key="product.id"
                                            :label="product.product_name"
                                            :value="product.id">
                                        </el-option>
                                    </el-select>
                                </template>
                            </el-table-column>
                            <el-table-column label="Unit Type" width="120">
                                <template slot-scope="scope">
                                    <el-input v-model="scope.row.unit_type" readonly></el-input>
                                </template>
                            </el-table-column>
                            <el-table-column label="Qty" width="120">
                                <template slot-scope="scope">
                                    <el-input-number
                                        v-model="scope.row.qty"
                                        :min="0.01"
                                        :precision="2"
                                        @change="calculateRowTotal(scope.$index)"
                                        style="width: 100%">
                                    </el-input-number>
                                </template>
                            </el-table-column>
                            <el-table-column label="Price" width="120">
                                <template slot-scope="scope">
                                    <el-input-number
                                        v-model="scope.row.price"
                                        :min="0"
                                        :precision="2"
                                        @change="calculateRowTotal(scope.$index)"
                                        style="width: 100%">
                                    </el-input-number>
                                </template>
                            </el-table-column>
                            <el-table-column label="GST %" width="120">
                                <template slot-scope="scope">
                                    <el-input-number
                                        v-model="scope.row.gst_percentage"
                                        :min="0"
                                        :max="100"
                                        :precision="2"
                                        @change="calculateRowTotal(scope.$index)"
                                        style="width: 100%">
                                    </el-input-number>
                                </template>
                            </el-table-column>
                            <el-table-column label="Net Amount" width="130">
                                <template slot-scope="scope">
                                    <el-input v-model="scope.row.net_amount" readonly></el-input>
                                </template>
                            </el-table-column>
                            <el-table-column label="GST Amount" width="130">
                                <template slot-scope="scope">
                                    <el-input v-model="scope.row.gst_amount" readonly></el-input>
                                </template>
                            </el-table-column>
                            <el-table-column label="Total Amount" width="130">
                                <template slot-scope="scope">
                                    <el-input v-model="scope.row.total_amount" readonly></el-input>
                                </template>
                            </el-table-column>
                            <el-table-column label="Action" width="100">
                                <template slot-scope="scope">
                                    <el-button type="danger" icon="el-icon-delete" size="mini" @click="removeProduct(scope.$index)"></el-button>
                                </template>
                            </el-table-column>
                        </el-table>
                        
                        <el-button type="primary" icon="el-icon-plus" @click="addProduct">Add Product</el-button>
                        
                        <hr class="my-4">
                        
                        <!-- Totals Section -->
                        <div class="row">
                            <div class="col-md-6 offset-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <td><strong>Subtotal:</strong></td>
                                        <td class="text-end">@{{ formatCurrency(totals.subtotal) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total GST:</strong></td>
                                        <td class="text-end">@{{ formatCurrency(totals.totalGst) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Labour Charges:</strong></td>
                                        <td class="text-end">
                                            <el-input-number
                                                v-model="form.labour_charges"
                                                :min="0"
                                                :precision="2"
                                                @change="calculateTotals"
                                                style="width: 100%">
                                            </el-input-number>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Freight Charges:</strong></td>
                                        <td class="text-end">
                                            <el-input-number
                                                v-model="form.freight_charges"
                                                :min="0"
                                                :precision="2"
                                                @change="calculateTotals"
                                                style="width: 100%">
                                            </el-input-number>
                                        </td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td><strong>Grand Total:</strong></td>
                                        <td class="text-end"><strong>@{{ formatCurrency(totals.grandTotal) }}</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-12 text-end">
                                <el-button @click="goBack">Cancel</el-button>
                                <el-button type="primary" @click="submitForm" :loading="submitting">Submit</el-button>
                            </div>
                        </div>
                    </el-form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Vue.js -->
<script src="https://unpkg.com/vue@2/dist/vue.js"></script>
<!-- Element UI JS -->
<script src="https://unpkg.com/element-ui/lib/index.js"></script>
<script>
new Vue({
    el: '#app',
    data() {
        return {
            form: {
                type: 'tax',
                vendor_id: null,
                warehouse_id: null,
                vendor_invoice_no: '',
                po_no: '',
                grn_no: '',
                credit_term: '',
                due_date: null,
                labour_charges: 0,
                freight_charges: 0,
                products: []
            },
            vendors: @json($vendors),
            warehouses: @json($warehouses),
            products: @json($products),
            filteredProducts: @json($products),
            productLoading: false,
            submitting: false
        }
    },
    computed: {
        totals() {
            let subtotal = 0;
            let totalGst = 0;
            this.form.products.forEach(product => {
                const qty = parseFloat(product.qty || 0);
                const price = parseFloat(product.price || 0);
                const netAmount = qty * price;
                subtotal += netAmount;
                
                const gstPercentage = parseFloat(product.gst_percentage || 0);
                const gstAmount = (netAmount * gstPercentage) / 100;
                totalGst += gstAmount;
            });
            const grandTotal = subtotal + totalGst + parseFloat(this.form.labour_charges || 0) + parseFloat(this.form.freight_charges || 0);
            return {
                subtotal: subtotal,
                totalGst: totalGst,
                grandTotal: grandTotal
            };
        }
    },
    methods: {
        addProduct() {
            this.form.products.push({
                product_id: null,
                unit_type: '',
                qty: 0,
                price: 0,
                gst_percentage: 0,
                net_amount: 0,
                gst_amount: 0,
                total_amount: 0
            });
        },
        removeProduct(index) {
            this.form.products.splice(index, 1);
            this.calculateTotals();
        },
        searchProducts(query, index) {
            if (query !== '') {
                this.productLoading = true;
                this.filteredProducts = this.products.filter(product => {
                    return product.product_name.toLowerCase().includes(query.toLowerCase());
                });
                this.productLoading = false;
            } else {
                this.filteredProducts = this.products;
            }
        },
        async onProductChange(index) {
            const productId = this.form.products[index].product_id;
            if (productId) {
                try {
                    const response = await fetch(`{{ route('purchase-orders.product.unit-type') }}?product_id=${productId}`);
                    const data = await response.json();
                    if (data.success) {
                        this.$set(this.form.products[index], 'unit_type', data.unit_type || '');
                    }
                } catch (error) {
                    this.$message.error('Error fetching product unit type');
                }
            }
            this.calculateRowTotal(index);
        },
        calculateRowTotal(index) {
            const product = this.form.products[index];
            const qty = parseFloat(product.qty || 0);
            const price = parseFloat(product.price || 0);
            // Tax PO: net = qty * price
            const netAmount = qty * price;
            
            const gstPercentage = parseFloat(product.gst_percentage || 0);
            // gst_amount = (net * gst%) / 100
            const gstAmount = (netAmount * gstPercentage) / 100;
            // total_amount = net + gst_amount
            const totalAmount = netAmount + gstAmount;
            
            this.$set(product, 'net_amount', netAmount);
            this.$set(product, 'gst_amount', gstAmount);
            this.$set(product, 'total_amount', totalAmount);
            this.calculateTotals();
        },
        calculateTotals() {
            // Totals are computed, no need to do anything here
        },
        formatCurrency(value) {
            return parseFloat(value || 0).toFixed(2);
        },
        goBack() {
            window.location.href = '{{ route("purchase-orders.tax.index") }}';
        },
        async submitForm() {
            // Validation
            if (!this.form.vendor_id) {
                this.$message.error('Please select a vendor');
                return;
            }
            if (!this.form.vendor_invoice_no) {
                this.$message.error('Please enter vendor invoice number');
                return;
            }
            if (this.form.products.length === 0) {
                this.$message.error('Please add at least one product');
                return;
            }
            
            // Validate all products
            for (let i = 0; i < this.form.products.length; i++) {
                const product = this.form.products[i];
                if (!product.product_id) {
                    this.$message.error(`Please select a product for row ${i + 1}`);
                    return;
                }
                if (!product.qty || product.qty <= 0) {
                    this.$message.error(`Please enter valid quantity for row ${i + 1}`);
                    return;
                }
                if (!product.price || product.price <= 0) {
                    this.$message.error(`Please enter valid price for row ${i + 1}`);
                    return;
                }
            }
            
            this.submitting = true;
            
            try {
                const response = await fetch('{{ route("purchase-orders.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.form)
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.$message.success('Purchase order created successfully');
                    setTimeout(() => {
                        window.location.href = '{{ route("purchase-orders.tax.index") }}';
                    }, 1000);
                } else {
                    this.$message.error(data.message || 'Error creating purchase order');
                    if (data.errors) {
                        console.error('Validation errors:', data.errors);
                    }
                }
            } catch (error) {
                this.$message.error('An error occurred while creating purchase order');
                console.error('Error:', error);
            } finally {
                this.submitting = false;
            }
        }
    },
    mounted() {
        this.addProduct();
    }
});
</script>
@endpush
