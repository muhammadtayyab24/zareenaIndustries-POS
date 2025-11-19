<style>
    .startbar .startbar-menu .navbar-nav .nav-item .nav-link[data-bs-toggle=collapse][aria-expanded=true] {
        background-color: rgb(197 129 34 / 5%) !important;
        border-radius: 10px !important;
    }
</style>
<div class="startbar d-print-none">
    <!--start brand-->
    <div class="brand">
        <a href="{{ route('dashboard') }}" class="logo">
            <span>
                <img src="{{ asset('assets/images/logo-sm.png') }}" alt="logo-small" class="logo-sm">
            </span>
            <span class="">
                {{--  <img src="{{ asset('assets/images/logo-light.png') }}" alt="logo-large" class="logo-sm logo-light">  --}}
                {{--  <img src="{{ asset('assets/images/logo-dark.png') }}" alt="logo-large" class="logo-sm logo-dark">  --}}
            </span>
        </a>
    </div>
    <!--end brand-->
    <!--start startbar-menu-->
    <div class="startbar-menu">
        <div class="startbar-collapse" id="startbarCollapse" data-simplebar>
            <div class="d-flex align-items-start flex-column w-100">
                <!-- Navigation -->
                <ul class="navbar-nav mb-auto w-100">
                    <li class="menu-label pt-0 mt-0">
                        <span>User Management</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#sidebarUsers" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarUsers">
                            <i class="iconoir-user menu-icon"></i>
                            <span>Users</span>
                        </a>
                        <div class="collapse " id="sidebarUsers">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('users.create') }}">Create</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('users.index') }}">View</a>
                                </li><!--end nav-item-->
                            </ul><!--end nav-->
                        </div><!--end startbarUsers-->
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link" href="#sidebarVendors" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarVendors">
                            <i class="iconoir-shop menu-icon"></i>
                            <span>Vendors</span>
                        </a>
                        <div class="collapse " id="sidebarVendors">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('vendors.create') }}">Create</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('vendors.index') }}">View</a>
                                </li><!--end nav-item-->
                            </ul><!--end nav-->
                        </div><!--end startbarVendors-->
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link" href="#sidebarCustomers" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarCustomers">
                            <i class="iconoir-user menu-icon"></i>
                            <span>Customers</span>
                        </a>
                        <div class="collapse " id="sidebarCustomers">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('customers.create') }}">Create</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('customers.index') }}">View</a>
                                </li><!--end nav-item-->
                            </ul><!--end nav-->
                        </div><!--end startbarCustomers-->
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link" href="#sidebarWarehouses" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarWarehouses">
                            <i class="iconoir-store menu-icon"></i>
                            <span>Warehouses</span>
                        </a>
                        <div class="collapse " id="sidebarWarehouses">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('warehouses.create') }}">Create</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('warehouses.index') }}">View</a>
                                </li><!--end nav-item-->
                            </ul><!--end nav-->
                        </div><!--end startbarWarehouses-->
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link" href="#sidebarEmployees" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarEmployees">
                            <i class="iconoir-user-square menu-icon"></i>
                            <span>Employees</span>
                        </a>
                        <div class="collapse " id="sidebarEmployees">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('employees.create') }}">Create</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('employees.index') }}">View</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('employees.attendances.index') }}">Attendance</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('employees.advance-salaries.index') }}">Advance Salaries</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('employees.overtimes.index') }}">Overtime</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('employees.salaries.index') }}">Salaries</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('employees.reports.employee') }}">Reports</a>
                                </li><!--end nav-item-->
                            </ul><!--end nav-->
                        </div><!--end startbarEmployees-->
                    </li><!--end nav-item-->
                    <br>
                    <li class="menu-label pt-0 mt-0">
                        <span>Product Management</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('product-categories.index') }}" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarProductCategories">
                            <i class="iconoir-folder menu-icon"></i>
                            <span>Product Categories</span>
                        </a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('product-types.index') }}" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarProductTypes">
                            <i class="iconoir-tag menu-icon"></i>
                            <span>Product Types</span>
                        </a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link" href="#sidebarProducts" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarProducts">
                            <i class="iconoir-box menu-icon"></i>
                            <span>Products</span>
                        </a>
                        <div class="collapse " id="sidebarProducts">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('products.create') }}">Create</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('products.index') }}">View</a>
                                </li><!--end nav-item-->
                            </ul><!--end nav-->
                        </div><!--end startbarProducts-->
                    </li><!--end nav-item-->
                    <br>
                    <li class="menu-label pt-0 mt-0">
                        <span>Expense Management</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('expense-categories.index') }}" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarExpenseCategories">
                            <i class="iconoir-folder menu-icon"></i>
                            <span>Expense Categories</span>
                        </a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link" href="#sidebarExpenseVouchers" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarExpenseVouchers">
                            <i class="iconoir-receipt menu-icon"></i>
                            <span>Expense Vouchers</span>
                        </a>
                        <div class="collapse " id="sidebarExpenseVouchers">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('expense-vouchers.create') }}">Create</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('expense-vouchers.index') }}">View</a>
                                </li><!--end nav-item-->
                            </ul><!--end nav-->
                        </div><!--end startbarExpenseVouchers-->
                    </li><!--end nav-item-->
                 
                </ul><!--end navbar-nav--->
               
            </div>
        </div>  
    </div>
</div>
