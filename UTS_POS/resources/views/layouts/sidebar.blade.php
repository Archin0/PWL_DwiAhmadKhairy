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
      <li class="nav-item"> 
        <a href="{{ url('/') }}" class="nav-link  {{ ($activeMenu == 'dashboard')? 'active' : '' }} "> 
          <i class="nav-icon fas fa-tachometer-alt"></i> 
          <p>Dashboard</p> 
        </a> 
      </li>

      <li class="nav-header">Kelola Akun</li> 
        <li class="nav-item"> 
          <a href="{{ url('/level') }}" class="nav-link {{ ($activeMenu == 'level')? 'active' : '' }} "> 
            <i class="nav-icon fas fa-user"></i> 
            <p>Data User</p>
          </a> 
        </li> 
        <li class="nav-item"> 
          <a href="{{ url('/user') }}" class="nav-link {{ ($activeMenu == 'user')? 'active' : '' }}"> 
            <i class="nav-icon fas fa-layer-group"></i> 
            <p>Hak Akses</p>
          </a> 
        </li>

      <li class="nav-header">Kelola Barang</li> 
        <li class="nav-item"> 
          <a href="{{ url('/kategori') }}" class="nav-link {{ ($activeMenu == 'kategori')? 'active' : '' }} "> 
            <i class="nav-icon far fa-bookmark"></i> 
            <p>Kategori Barang</p> 
          </a> 
        </li> 
        <li class="nav-item"> 
          <a href="{{ url('/barang') }}" class="nav-link {{ ($activeMenu == 'barang')? 'active' : '' }} "> 
            <i class="nav-icon fas fa-car"></i> 
            <p>Data Barang</p> 
          </a> 
        </li> 
        <li class="nav-item"> 
          <a href="{{ url('/barang') }}" class="nav-link {{ ($activeMenu == 'barang')? 'active' : '' }} "> 
            <i class="nav-icon fas fa-cubes"></i> 
            <p>Supply Barang</p> 
          </a> 
        </li> 

      <li class="nav-header">Kelola Transaksi</li>
        <li class="nav-item"> 
          <a href="{{ url('/stok') }}" class="nav-link {{ ($activeMenu == 'stok')? 'active' : '' }} "> 
            <i class="nav-icon fa fa-shopping-cart"></i> 
            <p>Transaksi</p> 
          </a> 
        </li>
      
      <li class="nav-header">Kelola Laporan</li>
        <li class="nav-item"> 
          <a href="{{ url('/stok') }}" class="nav-link {{ ($activeMenu == 'stok')? 'active' : '' }} "> 
            <i class="nav-icon fas fa-file-excel"></i> 
            <p>Laporan Transaksi</p>
          </a> 
        </li>

      <li class="nav-header">Keluar</li>
      <li class="nav-item">
          <a href="#" class="nav-link"
              onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
          </a>
          <form id="logout-form" action="{{ url('logout') }}" method="GET" style="display: none;">
              @csrf
          </form>
      </li>
    </ul> 
  </nav> 
</div>