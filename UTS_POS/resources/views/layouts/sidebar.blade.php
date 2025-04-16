<div class="sidebar">
  <!-- SidebarSearch Form -->
  <div class="form-inline mt-2">
    <div class="input-group" data-widget="sidebar-search">
      <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
      <div class="input-group-append">
        <button class="btn btn-sidebar">
          <i class="fas fa-search fa-fw"></i>
        </button>
      </div>
    </div>
  </div>

  <!-- Sidebar Menu -->
  <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <!-- Dashboard -->
      <li class="nav-item">
        <a href="{{ url('/') }}" class="nav-link {{ ($activeMenu == 'dashboard') ? 'active' : '' }}">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>Dashboard</p>
        </a>
      </li>

      <!-- Kelola Akun -->
      <li class="nav-header">KELOLA AKUN</li>
      <li class="nav-item">
        <a href="{{ url('/user') }}" class="nav-link {{ ($activeMenu == 'kelola-akun.users') ? 'active' : '' }}">
          <i class="nav-icon fas fa-users"></i>
          <p>Data User</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link {{ ($activeMenu == 'hak-akses') ? 'active' : '' }}">
          <i class="nav-icon fas fa-user-lock"></i>
          <p>Hak Akses</p>
        </a>
      </li>

      <!-- Kelola Barang -->
      <li class="nav-header">KELOLA BARANG</li>
      <li class="nav-item">
        <a href="#" class="nav-link {{ ($activeMenu == 'kategori') ? 'active' : '' }}">
          <i class="nav-icon fas fa-tags"></i>
          <p>Kategori Barang</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link {{ ($activeMenu == 'barang') ? 'active' : '' }}">
          <i class="nav-icon fas fa-car"></i>
          <p>Data Mobil</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link {{ ($activeMenu == 'supply') ? 'active' : '' }}">
          <i class="nav-icon fas fa-truck-loading"></i>
          <p>Supply Barang</p>
        </a>
      </li>

      <!-- Kelola Transaksi -->
      <li class="nav-header">KELOLA TRANSAKSI</li>
      <li class="nav-item">
        <a href="#" class="nav-link {{ ($activeMenu == 'transaksi') ? 'active' : '' }}">
          <i class="nav-icon fas fa-receipt"></i>
          <p>Transaksi</p>
        </a>
      </li>

      <!-- Laporan -->
      <li class="nav-header">LAPORAN</li>
      <li class="nav-item">
        <a href="#" class="nav-link {{ ($activeMenu == 'laporan') ? 'active' : '' }}">
          <i class="nav-icon fas fa-file-alt"></i>
          <p>Laporan Transaksi</p>
        </a>
      </li>

      <!-- Logout -->
      <li class="nav-header">AKUN</li>
      <li class="nav-item">
        <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
          <p class="text-danger">Logout</p>
        </a>
        <form id="logout-form" action="{{ url('/logout') }}" method="GET" style="display: none;">
          @csrf
        </form>
      </li>
    </ul>
  </nav>
</div>