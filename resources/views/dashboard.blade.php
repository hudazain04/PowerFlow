<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generator Management Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --success: #27ae60;
            --warning: #f39c12;
            --danger: #e74c3c;
            --light: #ecf0f1;
            --dark: #2c3e50;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            background: linear-gradient(180deg, var(--primary) 0%, #1a2530 100%);
            color: white;
            height: 100vh;
            position: fixed;
            padding-top: 20px;
        }

        .sidebar .nav-link {
            color: #bdc3c7;
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .stat-card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            margin-bottom: 20px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card .card-body {
            padding: 25px;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .table-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border: none;
        }

        .table-card .card-header {
            background-color: white;
            border-bottom: 1px solid #eee;
            padding: 20px;
            font-weight: 600;
        }

        .badge-online {
            background-color: var(--success);
        }

        .badge-offline {
            background-color: var(--danger);
        }

        .progress {
            height: 8px;
            margin-top: 5px;
        }

        .chart-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .navbar-custom {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 15px 0;
        }

        .page-title {
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 0;
        }

        .activity-feed .feed-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .activity-feed .feed-item:last-child {
            border-bottom: none;
        }

        .feed-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
        }

        .feed-content {
            flex: 1;
        }

        .feed-text {
            display: block;
            font-weight: 500;
        }

        .feed-time {
            font-size: 0.8rem;
            color: #6c757d;
        }

        .pagination {
            margin: 20px 0;
        }

        .page-size-selector {
            width: 80px;
            display: inline-block;
        }
        .bg-purple {
            background: linear-gradient(45deg, #6f42c1, #8e44ad) !important;
        }

        /* Improved card styles */
        .stat-card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            min-height: 140px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-self: stretch;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.2rem;
        }

        .stat-label {
            font-size: 0.85rem;
            opacity: 0.9;
            font-weight: 500;
        }

        /* Ensure proper spacing */
        .row {
            margin-right: -10px;
            margin-left: -10px;
        }

        .row > [class*="col-"] {
            padding-right: 10px;
            padding-left: 10px;
        }
    </style>
