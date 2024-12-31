<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Running Analytics</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2563EB;
            --secondary: #6366F1;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            --background: #F8FAFC;
            --card-bg: #FFFFFF;
            --text: #1E293B;
        }

        body {
            background: var(--background);
            color: var(--text);
            font-family: system-ui, -apple-system, sans-serif;
            margin: 0;
            padding: 0;
        }

        .dashboard-container {
            display: grid;
            grid-template-columns: 240px 1fr;
            min-height: 100vh;
        }

        .sidebar {
            background: var(--card-bg);
            padding: 1.5rem;
            border-right: 1px solid #E2E8F0;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--text);
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }

        .nav-link:hover {
            background: #F1F5F9;
            color: var(--primary);
        }

        .nav-link.active {
            background: var(--primary);
            color: white;
        }

        .main-content {
            padding: 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #64748B;
            font-size: 0.875rem;
        }

        .trend {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .trend-up {
            color: var(--success);
        }

        .trend-down {
            color: var(--danger);
        }

        .chart-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .chart-card {
            background: var(--card-bg);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .progress-card {
            background: var(--card-bg);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .progress-title {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .progress {
            height: 0.5rem;
            border-radius: 9999px;
            margin-bottom: 0.5rem;
        }

        .table-card {
            background: var(--card-bg);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .table thead {
            background: #F8FAFC;
        }

        .table th {
            font-weight: 600;
            color: #64748B;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }

        .avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 9999px;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .badge {
            padding: 0.5rem 0.75rem;
            border-radius: 9999px;
            font-weight: 500;
            font-size: 0.75rem;
        }
    </style>
</head>

<body>
    <?php require 'bd.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-0">Running Analytics</h1>
                <button class="btn btn-primary" data-toggle="modal" data-target="#editChildresn2">
                    <i class="fas fa-plus me-2"></i>
                    Record Run
                </button>
            </div>

            <!-- Stats Overview -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">
                        <?php
                        $sql = "SELECT SUM(KM) as total_km FROM registros WHERE MONTH(Fecha) = MONTH(CURRENT_DATE())";
                        $result = mysqli_query($conexion, $sql);
                        $row = mysqli_fetch_assoc($result);
                        echo number_format($row['total_km'] ?? 0, 1);
                        ?> km
                    </div>
                    <div class="stat-label">Distance This Month</div>
                    <div class="trend trend-up">
                        <i class="fas fa-arrow-up"></i>
                        12% vs last month
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-value">
                        <?php
                        $sql = "SELECT COUNT(*) as total_runs FROM registros WHERE MONTH(Fecha) = MONTH(CURRENT_DATE())";
                        $result = mysqli_query($conexion, $sql);
                        $row = mysqli_fetch_assoc($result);
                        echo $row['total_runs'] ?? 0;
                        ?>
                    </div>
                    <div class="stat-label">Total Activities</div>
                    <div class="trend trend-up">
                        <i class="fas fa-arrow-up"></i>
                        8% vs last month
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-value">
                        <?php
                        $sql = "SELECT AVG(KM) as avg_km FROM registros WHERE MONTH(Fecha) = MONTH(CURRENT_DATE())";
                        $result = mysqli_query($conexion, $sql);
                        $row = mysqli_fetch_assoc($result);
                        echo number_format($row['avg_km'] ?? 0, 1);
                        ?> km
                    </div>
                    <div class="stat-label">Average Distance</div>
                    <div class="trend trend-down">
                        <i class="fas fa-arrow-down"></i>
                        3% vs last month
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-value">
                        <?php
                        $sql = "SELECT COUNT(*) as streak FROM registros 
                               WHERE Estado = 'Completado' 
                               AND Fecha >= DATE_SUB(CURRENT_DATE(), INTERVAL 7 DAY)";
                        $result = mysqli_query($conexion, $sql);
                        $row = mysqli_fetch_assoc($result);
                        echo $row['streak'] ?? 0;
                        ?>
                    </div>
                    <div class="stat-label">Current Streak</div>
                    <div class="trend trend-up">
                        <i class="fas fa-fire"></i>
                        Personal Best
                    </div>
                </div>
            </div>

            <!-- Goals Progress -->
            <div class="progress-card">
                <div class="progress-title">
                    <h5 class="mb-0">Monthly Goals Progress</h5>
                    <span>18 days remaining</span>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Distance Goal (150km)</span>
                        <span>65%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-primary" style="width: 65%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Activity Goal (20 runs)</span>
                        <span>80%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: 80%"></div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="table-card">
                <div class="d-flex justify-content-between align-items-center p-4">
                    <h5 class="mb-0">Recent Activities</h5>
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control" placeholder="Search activities..."
                            style="width: 250px;" onkeyup="searchTable()">
                        <select class="form-select" style="width: 150px;">
                            <option>All Activities</option>
                            <option>Completed</option>
                            <option>In Progress</option>
                        </select>
                    </div>
                </div>
                <table class="table" id="activitiesTable">
                    <thead>
                        <tr>
                            <th>Runner</th>
                            <th>Date</th>
                            <th>Distance</th>
                            <th>Time</th>
                            <th>Pace</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT *, 
                               TIME_FORMAT(SEC_TO_TIME(
                                 TIME_TO_SEC(Tiempo) / KM
                               ), '%i:%s') as pace 
                               FROM registros 
                               ORDER BY Fecha DESC 
                               LIMIT 10";
                        $result = mysqli_query($conexion, $sql);

                        while ($row = mysqli_fetch_array($result)) {
                            $statusClass = $row['Estado'] == 'Completado' ? 'bg-success' : 'bg-warning';
                        ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar">
                                            <?php echo substr($row['Usuario'], 0, 1); ?>
                                        </div>
                                        <div>
                                            <div class="fw-semibold"><?php echo $row['Usuario']; ?></div>
                                            <small class="text-muted">Runner</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div><?php echo date('M d, Y', strtotime($row['Fecha'])); ?></div>
                                    <small class="text-muted"><?php echo date('h:i A', strtotime($row['Fecha'])); ?></small>
                                </td>
                                <td>
                                    <div class="fw-semibold"><?php echo $row['KM']; ?> km</div>
                                </td>
                                <td><?php echo $row['Tiempo']; ?></td>
                                <td><?php echo $row['pace']; ?> /km</td>
                                <td>
                                    <i class="fas fa-map-marker-alt text-primary me-1"></i>
                                    <?php echo $row['Ubicacion']; ?>
                                </td>
                                <td>
                                    <span class="badge <?php echo $statusClass; ?>">
                                        <?php echo $row['Estado']; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-light" data-toggle="modal"
                                            data-target="#editChildresn<?php echo $row['ID']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-light text-danger" data-toggle="modal"
                                            data-target="#editChildresn1<?php echo $row['ID']; ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php include('ModalEditar.php'); ?>
                            <?php include('ModalDelete.php'); ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Charts Grid -->
            <div class="chart-grid mt-4">
                <div class="chart-card">
                    <h5>Weekly Distance Trend</h5>
                    <canvas id="distanceChart"></canvas>
                </div>
                <div class="chart-card">
                    <h5>Pace Analysis</h5>
                    <canvas id="paceChart"></canvas>
                </div>
            </div>

            <!-- Training Load -->
            <div class="progress-card">
                <h5>Training Load</h5>
                <div class="d-flex gap-4 mt-3">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Weekly Load</span>
                            <span>Optimal Zone</span>
                        </div>
                        <div class="progress" style="height: 1rem;">
                            <div class="progress-bar bg-success" style="width: 75%"></div>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Recovery Status</span>
                            <span>85% Ready</span>
                        </div>
                        <div class="progress" style="height: 1rem;">
                            <div class="progress-bar bg-info" style="width: 85%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <?php include('ModalCrear.php'); ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>

    <script>
        // Distance Chart
        const distanceCtx = document.getElementById('distanceChart').getContext('2d');
        new Chart(distanceCtx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Distance (km)',
                    data: [5.2, 7.1, 0, 8.3, 6.4, 12.5, 4.8],
                    borderColor: '#2563EB',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Pace Chart
        const paceCtx = document.getElementById('paceChart').getContext('2d');
        new Chart(paceCtx, {
            type: 'bar',
            data: {
                labels: ['<5:00', '5:00-5:30', '5:31-6:00', '6:01-6:30', '>6:30'],
                datasets: [{
                    label: 'Number of Runs',
                    data: [2, 5, 8, 4, 3],
                    backgroundColor: '#6366F1'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Search functionality
        function searchTable() {
            const input = document.querySelector('input[type="text"]');
            const filter = input.value.toLowerCase();
            const tbody = document.querySelector('#activitiesTable tbody');
            const rows = tbody.getElementsByTagName('tr');

            for (let row of rows) {
                const cells = row.getElementsByTagName('td');
                let found = false;

                for (let cell of cells) {
                    if (cell.textContent.toLowerCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }

                row.style.display = found ? '' : 'none';
            }
        }

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>

</html>