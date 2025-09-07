<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - KAIZEN 360</title>

    <!-- CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  
  <style>
/* This will hide buttons based on body classes that we'll add via PHP */
body.user-role.no-create-materials a[href*="materials/create"],
body.user-role.no-create-materials button[onclick*="materials"][onclick*="create"] {
    display: none !important;
}

body.user-role.no-edit-materials a[href*="materials"][href*="edit"],
body.user-role.no-edit-materials button[onclick*="materials"][onclick*="edit"] {
    display: none !important;
}

body.user-role.no-delete-materials form[action*="materials"][action*="destroy"],
body.user-role.no-delete-materials button[onclick*="materials"][onclick*="delete"] {
    display: none !important;
}

/* Repeat for all modules */
body.user-role.no-create-vendors a[href*="vendors/create"] { display: none !important; }
body.user-role.no-edit-vendors a[href*="vendors"][href*="edit"] { display: none !important; }
body.user-role.no-delete-vendors form[action*="vendors"][action*="destroy"] { display: none !important; }

/* Add similar rules for all 10 modules */
</style>

  
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <img src="{{ asset('images/Kaizen logo.png') }}" alt="Kaizen Logo" class="img-fluid" style="max-height: 140px;margin-left:40px; margin-top:-30px;margin-bottom:-40px;">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <!-- Notification Bell -->
                        <li class="nav-item dropdown me-3">
                            <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ auth()->user()->unreadNotifications->count() }}
                                        <span class="visually-hidden">unread notifications</span>
                                    </span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" style="width: 300px; max-height: 400px; overflow-y: auto;">
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    @foreach(auth()->user()->unreadNotifications->take(5) as $notification)
                                        @php
                                            $data = is_array($notification->data) ? $notification->data : json_decode($notification->data, true);
                                        @endphp
                                        <li>
                                            <div class="dropdown-item-text small" id="sidebar-notification-{{ $notification->id }}">
                                                <strong>{{ $data['title'] ?? 'Notification' }}</strong><br>
                                                <span class="text-muted">{{ Str::limit($data['message'] ?? 'No message', 50) }}</span><br>
                                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                        </li>
                                        @if(!$loop->last)
                                            <li><hr class="dropdown-divider"></li>
                                        @endif
                                    @endforeach
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-center" href="{{ route('dashboard') }}">
                                            <i class="fas fa-eye me-1"></i> View All Notifications
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <div class="dropdown-item-text text-center text-muted">
                                            <i class="fas fa-inbox mb-2"></i><br>
                                            No new notifications
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </li>

                        <!-- User Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                                <span class="badge bg-light text-dark ms-1">{{ Auth::user()->getRoleDisplayName() }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="fas fa-user-edit me-2"></i> My Profile
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-user-circle"></i> Login
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

  
     <div class="container-fluid">
        <div class="row">
         <!-- Sidebar Navigation -->
<nav class="col-md-3 col-lg-2 d-md-block sidebar slide-in-left">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <!-- Dashboard - Always visible -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>

            @auth
                @php
                    $user = Auth::user();
                    $role = $user->role;
                    $permissions = $user->permissions ?? []; // Assumes this returns an array like: ['materials' => ['can_view' => true], ...]
                @endphp

                {{-- ADMIN ROLE - Show all modules --}}
                @if ($user->isAdmin())
                    <li class="nav-item">
                        <h6 class="sidebar-heading px-3 mt-4 mb-1 text-muted">ADMINISTRATION</h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                            <i class="fas fa-users-cog"></i> User Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.warehouses.*') ? 'active' : '' }}" href="{{ route('dashboard.warehouses.index') }}">
                            <i class="fas fa-warehouse"></i> Warehouse Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('warehouses.blocks.*') ? 'active' : '' }}" href="{{ route('warehouses.blocks.all') }}">
                            <i class="fas fa-th-large"></i> View Blocks
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('materials.*') ? 'active' : '' }}" href="{{ route('materials.index') }}">
                            <i class="fas fa-cube"></i> Materials
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('vendors.*') ? 'active' : '' }}" href="{{ route('vendors.index') }}">
                            <i class="fas fa-truck"></i> Vendor Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}" href="{{ route('purchase-orders.index') }}">
                            <i class="fas fa-shopping-cart"></i> Purchase Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}" href="{{ route('inventory.index') }}">
                            <i class="fas fa-boxes"></i> Inventory Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('barcode.*') ? 'active' : '' }}" href="{{ route('barcode.dashboard') }}">
                            <i class="fas fa-qrcode"></i> Barcode Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('quality-analysis.*') ? 'active' : '' }}" href="{{ route('quality-analysis.index') }}">
                            <i class="fas fa-check-circle"></i> Quality Analysis
                        </a>
                    </li>
                @endif

                {{-- PURCHASE TEAM ROLE - Show only purchase orders --}}
                @if ($role === 'purchase_team')
                    <li class="nav-item">
                        <h6 class="sidebar-heading px-3 mt-4 mb-1 text-muted">PURCHASING</h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}" href="{{ route('purchase-orders.index') }}">
                            <i class="fas fa-shopping-cart"></i> Purchase Orders
                        </a>
                    </li>
                @endif

                {{-- INVENTORY MANAGER ROLE - Show only inventory control and barcode management --}}
                @if ($role === 'inventory_manager')
                    <li class="nav-item">
                        <h6 class="sidebar-heading px-3 mt-4 mb-1 text-muted">INVENTORY</h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}" href="{{ route('inventory.index') }}">
                            <i class="fas fa-boxes"></i> Inventory Control
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('barcode.*') ? 'active' : '' }}" href="{{ route('barcode.dashboard') }}">
                            <i class="fas fa-qrcode"></i> Barcode Management
                        </a>
                    </li>
                @endif

                {{-- USER ROLE - Show only modules with permissions --}}
                @if ($role === 'user')
      @php
    $user = Auth::user();
    $userPermissions = [];

    if ($user) {
        $userPermissions = DB::table('permissions')
            ->join('modules', 'permissions.module_id', '=', 'modules.id')
            ->where('permissions.user_id', $user->id)
            ->where('permissions.can_view', 1)
            ->where('modules.is_active', 1)
            ->select('modules.id', 'modules.name', 'modules.route', 'modules.icon')
            ->get();
    }