</head>
<body>
<!-- Sidebar -->
<div class="sidebar col-md-2 d-none d-md-block">
    <div class="text-center mb-4">
        <h4><i class="fas fa-bolt"></i> PowerGen</h4>
        <small>Admin Dashboard</small>
    </div>

    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link active" href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#"><i class="fas fa-users"></i> Clients</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#"><i class="fas fa-tachometer-alt"></i> Counters</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#"><i class="fas fa-cube"></i> Boxes</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#"><i class="fas fa-user-tie"></i> Employees</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#"><i class="fas fa-map-marker-alt"></i> Areas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#"><i class="fas fa-cog"></i> Settings</a>
        </li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <!-- Top Navbar -->
    <nav class="navbar navbar-custom">
        <div class="container-fluid">
            <h2 class="page-title"><i class="fas fa-tachometer-alt me-2"></i>Generator Dashboard</h2>
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i> {{ auth()->user()->name }}
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
{{--                        <li>--}}
{{--                            <a class="dropdown-item" href="{{ route('logout') }}"--}}
{{--                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">--}}
{{--                                <i class="fas fa-sign-out-alt me-2"></i>Logout--}}
{{--                            </a>--}}
{{--                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">--}}
{{--                                @csrf--}}
{{--                            </form>--}}
{{--                        </li>--}}
                    </ul>
                </div>
            </div>
        </div>
    </nav>

{{--    <!-- Summary Cards -->--}}
{{--    <div class="row mb-4">--}}
{{--        <div class="col-xl-2 col-md-4 col-sm-6">--}}
{{--            <div class="card stat-card bg-primary text-white">--}}
{{--                <div class="card-body text-center">--}}
{{--                    <i class="fas fa-users fa-2x mb-3"></i>--}}
{{--                    <h2 class="stat-number">{{ number_format($summary['total_clients']) }}</h2>--}}
{{--                    <p class="stat-label">Total Clients</p>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-xl-2 col-md-4 col-sm-6">--}}
{{--            <div class="card stat-card bg-success text-white">--}}
{{--                <div class="card-body text-center">--}}
{{--                    <i class="fas fa-tachometer-alt fa-2x mb-3"></i>--}}
{{--                    <h2 class="stat-number">{{ number_format($summary['total_counters']) }}</h2>--}}
{{--                    <p class="stat-label">Counters</p>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-xl-2 col-md-4 col-sm-6">--}}
{{--            <div class="card stat-card bg-info text-white">--}}
{{--                <div class="card-body text-center">--}}
{{--                    <i class="fas fa-cube fa-2x mb-3"></i>--}}
{{--                    <h2 class="stat-number">{{ number_format($summary['total_boxes']) }}</h2>--}}
{{--                    <p class="stat-label">Electrical Boxes</p>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-xl-2 col-md-4 col-sm-6">--}}
{{--            <div class="card stat-card bg-warning text-white">--}}
{{--                <div class="card-body text-center">--}}
{{--                    <i class="fas fa-user-tie fa-2x mb-3"></i>--}}
{{--                    <h2 class="stat-number">{{ number_format($summary['total_employees']) }}</h2>--}}
{{--                    <p class="stat-label">Employees</p>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-xl-2 col-md-4 col-sm-6">--}}
{{--            <div class="card stat-card bg-dark text-white">--}}
{{--                <div class="card-body text-center">--}}
{{--                    <i class="fas fa-map-marker-alt fa-2x mb-3"></i>--}}
{{--                    <h2 class="stat-number">{{ number_format($summary['total_areas']) }}</h2>--}}
{{--                    <p class="stat-label">Areas</p>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-xl-2 col-md-4 col-sm-6">--}}
{{--            <div class="card stat-card bg-secondary text-white">--}}
{{--                <div class="card-body text-center">--}}
{{--                    <i class="fas fa-bolt fa-2x mb-3"></i>--}}
{{--                    <h2 class="stat-number">{{ number_format($summary['total_consumption']) }}</h2>--}}
{{--                    <p class="stat-label">Total Consumption (kWh)</p>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
    <!-- Summary Cards - Responsive 4-Column Grid -->
    <div class="row mb-4">
        <!-- Client Count Card -->
        <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-3">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h2 class="stat-number mb-0">{{ number_format($summary['total_clients']) }}</h2>
                            <p class="stat-label mb-0">Total Clients</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small><i class="fas fa-arrow-up me-1"></i> Active users</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Counters Card -->
        <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-3">
            <div class="card stat-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-tachometer-alt fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h2 class="stat-number mb-0">{{ number_format($summary['total_counters']) }}</h2>
                            <p class="stat-label mb-0">Energy Counters</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small><i class="fas fa-bolt me-1"></i> Monitoring consumption</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boxes Card -->
        <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-3">
            <div class="card stat-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-cube fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h2 class="stat-number mb-0">{{ number_format($summary['total_boxes']) }}</h2>
                            <p class="stat-label mb-0">Electrical Boxes</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small><i class="fas fa-map-marker-alt me-1"></i> Distribution points</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employees Card -->
        <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-3">
            <div class="card stat-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-user-tie fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h2 class="stat-number mb-0">{{ number_format($summary['total_employees']) }}</h2>
                            <p class="stat-label mb-0">Employees</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small><i class="fas fa-users-cog me-1"></i> Team members</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Areas Card -->
        <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-3">
            <div class="card stat-card bg-dark text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-map-marker-alt fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h2 class="stat-number mb-0">{{ number_format($summary['total_areas']) }}</h2>
                            <p class="stat-label mb-0">Service Areas</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small><i class="fas fa-layer-group me-1"></i> Coverage zones</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Consumption Card -->
        <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-3">
            <div class="card stat-card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-bolt fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h2 class="stat-number mb-0">{{ number_format($summary['total_consumption']) }}</h2>
                            <p class="stat-label mb-0">Total Consumption</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small><i class="fas fa-chart-line me-1"></i> kWh delivered</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments Card -->
        <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-3">
            <div class="card stat-card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-dollar-sign fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h2 class="stat-number mb-0">${{ number_format($summary['total_payments']) }}</h2>
                            <p class="stat-label mb-0">Total Payments</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small><i class="fas fa-money-bill-wave me-1"></i> Revenue collected</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Card (if you have more data) -->
        <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-3">
            <div class="card stat-card bg-purple text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-chart-pie fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h2 class="stat-number mb-0">98%</h2>
                            <p class="stat-label mb-0">System Uptime</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small><i class="fas fa-check-circle me-1"></i> Operational efficiency</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Data Tables -->
    <div class="row">
        <!-- Clients Table -->
        <div class="col-lg-6 mb-4">
            <div class="card table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-users me-2"></i>Clients (<span id="clients-total">{{ count($clients) }}</span>)</span>
                    <div>
                        <select class="form-select form-select-sm page-size-selector" id="clients-page-size">
                            <option value="5">5 per page</option>
                            <option value="10" selected>10 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="clients-table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Counters</th>
                                <th>Joined</th>
                            </tr>
                            </thead>
                            <tbody id="clients-tbody">
                            <!-- Data will be loaded by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    <!-- JavaScript Pagination -->
                    <nav>
                        <ul class="pagination justify-content-center" id="clients-pagination">
                            <!-- Pagination will be generated by JavaScript -->
                        </ul>
                        <div class="text-center">
                            <small>Showing <span id="clients-start">1</span> to <span id="clients-end">10</span> of <span id="clients-total-display">{{ count($clients) }}</span> entries</small>
                        </div>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Counters Table -->
        <div class="col-lg-6 mb-4">
            <div class="card table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-tachometer-alt me-2"></i>Counters (<span id="counters-total">{{ count($counters) }}</span>)</span>
                    <div>
                        <select class="form-select form-select-sm page-size-selector" id="counters-page-size">
                            <option value="5">5 per page</option>
                            <option value="10" selected>10 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="counters-table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Number</th>
                                <th>Status</th>
                                <th>Current Spending</th>
                                <th>User</th>
                                <th>Box</th>
                            </tr>
                            </thead>
                            <tbody id="counters-tbody">
                            <!-- Data will be loaded by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    <!-- JavaScript Pagination -->
                    <nav>
                        <ul class="pagination justify-content-center" id="counters-pagination">
                            <!-- Pagination will be generated by JavaScript -->
                        </ul>
                        <div class="text-center">
                            <small>Showing <span id="counters-start">1</span> to <span id="counters-end">10</span> of <span id="counters-total-display">{{ count($counters) }}</span> entries</small>
                        </div>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Boxes Table -->
        <div class="col-lg-6 mb-4">
            <div class="card table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-cube me-2"></i>Electrical Boxes (<span id="boxes-total">{{ count($boxes) }}</span>)</span>
                    <div>
                        <select class="form-select form-select-sm page-size-selector" id="boxes-page-size">
                            <option value="5">5 per page</option>
                            <option value="10" selected>10 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="boxes-table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Number</th>
                                <th>Location</th>
                                <th>Capacity</th>
                                <th>Counters</th>
                                <th>Usage</th>
                            </tr>
                            </thead>
                            <tbody id="boxes-tbody">
                            <!-- Data will be loaded by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    <!-- JavaScript Pagination (similar to above) -->
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-lg-6 mb-4">
            <div class="card table-card">
                <div class="card-header">
                    <span><i class="fas fa-history me-2"></i>Recent Activity</span>
                </div>
                <div class="card-body">
                    <div class="activity-feed">
                        @foreach($recentSpendings->take(5) as $spending)
                            <div class="feed-item">
                                <div class="feed-icon bg-primary">
                                    <i class="fas fa-bolt"></i>
                                </div>
                                <div class="feed-content">
                                    <span class="feed-text">Energy reading: {{ $spending->consume }} kWh for Counter {{ $spending->counter->number }}</span>
                                    <span class="feed-time">{{ $spending->date->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endforeach

                        @foreach($recentPayments->take(5) as $payment)
                            <div class="feed-item">
                                <div class="feed-icon bg-success">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <div class="feed-content">
                                    <span class="feed-text">Payment received for Counter {{ $payment->counter->number }}</span>
                                    <span class="feed-time">{{ $payment->date->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<<!-- Pass PHP data to JavaScript -->
<script>
    // Convert PHP data to JavaScript with proper formatting
    const allData = {
        clients: @json($clients),
        counters: @json($counters),
        boxes: @json($boxes),
        employees: @json($employees),
        areas: @json($areas)
    };

    // Debug: Log data to console to verify it's loaded correctly
    console.log('Clients data:', allData.clients);
    console.log('Counters data:', allData.counters);
    console.log('Boxes data:', allData.boxes);
</script>

<script>
    // Pagination functionality
    class TablePaginator {
        constructor(tableId, data, pageSize = 10) {
            this.tableId = tableId;
            this.data = data || [];
            this.pageSize = pageSize;
            this.currentPage = 1;
            this.totalPages = Math.ceil((data?.length || 0) / pageSize);
        }

        renderTable() {
            if (!this.data || this.data.length === 0) {
                this.showNoDataMessage();
                return;
            }

            const startIndex = (this.currentPage - 1) * this.pageSize;
            const endIndex = Math.min(startIndex + this.pageSize, this.data.length);
            const pageData = this.data.slice(startIndex, endIndex);

            const tbody = document.getElementById(`${this.tableId}-tbody`);
            tbody.innerHTML = '';

            pageData.forEach(item => {
                const row = this.createRow(item);
                if (row) {
                    tbody.appendChild(row);
                }
            });

            this.updatePaginationInfo(startIndex, endIndex);
            this.renderPagination();
        }

        showNoDataMessage() {
            const tbody = document.getElementById(`${this.tableId}-tbody`);
            const colSpan = this.getColumnCount();
            tbody.innerHTML = `<tr><td colspan="${colSpan}" class="text-center text-muted py-4">No data available</td></tr>`;

            // Update pagination info
            document.getElementById(`${this.tableId}-start`).textContent = '0';
            document.getElementById(`${this.tableId}-end`).textContent = '0';
            document.getElementById(`${this.tableId}-total-display`).textContent = '0';

            // Clear pagination buttons
            const pagination = document.getElementById(`${this.tableId}-pagination`);
            pagination.innerHTML = '';
        }

        getColumnCount() {
            // Count the number of columns in the table header
            const table = document.getElementById(`${this.tableId}-table`);
            if (table) {
                const headerRow = table.querySelector('thead tr');
                return headerRow ? headerRow.cells.length : 6;
            }
            return 6;
        }

        createRow(item) {
            // This will be overridden by specific table implementations
            const row = document.createElement('tr');
            row.innerHTML = `<td colspan="${this.getColumnCount()}" class="text-center">Row renderer not implemented</td>`;
            return row;
        }

        updatePaginationInfo(startIndex, endIndex) {
            document.getElementById(`${this.tableId}-start`).textContent = startIndex + 1;
            document.getElementById(`${this.tableId}-end`).textContent = endIndex;
            document.getElementById(`${this.tableId}-total-display`).textContent = this.data.length;
            document.getElementById(`${this.tableId}-total`).textContent = this.data.length;
        }

        renderPagination() {
            const pagination = document.getElementById(`${this.tableId}-pagination`);
            pagination.innerHTML = '';

            if (this.totalPages <= 1) return;

            // Previous button
            const prevLi = document.createElement('li');
            prevLi.className = `page-item ${this.currentPage === 1 ? 'disabled' : ''}`;
            prevLi.innerHTML = `<a class="page-link" href="#" onclick="paginator${this.tableId}.goToPage(${this.currentPage - 1}); return false;">Previous</a>`;
            pagination.appendChild(prevLi);

            // Page numbers
            const maxPagesToShow = 5;
            let startPage = Math.max(1, this.currentPage - Math.floor(maxPagesToShow / 2));
            let endPage = Math.min(this.totalPages, startPage + maxPagesToShow - 1);

            if (endPage - startPage + 1 < maxPagesToShow) {
                startPage = Math.max(1, endPage - maxPagesToShow + 1);
            }

            if (startPage > 1) {
                const firstLi = document.createElement('li');
                firstLi.className = 'page-item';
                firstLi.innerHTML = `<a class="page-link" href="#" onclick="paginator${this.tableId}.goToPage(1); return false;">1</a>`;
                pagination.appendChild(firstLi);

                if (startPage > 2) {
                    const ellipsisLi = document.createElement('li');
                    ellipsisLi.className = 'page-item disabled';
                    ellipsisLi.innerHTML = `<span class="page-link">...</span>`;
                    pagination.appendChild(ellipsisLi);
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                const pageLi = document.createElement('li');
                pageLi.className = `page-item ${this.currentPage === i ? 'active' : ''}`;
                pageLi.innerHTML = `<a class="page-link" href="#" onclick="paginator${this.tableId}.goToPage(${i}); return false;">${i}</a>`;
                pagination.appendChild(pageLi);
            }

            if (endPage < this.totalPages) {
                if (endPage < this.totalPages - 1) {
                    const ellipsisLi = document.createElement('li');
                    ellipsisLi.className = 'page-item disabled';
                    ellipsisLi.innerHTML = `<span class="page-link">...</span>`;
                    pagination.appendChild(ellipsisLi);
                }

                const lastLi = document.createElement('li');
                lastLi.className = 'page-item';
                lastLi.innerHTML = `<a class="page-link" href="#" onclick="paginator${this.tableId}.goToPage(${this.totalPages}); return false;">${this.totalPages}</a>`;
                pagination.appendChild(lastLi);
            }

            // Next button
            const nextLi = document.createElement('li');
            nextLi.className = `page-item ${this.currentPage === this.totalPages ? 'disabled' : ''}`;
            nextLi.innerHTML = `<a class="page-link" href="#" onclick="paginator${this.tableId}.goToPage(${this.currentPage + 1}); return false;">Next</a>`;
            pagination.appendChild(nextLi);
        }

        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
                this.renderTable();

                // Scroll to the top of the table
                const tableElement = document.getElementById(`${this.tableId}-table`);
                if (tableElement) {
                    tableElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        }

        changePageSize(newSize) {
            this.pageSize = parseInt(newSize);
            this.currentPage = 1;
            this.totalPages = Math.ceil(this.data.length / this.pageSize);
            this.renderTable();
        }
    }

    // Clients Table Paginator
    class ClientsPaginator extends TablePaginator {
        createRow(client) {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${client.id}</td>
                <td>${client.first_name} ${client.last_name}</td>
                <td>${client.email}</td>
                <td>${client.phone_number}</td>
                <td><span class="badge bg-primary">${client.counters ? client.counters.length : 0}</span></td>
                <td>${new Date(client.created_at).toLocaleDateString()}</td>
            `;
            return row;
        }
    }

    // Counters Table Paginator
    class CountersPaginator extends TablePaginator {
        createRow(counter) {
            const row = document.createElement('tr');

            // Safely access electrical_boxes data
            const electricalBoxes = counter.electrical_boxes || [];
            const boxInfo = electricalBoxes.length > 0
                ? electricalBoxes[0].number
                : '<span class="text-muted">Not assigned</span>';

            // Safely access user data
            const user = counter.user || {};
            const userName = user.first_name && user.last_name
                ? `${user.first_name} ${user.last_name}`
                : 'N/A';

            row.innerHTML = `
                <td>${counter.id}</td>
                <td>${counter.number || 'N/A'}</td>
                <td><span class="badge ${counter.status === 'Active' ? 'bg-success' : 'bg-warning'}">${counter.status || 'Unknown'}</span></td>
                <td>${counter.current_spending ? Number(counter.current_spending).toLocaleString() : 0} kWh</td>
                <td>${userName}</td>
                <td>${boxInfo}</td>
            `;
            return row;
        }
    }

    // Boxes Table Paginator
    class BoxesPaginator extends TablePaginator {
        createRow(box) {
            const row = document.createElement('tr');

            // Safely access counters data
            const counters = box.counters || [];
            const counterCount = counters.length;
            const usagePercentage = box.capacity > 0 ? (counterCount / box.capacity) * 100 : 0;
            const progressClass = usagePercentage >= 80 ? 'bg-danger' :
                usagePercentage >= 60 ? 'bg-warning' : 'bg-success';

            row.innerHTML = `
                <td>${box.id}</td>
                <td>${box.number || 'N/A'}</td>
                <td>${box.location || 'N/A'}</td>
                <td>${box.capacity || 0} counters</td>
                <td>${counterCount}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="progress flex-grow-1 me-2" style="height: 10px;">
                            <div class="progress-bar ${progressClass}" style="width: ${usagePercentage}%"></div>
                        </div>
                        <small>${Math.round(usagePercentage)}%</small>
                    </div>
                </td>
            `;
            return row;
        }
    }

    // Initialize paginators when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Initializing paginators...');

        // Clients paginator
        if (allData.clients && allData.clients.length > 0) {
            window.paginatorclients = new ClientsPaginator('clients', allData.clients, 10);
            paginatorclients.renderTable();
        } else {
            console.warn('No clients data found');
            window.paginatorclients = new ClientsPaginator('clients', [], 10);
            paginatorclients.renderTable();
        }

        // Counters paginator
        if (allData.counters && allData.counters.length > 0) {
            window.paginatorcounters = new CountersPaginator('counters', allData.counters, 10);
            paginatorcounters.renderTable();
        } else {
            console.warn('No counters data found');
            window.paginatorcounters = new CountersPaginator('counters', [], 10);
            paginatorcounters.renderTable();
        }

        // Boxes paginator
        if (allData.boxes && allData.boxes.length > 0) {
            window.paginatorboxes = new BoxesPaginator('boxes', allData.boxes, 10);
            paginatorboxes.renderTable();
        } else {
            console.warn('No boxes data found');
            window.paginatorboxes = new BoxesPaginator('boxes', [], 10);
            paginatorboxes.renderTable();
        }

        // Add event listeners for page size changes
        document.getElementById('clients-page-size')?.addEventListener('change', function(e) {
            paginatorclients.changePageSize(e.target.value);
        });

        document.getElementById('counters-page-size')?.addEventListener('change', function(e) {
            paginatorcounters.changePageSize(e.target.value);
        });

        document.getElementById('boxes-page-size')?.addEventListener('change', function(e) {
            paginatorboxes.changePageSize(e.target.value);
        });
    });

    // Simple JavaScript for interactivity
    $(document).ready(function() {
        // Add hover effects to table rows
        $(document).on('mouseenter', '.table-hover tbody tr', function() {
            $(this).addClass('table-active');
        }).on('mouseleave', '.table-hover tbody tr', function() {
            $(this).removeClass('table-active');
        });

        // Auto-refresh data every 5 minutes
        setInterval(function() {
            window.location.reload();
        }, 300000); // 5 minutes
    });
</script>
</body>
</html>
