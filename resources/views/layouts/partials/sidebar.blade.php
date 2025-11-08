<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/dashboard" class="brand-link">
        <img src="{{ asset('adminlte/dist/img/accounting1.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">家計簿 1.0</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('adminlte/dist/img/panda.png') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('transactions.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-money-check-alt"></i>
                        <p>
                            取引
                        </p>
                    </a>
                </li>

                <li class="nav-header">レポート</li>

                {{-- 月間サマリーレポート --}}
                <li class="nav-item">
                    <a href="{{ route('admin.reports.monthly-summary') }}" class="nav-link">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>月間サマリー</p>
                    </a>
                </li>

                {{-- 勘定科目別サマリーレポート --}}
                <li class="nav-item">
                    <a href="{{ route('admin.reports.category-summary') }}" class="nav-link">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>勘定科目別サマリー</p>
                    </a>
                </li>

                <li class="nav-header">設定</li>                
                <li class="nav-item">
                    <a href="{{ route('admin.transaction_types.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>
                            取引タイプ    
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.categories.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-folder"></i>
                        <p>
                            取引カテゴリー
                        </p>
                    </a>
                </li>                                
               
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>