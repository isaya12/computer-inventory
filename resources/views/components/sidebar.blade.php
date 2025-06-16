<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            @if (auth()->user()->role=='admin')
            <ul>
                <li class="active">
                    <a href="{{ route('home') }}"><img src="assets/img/icons/dashboard.svg" alt="img"><span>
                            Dashboard</span> </a>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/product.svg"
                            alt="img"><span>Device</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('device') }}">Device List</a></li>
                        <li><a href="{{ route('newdevice') }}">Add Device</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/users1.svg"
                            alt="img"><span> Users</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="">Add User </a></li>
                        <li><a href="">Users</a></li>
                    </ul>
                </li>
                {{-- <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/sales1.svg"
                            alt="img"><span> Sales</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="saleslist.html">Sales List</a></li>
                        <li><a href="pos.html">POS</a></li>
                        <li><a href="pos.html">New Sales</a></li>
                        <li><a href="salesreturnlists.html">Sales Return List</a></li>
                        <li><a href="createsalesreturns.html">New Sales Return</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/purchase1.svg"
                            alt="img"><span> Purchase</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="purchaselist.html">Device List</a></li>
                        <li><a href="addpurchase.html">Add device</a></li>
                        <li><a href="importpurchase.html">Import device</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/expense1.svg"
                            alt="img"><span> Expense</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="expenselist.html">Expense List</a></li>
                        <li><a href="createexpense.html">Add Expense</a></li>
                        <li><a href="expensecategory.html">Expense Category</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/transfer1.svg"
                            alt="img"><span> Transfer</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="transferlist.html">Transfer List</a></li>
                        <li><a href="addtransfer.html">Add Transfer </a></li>
                        <li><a href="importtransfer.html">Import Transfer </a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/return1.svg"
                            alt="img"><span> Return</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="salesreturnlist.html">Sales Return List</a></li>
                        <li><a href="createsalesreturn.html">Add Sales Return </a></li>
                        <li><a href="purchasereturnlist.html">Purchase Return List</a></li>
                        <li><a href="createpurchasereturn.html">Add Purchase Return </a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/time.svg" alt="img"><span>
                            Report</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="purchaseorderreport.html">Purchase order report</a></li>
                        <li><a href="inventoryreport.html">Inventory Report</a></li>
                        <li><a href="salesreport.html">Sales Report</a></li>
                        <li><a href="invoicereport.html">Invoice Report</a></li>
                        <li><a href="purchasereport.html">Purchase Report</a></li>
                        <li><a href="supplierreport.html">Supplier Report</a></li>
                        <li><a href="customerreport.html">Customer Report</a></li>
                    </ul>
                </li> --}}


            </ul>
            @endif
            @if (auth()->user()->role=='staff')
            <ul>
                <li class="active">
                    <a href="{{ route('home') }}"><img src="assets/img/icons/dashboard.svg" alt="img"><span>
                            Dashboard</span> </a>
                </li>
                <li class="">
                    <a href="{{ route('helpdesk') }}"><img src="assets/img/icons/product.svg"
                            alt="img"><span>Help Desk</span></a>
                </li>

            </ul>
            @endif
            @if (auth()->user()->role=='it-person')
            <ul>
                <li class="active">
                    <a href="{{ route('home') }}"><img src="assets/img/icons/dashboard.svg" alt="img"><span>
                            Dashboard</span> </a>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/product.svg"
                            alt="img"><span>Device</span> <span class="menu-arrow"></span></a>
                    <ul>
                        {{-- <li><a href="{{ route('devices.index') }}">Device List</a></li> --}}
                        {{-- <li><a href="{{ route('device.create') }}">Add Device</a></li> --}}
                        <li><a href="categorylist.html">Asigned Device</a></li>
                        <li><a href="addcategory.html">Add Category</a></li>
                        <li><a href="subcategorylist.html">Sub Category List</a></li>
                        <li><a href="subaddcategory.html">Add Sub Category</a></li>
                        <li><a href="brandlist.html">Brand List</a></li>
                        <li><a href="addbrand.html">Add Brand</a></li>
                        <li><a href="importproduct.html">Import Products</a></li>
                        <li><a href="barcode.html">Print Barcode</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/users1.svg"
                            alt="img"><span> Users</span> <span class="menu-arrow"></span></a>
                    <ul>
                        {{-- <li><a href="{{ route('user.adduser') }}">New User </a></li> --}}
                        {{-- <li><a href="{{ route('user.view') }}">Users List</a></li> --}}
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/sales1.svg"
                            alt="img"><span> Sales</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="saleslist.html">Sales List</a></li>
                        <li><a href="pos.html">POS</a></li>
                        <li><a href="pos.html">New Sales</a></li>
                        <li><a href="salesreturnlists.html">Sales Return List</a></li>
                        <li><a href="createsalesreturns.html">New Sales Return</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/purchase1.svg"
                            alt="img"><span> Purchase</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="purchaselist.html">Device List</a></li>
                        <li><a href="addpurchase.html">Add device</a></li>
                        <li><a href="importpurchase.html">Import device</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/expense1.svg"
                            alt="img"><span> Expense</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="expenselist.html">Expense List</a></li>
                        <li><a href="createexpense.html">Add Expense</a></li>
                        <li><a href="expensecategory.html">Expense Category</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/transfer1.svg"
                            alt="img"><span> Transfer</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="transferlist.html">Transfer List</a></li>
                        <li><a href="addtransfer.html">Add Transfer </a></li>
                        <li><a href="importtransfer.html">Import Transfer </a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/return1.svg"
                            alt="img"><span> Return</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="salesreturnlist.html">Sales Return List</a></li>
                        <li><a href="createsalesreturn.html">Add Sales Return </a></li>
                        <li><a href="purchasereturnlist.html">Purchase Return List</a></li>
                        <li><a href="createpurchasereturn.html">Add Purchase Return </a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/time.svg" alt="img"><span>
                            Report</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="purchaseorderreport.html">Purchase order report</a></li>
                        <li><a href="inventoryreport.html">Inventory Report</a></li>
                        <li><a href="salesreport.html">Sales Report</a></li>
                        <li><a href="invoicereport.html">Invoice Report</a></li>
                        <li><a href="purchasereport.html">Purchase Report</a></li>
                        <li><a href="supplierreport.html">Supplier Report</a></li>
                        <li><a href="customerreport.html">Customer Report</a></li>
                    </ul>
                </li>


            </ul>
            @endif

        </div>
    </div>
</div>
