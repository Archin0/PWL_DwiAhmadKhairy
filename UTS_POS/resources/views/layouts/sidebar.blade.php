@php
    $user = Auth::user();
@endphp

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
        <a href="{{ url('/dashboard') }}" class="nav-link {{ ($activeMenu == 'dashboard') ? 'active' : '' }}">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>Dashboard</p>
        </a>
      </li>

      {{-- membuat variabel $akses untuk menampung data dari database --}}
      @php
      $akses = \App\Models\Akses::where('id_user', $user->id_user)->first();
      @endphp

      @if($akses->kelola_akun == 1)
        <!-- Kelola Akun -->
        <li class="nav-header">KELOLA AKUN</li>
        <li class="nav-item">
          <a href="{{ url('/user') }}" class="nav-link {{ ($activeMenu == 'user') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users"></i>
            <p>Data User</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/akses') }}" class="nav-link {{ ($activeMenu == 'akses') ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-lock"></i>
            <p>Hak Akses</p>
          </a>
        </li>
      @endif

      @if($akses->kelola_barang == 1)
        <!-- Kelola Barang -->
        <li class="nav-header">KELOLA BARANG</li>
        <li class="nav-item">
          <a href="{{ url('/kategori') }}" class="nav-link {{ ($activeMenu == 'kategori') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tags"></i>
            <p>Kategori Barang</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/barang') }}" class="nav-link {{ ($activeMenu == 'barang') ? 'active' : '' }}">
            <i class="nav-icon fas fa-car"></i>
            <p>Data Barang</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/supplier') }}" class="nav-link {{ ($activeMenu == 'supplier') ? 'active' : '' }}">
            <i class="nav-icon fas fa-truck"></i>
            <p>Data Supplier</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/supply') }}" class="nav-link {{ ($activeMenu == 'supply') ? 'active' : '' }}">
            <i class="nav-icon fas fa-truck-loading"></i>
            <p>Data Supply Barang</p>
          </a>
        </li>
      @endif

      @if($akses->transaksi == 1)
        <!-- Kelola Transaksi -->
        <li class="nav-header">KELOLA TRANSAKSI</li>
        <li class="nav-item">
          <a href="{{ url('/transaksi') }}" class="nav-link {{ ($activeMenu == 'transaksi') ? 'active' : '' }}">
            <i class="nav-icon fa fa-cart-plus"></i>
            <p>Transaksi</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/transaksi/list') }}" class="nav-link {{ ($activeMenu == 'transaksi_list') ? 'active' : '' }}">
            <i class="nav-icon fas fa-receipt"></i>
            <p>Data Transaksi</p>
          </a>
        </li>
      @endif

      @if($akses->laporan == 1)
        <!-- Laporan -->
        <li class="nav-header">LAPORAN</li>
        <li class="nav-item">
          <a href="{{ url('/laporan/exportpdf_user') }}" target="_blank" class="nav-link {{ ($activeMenu == 'laporan') ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-alt"></i>
            <p>Laporan User</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/laporan/exportpdf_kategori') }}" target="_blank" class="nav-link {{ ($activeMenu == 'laporan') ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-alt"></i>
            <p>Laporan Kategori</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/laporan/exportpdf_barang') }}" target="_blank" class="nav-link {{ ($activeMenu == 'laporan') ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-alt"></i>
            <p>Laporan Barang</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/laporan/exportpdf_supplier') }}" target="_blank" class="nav-link {{ ($activeMenu == 'laporan') ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-alt"></i>
            <p>Laporan Supplier</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/laporan/exportpdf_supply') }}" target="_blank" class="nav-link {{ ($activeMenu == 'laporan') ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-alt"></i>
            <p>Laporan Supply Barang</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/laporan/exportpdf_transaksi') }}" target="_blank" class="nav-link {{ ($activeMenu == 'laporan') ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-alt"></i>
            <p>Laporan Transaksi</p>
          </a>
        </li>
      @endif

      <!-- Logout -->
      <li class="nav-header">KELUAR</li>
      <li class="nav-item">
        <a href="#" class="nav-link" onclick="confirmLogout(event)">
          <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
          <p class="text-danger">Log out</p>
        </a>
        <form id="logout-form" action="{{ url('/logout') }}" method="GET" style="display: none;">
          @csrf
        </form>
      </li>

      <script>
        function confirmLogout(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Log out',
                text: "Apakah Anda yakin ingin keluar?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Keluar!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
      </script>
    </ul>
  </nav>
</div>