<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ url(auth()->user()->foto ?? '') }}" class="img-circle img-profil" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ auth()->user()->name }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <ul class="sidebar-menu" data-widget="tree">
            <li class="{{ Request::route()->getName() == 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <!-- Add 'active' class to menu item if the route name matches -->
            @if (auth()->user()->level == 1)
            <li class="{{ Request::route()->getName() == 'transaksi.baru' ? 'active' : '' }}">
                <a href="{{ route('transaksi.baru') }}">
                    <i class="fa fa-cart-arrow-down"></i> <span>Point Of Sales</span>
                </a>
            </li>
            <!-- Add 'active' class to menu item if the route name matches -->
            <li class="treeview {{ Request::is('kategori*') || Request::is('produk*') || Request::is('member*') || Request::is('supplier*') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-database"></i> <span>Master</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::route()->getName() == 'kategori.index' ? 'active' : '' }}"><a href="{{ route('kategori.index') }}"><i class="fa fa-cube"></i> Kategori</a></li>
                    <li class="{{ Request::route()->getName() == 'produk.index' ? 'active' : '' }}"><a href="{{ route('produk.index') }}"><i class="fa fa-cubes"></i> Produk</a></li>
                    <li class="{{ Request::route()->getName() == 'member.index' ? 'active' : '' }}"><a href="{{ route('member.index') }}"><i class="fa fa-id-card"></i> Member</a></li>
                    <li class="{{ Request::route()->getName() == 'supplier.index' ? 'active' : '' }}"><a href="{{ route('supplier.index') }}"><i class="fa fa-truck"></i> Supplier</a></li>
                </ul>
            </li>
            <!-- buatkan menu iventori -->
            <li class="treeview {{ Request::is('iventori*') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-sliders"></i> <span>Inventori</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href=""><i class="fa fa-rotate-right"></i> Iventori</a></li>
                </ul>
            </li>
            <!-- Add 'active' class to menu item if the route name matches -->
            <li class="treeview {{ Request::is('pengeluaran*') || Request::is('pembelian*') || Request::is('penjualan*') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-cart-plus"></i> <span>Transaksi</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::route()->getName() == 'pengeluaran.index' ? 'active' : '' }}"><a href="{{ route('pengeluaran.index') }}"><i class="fa fa-money"></i> Pengeluaran</a></li>
                    <li class="{{ Request::route()->getName() == 'pembelian.index' ? 'active' : '' }}"><a href="{{ route('pembelian.index') }}"><i class="fa fa-download"></i> Pembelian</a></li>
                    <li class="{{ Request::route()->getName() == 'penjualan.index' ? 'active' : '' }}"><a href="{{ route('penjualan.index') }}"><i class="fa fa-upload"></i> Penjualan</a></li>
                </ul>
            </li>
            <!-- Add 'active' class to menu item if the route name matches -->
            <li class="treeview {{ Request::route()->getName() == 'laporan.index' ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-file"></i> <span>REPORT</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('laporan.index') }}"><i class="fa fa-file-pdf-o"></i> Penjualan</a></li>
                </ul>
            </li>
            <!-- Add 'active' class to menu item if the route name matches -->
            <li class="treeview {{Request::is('presensi*') || Request::is('user*') || Request::is('setting*') || Request::is('admin*') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-user-circle"></i> <span>Employe</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                <li class="{{ Request::route()->getName() == 'presensi.index' ? 'active' : '' }}"><a href="{{ route('presensi.index') }}"><i class="fa fa-table"></i>Data Presensi</a></li>
                    <li class="{{ Request::route()->getName() == 'admin.index' ? 'active' : '' }}"><a href="{{ route('admin.index') }}"><i class="fa fa-user"></i>Pengguna Admin</a></li>
                    <li class="{{ Request::route()->getName() == 'user.index' ? 'active' : '' }}"><a href="{{ route('user.index') }}"><i class="fa fa-users"></i>Karyawan</a></li>
                    <li class="{{ Request::route()->getName() == 'setting.index' ? 'active' : '' }}"><a href="{{ route('setting.index') }}"><i class="fa fa-cog"></i>Pengaturan</a></li>
                </ul>
            </li>
            @else
            <li class="{{ Request::route()->getName() == 'transaksi.baru' ? 'active' : '' }}">
                <a href="{{ route('transaksi.baru') }}">
                    <i class="fa fa-cart-arrow-down"></i> <span>Point Of Sales</span>
                </a>
            </li>
            @endif
        </ul>

    </section>
</aside>