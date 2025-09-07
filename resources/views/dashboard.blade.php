<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        /* Login Screen */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            width: 100%;
            max-width: 400px;
            transform: translateY(0);
            transition: all 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        /* Dashboard Layout */
        .dashboard {
            display: none;
            min-height: 100vh;
            background: #f8f9fa;
        }

        .dashboard.active {
            display: flex;
        }

        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-header h3 {
            font-size: 18px;
            font-weight: 600;
        }

        .sidebar-toggle {
            background: none;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
        }

        .sidebar-nav {
            padding: 20px 0;
        }

        .nav-item {
            margin-bottom: 5px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-right: 3px solid #3498db;
        }

        .nav-link i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }

        .main-content {
            margin-left: 260px;
            width: calc(100% - 260px);
            transition: all 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 70px;
            width: calc(100% - 70px);
        }

        .topbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .content-area {
            padding: 30px;
        }

        /* Dashboard Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .stat-icon.blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stat-icon.green { background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%); }
        .stat-icon.orange { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .stat-icon.red { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
        }

        .stat-label {
            color: #7f8c8d;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Tables */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .card-header {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: between;
            align-items: center;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }

        .card-body {
            padding: 20px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger { background: #f8d7da; color: #721c24; }

        /* Forms */
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .topbar {
                padding: 15px 20px;
            }

            .content-area {
                padding: 20px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }

        /* Loading Animation */
        .loading {
            display: none;
            text-align: center;
            padding: 50px;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Alerts */
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .close {
            font-size: 24px;
            cursor: pointer;
            color: #aaa;
        }

        .close:hover {
            color: #000;
        }
    </style>
</head>
<body>
    <!-- Login Screen -->
    <div id="loginScreen" class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1><i class="fas fa-boxes"></i> Inventory Pro</h1>
                <p>Comprehensive Inventory Management System</p>
            </div>
            <form id="loginForm">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" class="form-control" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" class="form-control" placeholder="Enter your password" required>
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" class="form-control" required>
                        <option value="">Select Role</option>
                        <option value="admin">Admin</option>
                        <option value="purchase_team">Purchase Team</option>
                        <option value="inventory_manager">Inventory Manager</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
        </div>
    </div>

    <!-- Dashboard -->
    <div id="dashboard" class="dashboard">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h3 id="sidebarTitle">Inventory Pro</h3>
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <div class="sidebar-nav">
                <div class="nav-item">
                    <a href="#" class="nav-link active" onclick="showSection('dashboard')">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showSection('purchase')">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Purchase Orders</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showSection('inventory')">
                        <i class="fas fa-boxes"></i>
                        <span>Inventory</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showSection('barcode')">
                        <i class="fas fa-barcode"></i>
                        <span>Barcode</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showSection('reports')">
                        <i class="fas fa-chart-bar"></i>
                        <span>Reports</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showSection('vendors')">
                        <i class="fas fa-truck"></i>
                        <span>Vendors</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showSection('settings')">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <!-- Top Bar -->
            <div class="topbar">
                <div>
                    <button class="sidebar-toggle d-md-none" onclick="toggleMobileSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h4 id="pageTitle">Dashboard</h4>
                </div>
                <div class="user-info">
                    <span id="userRole">Admin</span>
                    <div class="user-avatar" id="userAvatar">A</div>
                    <button class="btn btn-secondary" onclick="logout()">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </div>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Alerts -->
                <div class="alert alert-success" id="successAlert"></div>
                <div class="alert alert-danger" id="errorAlert"></div>

                <!-- Dashboard Section -->
                <div id="dashboardSection" class="content-section">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-header">
                                <div class="stat-icon blue">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                            <div class="stat-value" id="totalOrders">156</div>
                            <div class="stat-label">Total Purchase Orders</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-header">
                                <div class="stat-icon green">
                                    <i class="fas fa-boxes"></i>
                                </div>
                            </div>
                            <div class="stat-value" id="totalStock">2,847</div>
                            <div class="stat-label">Items in Stock</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-header">
                                <div class="stat-icon orange">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                            </div>
                            <div class="stat-value" id="lowStock">23</div>
                            <div class="stat-label">Low Stock Alerts</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-header">
                                <div class="stat-icon red">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                            <div class="stat-value" id="monthlyValue">₹4.2L</div>
                            <div class="stat-label">Monthly Purchase Value</div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Recent Purchase Orders</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Vendor</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="recentOrdersTable">
                                        <!-- Dynamic content -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Purchase Orders Section -->
                <div id="purchaseSection" class="content-section" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3>Purchase Orders</h3>
                        <button class="btn btn-primary" onclick="showCreatePurchaseModal()">
                            <i class="fas fa-plus"></i> New Purchase Order
                        </button>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>PO Number</th>
                                            <th>Vendor</th>
                                            <th>Date</th>
                                            <th>Items</th>
                                            <th>Total Amount</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="purchaseOrdersTable">
                                        <!-- Dynamic content -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inventory Section -->
                <div id="inventorySection" class="content-section" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3>Inventory Management</h3>
                        <button class="btn btn-primary" onclick="showAddStockModal()">
                            <i class="fas fa-plus"></i> Add Stock
                        </button>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Item Code</th>
                                            <th>Material Name</th>
                                            <th>Batch Number</th>
                                            <th>Current Stock</th>
                                            <th>Unit</th>
                                            <th>Location</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="inventoryTable">
                                        <!-- Dynamic content -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Other sections hidden by default -->
                <div id="barcodeSection" class="content-section" style="display: none;">
                    <h3>Barcode Management</h3>
                    <div class="card">
                        <div class="card-body">
                            <p>Barcode generation and scanning functionality will be implemented here.</p>
                        </div>
                    </div>
                </div>

                <div id="reportsSection" class="content-section" style="display: none;">
                    <h3>Reports & Analytics</h3>
                    <div class="card">
                        <div class="card-body">
                            <p>Comprehensive reporting dashboard will be implemented here.</p>
                        </div>
                    </div>
                </div>

                <div id="vendorsSection" class="content-section" style="display: none;">
                    <h3>Vendor Management</h3>
                    <div class="card">
                        <div class="card-body">
                            <p>Vendor management features will be implemented here.</p>
                        </div>
                    </div>
                </div>

                <div id="settingsSection" class="content-section" style="display: none;">
                    <h3>System Settings</h3>
                    <div class="card">
                        <div class="card-body">
                            <p>System configuration and settings will be implemented here.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Purchase Order Modal -->
    <div id="createPurchaseModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Create New Purchase Order</h4>
                <span class="close" onclick="closeModal('createPurchaseModal')">&times;</span>
            </div>
            <form id="createPurchaseForm">
                <div class="form-row">
                    <div class="form-group">
                        <label>Vendor</label>
                        <select class="form-control" required>
                            <option value="">Select Vendor</option>
                            <option value="vendor1">ABC Suppliers</option>
                            <option value="vendor2">XYZ Materials</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Order Date</label>
                        <input type="date" class="form-control" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Material</label>
                        <select class="form-control" required>
                            <option value="">Select Material</option>
                            <option value="steel">Steel Bars</option>
                            <option value="cement">Cement</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" class="form-control" placeholder="Enter quantity" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Unit Price</label>
                        <input type="number" class="form-control" placeholder="Price per unit" required>
                    </div>
                    <div class="form-group">
                        <label>GST (%)</label>
                        <input type="number" class="form-control" placeholder="GST percentage" value="18">
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('createPurchaseModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Order</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Stock Modal -->
    <div id="addStockModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Add Stock to Inventory</h4>
                <span class="close" onclick="closeModal('addStockModal')">&times;</span>
            </div>
            <form id="addStockForm">
                <div class="form-row">
                    <div class="form-group">
                        <label>Purchase Order ID</label>
                        <input type="text" class="form-control" placeholder="Enter PO ID" required>
                    </div>
                    <div class="form-group">
                        <label>Batch Number</label>
                        <input type="text" class="form-control" placeholder="Auto-generated" readonly>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Material</label>
                        <select class="form-control" required>
                            <option value="">Select Material</option>
                            <option value="steel">Steel Bars</option>
                            <option value="cement">Cement</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Quantity Received</label>
                        <input type="number" class="form-control" placeholder="Enter quantity" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Quality Status</label>
                        <select class="form-control" required>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                            <option value="on_hold">On Hold</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Storage Location</label>
                        <select class="form-control" required>
                            <option value="warehouse_a">Warehouse A</option>
                            <option value="warehouse_b">Warehouse B</option>
                            <option value="warehouse_c">Warehouse C</option>
                        </select>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addStockModal')">Cancel</button>
                    <button type="submit" class="btn btn-success">Add to Inventory</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Global state
        let currentUser = null;
        let sidebarCollapsed = false;

        // Sample data
        const samplePurchaseOrders = [
            { id: 'PO001', vendor: 'ABC Suppliers', date: '2024-06-01', amount: '₹45,000', status: 'Approved' },
            { id: 'PO002', vendor: 'XYZ Materials', date: '2024-06-02', amount: '₹32,500', status: 'Pending' },
            { id: 'PO003', vendor: 'DEF Industries', date: '2024-05-30', amount: '₹78,900', status: 'Delivered' },
            { id: 'PO004', vendor: 'GHI Traders', date: '2024-05-28', amount: '₹25,000', status: 'Cancelled' }
        ];

        const sampleInventory = [
            { code: 'ITM001', name: 'Steel Bars 12mm', batch: 'BT001', stock: 150, unit: 'Tons', location: 'Warehouse A', status: 'Available' },
            { code: 'ITM002', name: 'Cement OPC 53', batch: 'BT002', stock: 85, unit: 'Bags', location: 'Warehouse B', status: 'Low Stock' },
            { code: 'ITM003', name: 'Concrete Blocks', batch: 'BT003', stock: 320, unit: 'Pieces', location: 'Warehouse A', status: 'Available' },
            { code: 'ITM004', name: 'Sand (River)', batch: 'BT004', stock: 12, unit: 'Cubic Meters', location: 'Warehouse C', status: 'Critical' }
        ];

        // Authentication
        function handleLogin(event) {
            event.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const role = document.getElementById('role').value;

            // Simple validation (in real app, this would be server-side)
            if (email && password && role) {
                currentUser = {
                    email: email,
                    role: role,
                    name: email.split('@')[0].charAt(0).toUpperCase() + email.split('@')[0].slice(1)
                };

                // Update UI with user info
                document.getElementById('userRole').textContent = role.replace('_', ' ').toUpperCase();
                document.getElementById('userAvatar').textContent = currentUser.name.charAt(0).toUpperCase();

                // Show dashboard
                document.getElementById('loginScreen').style.display = 'none';
                document.getElementById('dashboard').classList.add('active');

                // Load initial data
                loadDashboardData();
                showAlert('success', 'Login successful! Welcome to Inventory Pro.');
            } else {
                showAlert('error', 'Please fill in all fields.');
            }
        }

        function logout() {
            currentUser = null;
            document.getElementById('dashboard').classList.remove('active');
            document.getElementById('loginScreen').style.display = 'flex';
            document.getElementById('loginForm').reset();
            showAlert('success', 'You have been logged out successfully.');
        }

        // Navigation
        function showSection(sectionName) {
            // Hide all sections
            const sections = document.querySelectorAll('.content-section');
            sections.forEach(section => section.style.display = 'none');

            // Show selected section
            document.getElementById(sectionName + 'Section').style.display = 'block';

            // Update active nav link
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => link.classList.remove('active'));
            event.target.closest('.nav-link').classList.add('active');

            // Update page title
            const titles = {
                'dashboard': 'Dashboard',
                'purchase': 'Purchase Orders',
                'inventory': 'Inventory Management',
                'barcode': 'Barcode Management',
                'reports': 'Reports & Analytics',
                'vendors': 'Vendor Management',
                'settings': 'System Settings'
            };
            document.getElementById('pageTitle').textContent = titles[sectionName];

            // Load section-specific data
            if (sectionName === 'purchase') {
                loadPurchaseOrders();
            } else if (sectionName === 'inventory') {
                loadInventoryData();
            }
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const sidebarTitle = document.getElementById('sidebarTitle');
            
            sidebarCollapsed = !sidebarCollapsed;
            
            if (sidebarCollapsed) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
                sidebarTitle.style.display = 'none';
            } else {
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('expanded');
                sidebarTitle.style.display = 'block';
            }
        }

        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('mobile-open');
        }

        // Data Loading Functions
        function loadDashboardData() {
            // Load recent orders
            const recentOrdersTable = document.getElementById('recentOrdersTable');
            recentOrdersTable.innerHTML = '';
            
            samplePurchaseOrders.slice(0, 5).forEach(order => {
                const statusClass = {
                    'Approved': 'badge-success',
                    'Pending': 'badge-warning',
                    'Delivered': 'badge-success',
                    'Cancelled': 'badge-danger'
                }[order.status];

                const row = `
                    <tr>
                        <td>${order.id}</td>
                        <td>${order.vendor}</td>
                        <td>${order.date}</td>
                        <td>${order.amount}</td>
                        <td><span class="badge ${statusClass}">${order.status}</span></td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="viewOrder('${order.id}')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                `;
                recentOrdersTable.innerHTML += row;
            });

            // Update stats with animation
            animateNumber('totalOrders', 156);
            animateNumber('totalStock', 2847);
            animateNumber('lowStock', 23);
        }

        function loadPurchaseOrders() {
            const purchaseOrdersTable = document.getElementById('purchaseOrdersTable');
            purchaseOrdersTable.innerHTML = '';
            
            samplePurchaseOrders.forEach(order => {
                const statusClass = {
                    'Approved': 'badge-success',
                    'Pending': 'badge-warning',
                    'Delivered': 'badge-success',
                    'Cancelled': 'badge-danger'
                }[order.status];

                const row = `
                    <tr>
                        <td>${order.id}</td>
                        <td>${order.vendor}</td>
                        <td>${order.date}</td>
                        <td>5</td>
                        <td>${order.amount}</td>
                        <td><span class="badge ${statusClass}">${order.status}</span></td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="viewOrder('${order.id}')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-success" onclick="editOrder('${order.id}')">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                `;
                purchaseOrdersTable.innerHTML += row;
            });
        }

        function loadInventoryData() {
            const inventoryTable = document.getElementById('inventoryTable');
            inventoryTable.innerHTML = '';
            
            sampleInventory.forEach(item => {
                const statusClass = {
                    'Available': 'badge-success',
                    'Low Stock': 'badge-warning',
                    'Critical': 'badge-danger'
                }[item.status];

                const row = `
                    <tr>
                        <td>${item.code}</td>
                        <td>${item.name}</td>
                        <td>${item.batch}</td>
                        <td>${item.stock}</td>
                        <td>${item.unit}</td>
                        <td>${item.location}</td>
                        <td><span class="badge ${statusClass}">${item.status}</span></td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="viewItem('${item.code}')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-success" onclick="dispatchItem('${item.code}')">
                                <i class="fas fa-truck"></i>
                            </button>
                        </td>
                    </tr>
                `;
                inventoryTable.innerHTML += row;
            });
        }

        // Modal Functions
        function showCreatePurchaseModal() {
            document.getElementById('createPurchaseModal').style.display = 'block';
        }

        function showAddStockModal() {
            // Auto-generate batch number
            const batchNumber = 'BT' + Date.now().toString().slice(-6);
            document.querySelector('#addStockModal input[placeholder="Auto-generated"]').value = batchNumber;
            document.getElementById('addStockModal').style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Form Handlers
        function handleCreatePurchase(event) {
            event.preventDefault();
            
            // In real app, this would send data to server
            showAlert('success', 'Purchase order created successfully!');
            closeModal('createPurchaseModal');
            
            // Refresh purchase orders if on that page
            if (document.getElementById('purchaseSection').style.display !== 'none') {
                loadPurchaseOrders();
            }
        }

        function handleAddStock(event) {
            event.preventDefault();
            
            // In real app, this would send data to server
            showAlert('success', 'Stock added to inventory successfully!');
            closeModal('addStockModal');
            
            // Refresh inventory if on that page
            if (document.getElementById('inventorySection').style.display !== 'none') {
                loadInventoryData();
            }
        }

        // Utility Functions
        function showAlert(type, message) {
            const alertId = type === 'success' ? 'successAlert' : 'errorAlert';
            const alertElement = document.getElementById(alertId);
            
            alertElement.textContent = message;
            alertElement.style.display = 'block';
            
            setTimeout(() => {
                alertElement.style.display = 'none';
            }, 5000);
        }

        function animateNumber(elementId, targetNumber) {
            const element = document.getElementById(elementId);
            const startNumber = 0;
            const duration = 2000;
            const startTime = Date.now();
            
            function updateNumber() {
                const elapsed = Date.now() - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const currentNumber = Math.floor(startNumber + (targetNumber - startNumber) * progress);
                
                if (elementId === 'monthlyValue') {
                    element.textContent = '₹' + (currentNumber / 100).toFixed(1) + 'L';
                } else {
                    element.textContent = currentNumber.toLocaleString();
                }
                
                if (progress < 1) {
                    requestAnimationFrame(updateNumber);
                }
            }
            
            updateNumber();
        }

        function viewOrder(orderId) {
            showAlert('success', `Viewing details for order ${orderId}`);
        }

        function editOrder(orderId) {
            showAlert('success', `Editing order ${orderId}`);
        }

        function viewItem(itemCode) {
            showAlert('success', `Viewing details for item ${itemCode}`);
        }

        function dispatchItem(itemCode) {
            showAlert('success', `Dispatching item ${itemCode}`);
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Form event listeners
            document.getElementById('loginForm').addEventListener('submit', handleLogin);
            document.getElementById('createPurchaseForm').addEventListener('submit', handleCreatePurchase);
            document.getElementById('addStockForm').addEventListener('submit', handleAddStock);

            // Close modals when clicking outside
            window.addEventListener('click', function(event) {
                const modals = document.querySelectorAll('.modal');
                modals.forEach(modal => {
                    if (event.target === modal) {
                        modal.style.display = 'none';
                    }
                });
            });

            // Handle responsive sidebar
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    document.getElementById('sidebar').classList.remove('mobile-open');
                }
            });
        });

        // Barcode simulation function
        function generateBarcode(itemCode) {
            // In real app, this would generate actual barcodes
            return `|||||| ||| |||| | ||| |||||| ${itemCode}`;
        }

        // Report generation simulation
        function generateReport(type) {
            showAlert('success', `Generating ${type} report... Download will start shortly.`);
        }

        // Quality check functions
        function approveQuality(itemCode) {
            showAlert('success', `Quality approved for item ${itemCode}`);
        }

        function rejectQuality(itemCode) {
            showAlert('error', `Quality rejected for item ${itemCode}`);
        }

        // Stock adjustment functions
        function adjustStock(itemCode, adjustment) {
            showAlert('success', `Stock adjusted for item ${itemCode}: ${adjustment > 0 ? '+' : ''}${adjustment}`);
        }

        // Search and filter functions
        function searchInventory(query) {
            // Filter inventory based on search query
            const filteredInventory = sampleInventory.filter(item => 
                item.name.toLowerCase().includes(query.toLowerCase()) ||
                item.code.toLowerCase().includes(query.toLowerCase())
            );
            
            // Update table with filtered results
            displayInventoryResults(filteredInventory);
        }

        function displayInventoryResults(inventory) {
            const inventoryTable = document.getElementById('inventoryTable');
            inventoryTable.innerHTML = '';
            
            inventory.forEach(item => {
                const statusClass = {
                    'Available': 'badge-success',
                    'Low Stock': 'badge-warning',
                    'Critical': 'badge-danger'
                }[item.status];

                const row = `
                    <tr>
                        <td>${item.code}</td>
                        <td>${item.name}</td>
                        <td>${item.batch}</td>
                        <td>${item.stock}</td>
                        <td>${item.unit}</td>
                        <td>${item.location}</td>
                        <td><span class="badge ${statusClass}">${item.status}</span></td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="viewItem('${item.code}')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-success" onclick="dispatchItem('${item.code}')">
                                <i class="fas fa-truck"></i>
                            </button>
                        </td>
                    </tr>
                `;
                inventoryTable.innerHTML += row;
            });
        }

        // PWA Service Worker Registration (for offline functionality)
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('ServiceWorker registration successful');
                    })
                    .catch(function(error) {
                        console.log('ServiceWorker registration failed');
                    });
            });
        }

        // Export functions for CSV/Excel reports
        function exportToCSV(data, filename) {
            const csvContent = "data:text/csv;charset=utf-8," 
                + data.map(row => row.join(",")).join("\n");
            
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", filename + ".csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Initialize app
        console.log('Inventory Management System initialized');
    </script>
</body>
</html>