@endphp


                    @if ($userPermissions->count() > 0)
                        <li class="nav-item">
                            <h6 class="sidebar-heading px-3 mt-4 mb-1 text-muted">MODULES</h6>
                        </li>

                        @foreach ($userPermissions as $module)
                            @if ($module->id == 1) {{-- User Management --}}
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                                        <i class="{{ $module->icon }}"></i> {{ $module->name }}
                                    </a>
                                </li>
                            @elseif ($module->id == 2) {{-- Warehouse Management --}}
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('dashboard.warehouses.*') ? 'active' : '' }}" href="{{ route('dashboard.warehouses.index') }}">
                                        <i class="{{ $module->icon }}"></i> {{ $module->name }}
                                    </a>
                                </li>
                            @elseif ($module->id == 3) {{-- View Blocks --}}
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('warehouses.blocks.*') ? 'active' : '' }}" href="{{ route('warehouses.blocks.all') }}">
                                        <i class="{{ $module->icon }}"></i> {{ $module->name }}
                                    </a>
                                </li>
                            @elseif ($module->id == 4) {{-- Materials --}}
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('materials.*') ? 'active' : '' }}" href="{{ route('materials.index') }}">
                                        <i class="{{ $module->icon }}"></i> {{ $module->name }}
                                    </a>
                                </li>
                            @elseif ($module->id == 5) {{-- Vendor Management --}}
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('vendors.*') ? 'active' : '' }}" href="{{ route('vendors.index') }}">
                                        <i class="{{ $module->icon ?? 'fas fa-circle' }}"></i> {{ $module->name }}

                                    </a>
                                </li>
                            @elseif ($module->id == 6) {{-- Quality Analysis --}}
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('quality-analysis.*') ? 'active' : '' }}" href="{{ route('quality-analysis.index') }}">
                                        <i class="{{ $module->icon }}"></i> {{ $module->name }}
                                    </a>
                                </li>
                            @elseif ($module->id == 7) {{-- Purchase Orders --}}
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}" href="{{ route('purchase-orders.index') }}">
                                        <i class="{{ $module->icon }}"></i> {{ $module->name }}
                                    </a>
                                </li>
                            @elseif ($module->id == 8) {{-- Inventory Controls --}}
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}" href="{{ route('inventory.index') }}">
                                        <i class="{{ $module->icon }}"></i> {{ $module->name }}
                                    </a>
                                </li>
                            @elseif ($module->id == 9) {{-- Barcode Management --}}
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('barcode.*') ? 'active' : '' }}" href="{{ route('barcode.dashboard') }}">
                                        <i class="{{ $module->icon }}"></i> {{ $module->name }}
                                    </a>
                                </li>
                            @elseif ($module->id == 10) {{-- Report Analysis --}}
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                                        <i class="{{ $module->icon }}"></i> {{ $module->name }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endif

                {{-- Reports (visible to all authenticated users, but check permissions for regular users) --}}
                @if ($role !== 'user')
                    <li class="nav-item">
                        <h6 class="sidebar-heading px-3 mt-4 mb-1 text-muted">REPORTS</h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                            <i class="fas fa-file-alt"></i> Reports & Analytics
                        </a>
                    </li>
                @else
                    {{-- For users, check if they have permission to view reports --}}
                    @php
                        $hasReportPermission = DB::table('permissions')
                            ->where('user_id', $user->id)
                            ->where('module_id', 10) // Report Analysis module
                            ->where('can_view', 1)
                            ->exists();
                    @endphp
                    
                    @if ($hasReportPermission)
                        <li class="nav-item">
                            <h6 class="sidebar-heading px-3 mt-4 mb-1 text-muted">REPORTS</h6>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                                <i class="fas fa-file-alt"></i> Reports & Analytics
                            </a>
                        </li>
                    @endif
                @endif
            @endauth
        </ul>
    </div>
</nav>
 

             
            
            <!-- Main Content Area -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 fade-in-up">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <!-- Page Content -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
  <script>
document.addEventListener('DOMContentLoaded', function() {
    // Get user permissions from PHP
    const userPermissions = @json(Auth::user()->role === 'user' ? 
        DB::table('permissions')->where('user_id', Auth::id())->get()->keyBy('module_id') : 
        collect()
    );
    
    const userRole = @json(Auth::user()->role);
    
    // If user is admin or super_admin, don't hide anything
    if (userRole !== 'user') {
        return;
    }
    
    // Module ID mapping based on route patterns
    const moduleMapping = {
        'materials': 4,
        'vendors': 5,
        'warehouses': 2,
        'users': 1,
        'blocks': 3,
        'quality-analysis': 6,
        'purchase-orders': 7,
        'inventory': 8,
        'barcode': 9,
        'reports': 10
    };
    
    // Hide buttons based on permissions
    function hideUnauthorizedElements() {
        // Hide Create buttons
        document.querySelectorAll('a[href*="/create"], button[onclick*="create"]').forEach(function(element) {
            const href = element.getAttribute('href') || element.getAttribute('onclick') || '';
            
            for (let module in moduleMapping) {
                if (href.includes(module)) {
                    const moduleId = moduleMapping[module];
                    const permission = userPermissions[moduleId];
                    
                    if (!permission || !permission.can_create) {
                        element.style.display = 'none';
                    }
                    break;
                }
            }
        });
        
        // Hide Edit buttons
        document.querySelectorAll('a[href*="/edit"], button[onclick*="edit"]').forEach(function(element) {
            const href = element.getAttribute('href') || element.getAttribute('onclick') || '';
            
            for (let module in moduleMapping) {
                if (href.includes(module)) {
                    const moduleId = moduleMapping[module];
                    const permission = userPermissions[moduleId];
                    
                    if (!permission || !permission.can_edit) {
                        element.style.display = 'none';
                    }
                    break;
                }
            }
        });
        
        // Hide Delete buttons
        document.querySelectorAll('button[onclick*="delete"], form[action*="destroy"] button, a[href*="/delete"]').forEach(function(element) {
            const href = element.getAttribute('href') || element.getAttribute('onclick') || '';
            const form = element.closest('form');
            const formAction = form ? form.getAttribute('action') : '';
            const checkString = href + formAction;
            
            for (let module in moduleMapping) {
                if (checkString.includes(module)) {
                    const moduleId = moduleMapping[module];
                    const permission = userPermissions[moduleId];
                    
                    if (!permission || !permission.can_delete) {
                        element.style.display = 'none';
                        if (form) form.style.display = 'none';
                    }
                    break;
                }
            }
        });
    }
    
    // Run on page load
    hideUnauthorizedElements();
    
    // Run when content changes (for dynamic content)
    const observer = new MutationObserver(hideUnauthorizedElements);
    observer.observe(document.body, { childList: true, subtree: true });
});
</script>
  
    <script>
        // Enhanced navigation active state management
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            
            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href && (href === currentPath || currentPath.startsWith(href + '/'))) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        });
        
        // Enhanced alert auto-hide with fade effect
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(alert => {
                if (alert) {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }, 500);
                }
            });
        }, 5000);

        // Add smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Form submission handling with loading state
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
                if (submitBtn && !submitBtn.disabled) {
                    if (submitBtn.tagName === 'BUTTON') {
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
                    } else if (submitBtn.tagName === 'INPUT') {
                        submitBtn.value = 'Processing...';
                    }
                    submitBtn.disabled = true;
                }
            });
        });

        // Confirmation dialogs for delete actions
        document.querySelectorAll('[data-confirm]').forEach(element => {
            element.addEventListener('click', function(e) {
                const message = this.getAttribute('data-confirm');
                if (!confirm(message)) {
                    e.preventDefault();
                }
            });
        });

        // Toast notification system
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
            toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            toast.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 5000);
        }

        // Make toast function globally available
        window.showToast = showToast;
    </script>
    
    @yield('scripts')
    @stack('scripts')
</body>
</html>