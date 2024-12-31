<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Running Analytics</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">


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
            <h1 class="mb-0">Dashboard de Corridas</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editChildresn2">
                <i class="fas fa-plus me-2"></i>
                Nueva Carrera
            </button>
        </div>


        <?php include('ModalCrear.php'); ?>

        <!-- Stats Overview -->
        <div class="stats-grid">
            <div class="stat-card">
                <?php
                $query = "SELECT 
                    SUM(CASE 
                        WHEN MONTH(Fecha) = MONTH(CURRENT_DATE()) AND YEAR(Fecha) = YEAR(CURRENT_DATE()) 
                        THEN KM 
                        ELSE 0 
                    END) AS distancia_actual,
                    SUM(CASE 
                        WHEN MONTH(Fecha) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) AND YEAR(Fecha) = YEAR(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) 
                        THEN KM 
                        ELSE 0 
                    END) AS distancia_anterior,
                    CASE 
                        WHEN SUM(CASE 
                            WHEN MONTH(Fecha) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) AND YEAR(Fecha) = YEAR(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) 
                            THEN KM 
                            ELSE 0 
                        END) = 0 
                        THEN NULL
                        ELSE 
                            ROUND((
                                SUM(CASE 
                                    WHEN MONTH(Fecha) = MONTH(CURRENT_DATE()) AND YEAR(Fecha) = YEAR(CURRENT_DATE()) 
                                    THEN KM 
                                    ELSE 0 
                                END) - 
                                SUM(CASE 
                                    WHEN MONTH(Fecha) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) AND YEAR(Fecha) = YEAR(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) 
                                    THEN KM 
                                    ELSE 0 
                                END)
                            ) / 
                            SUM(CASE 
                                WHEN MONTH(Fecha) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) AND YEAR(Fecha) = YEAR(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) 
                                THEN KM 
                                ELSE 0 
                            END) * 100, 2)
                    END AS porcentaje_cambio
                FROM registros;";
                $result = $conexion->query($query);

                if ($result) {
                    $data = $result->fetch_assoc();
                    $distancia_actual = $data['distancia_actual'] ?? 0;
                    $distancia_anterior = $data['distancia_anterior'] ?? 0;
                    $porcentaje_cambio = $data['porcentaje_cambio'] ?? 0;

                    echo "<div class='stat-value'>{$distancia_actual} KM</div><div class='stat-label'>Total Distancia Este Mes</div>";
                    echo "<div class='trend trend-" . ($porcentaje_cambio >= 0 ? "up" : "down") . "'>";
                    echo "<i class='fas fa-arrow-" . ($porcentaje_cambio >= 0 ? "up" : "down") . "'></i>";
                    echo abs($porcentaje_cambio) . "% vs ultimo mes</div>";
                }

                ?>
            </div>

            <div class="stat-card">
                <div class="stat-value">
                    <?php
                    // Consulta para obtener las actividades del mes actual y el mes anterior
                    $sql = "
            SELECT 
                SUM(CASE WHEN MONTH(Fecha) = MONTH(CURRENT_DATE()) AND YEAR(Fecha) = YEAR(CURRENT_DATE()) THEN 1 ELSE 0 END) AS current_month,
                SUM(CASE WHEN MONTH(Fecha) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) AND YEAR(Fecha) = YEAR(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) THEN 1 ELSE 0 END) AS last_month
            FROM registros
        ";
                    $result = mysqli_query($conexion, $sql);
                    $row = mysqli_fetch_assoc($result);

                    $total_current_month = $row['current_month'] ?? 0;
                    $total_last_month = $row['last_month'] ?? 0;

                    // Calcular el porcentaje de cambio
                    $percentage_change = 0;
                    if ($total_last_month > 0) {
                        $percentage_change = (($total_current_month - $total_last_month) / $total_last_month) * 100;
                    }
                    ?>
                    <?php echo $total_current_month; ?>
                </div>
                <div class="stat-label">Total Corridas</div>
                <div class="trend <?php echo $percentage_change >= 0 ? 'trend-up' : 'trend-down'; ?>">
                    <i class="fas fa-arrow-<?php echo $percentage_change >= 0 ? 'up' : 'down'; ?>"></i>
                    <?php echo number_format(abs($percentage_change), 2); ?>% vs ultimo mes
                </div>
            </div>


            <div class="stat-card">
                <div class="stat-value">
                    <?php
                    // Consulta para obtener el promedio de distancia del mes actual y del mes anterior
                    $sql = "
            SELECT 
                AVG(CASE WHEN MONTH(Fecha) = MONTH(CURRENT_DATE()) AND YEAR(Fecha) = YEAR(CURRENT_DATE()) THEN KM ELSE NULL END) AS avg_current_month,
                AVG(CASE WHEN MONTH(Fecha) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) AND YEAR(Fecha) = YEAR(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) THEN KM ELSE NULL END) AS avg_last_month
            FROM registros
        ";
                    $result = mysqli_query($conexion, $sql);
                    $row = mysqli_fetch_assoc($result);

                    $avg_current_month = $row['avg_current_month'] ?? 0;
                    $avg_last_month = $row['avg_last_month'] ?? 0;

                    // Calcular el porcentaje de cambio
                    $percentage_change = 0;
                    if ($avg_last_month > 0) {
                        $percentage_change = (($avg_current_month - $avg_last_month) / $avg_last_month) * 100;
                    }
                    ?>
                    <?php echo number_format($avg_current_month, 1); ?> km
                </div>
                <div class="stat-label">Promedio Distancia</div>
                <div class="trend <?php echo $percentage_change >= 0 ? 'trend-up' : 'trend-down'; ?>">
                    <i class="fas fa-arrow-<?php echo $percentage_change >= 0 ? 'up' : 'down'; ?>"></i>
                    <?php echo number_format(abs($percentage_change), 2); ?>% vs ultimo mes
                </div>
            </div>


            <div class="stat-card">
                <div class="stat-value">
                    <?php
                    // Consulta para contar las actividades completadas en el mes actual
                    $sql = "
            SELECT MAX(KM) as streak 
            FROM registros 
            WHERE Estado = 'Finalizada' 
            AND MONTH(Fecha) = MONTH(CURRENT_DATE()) 
            AND YEAR(Fecha) = YEAR(CURRENT_DATE())
        ";
                    $result = mysqli_query($conexion, $sql);
                    $row = mysqli_fetch_assoc($result);
                    echo number_format($row['streak'] ?? 0, 1);
                    ?> km

                </div>
                <div class="stat-label">Mayor Distancia Actual</div>
                <div class="trend trend-up">
                    <i class="fas fa-fire"></i>
                    <?php
                    // Consulta combinada para obtener el mejor registro con estado 'Completado' o 'Finalizada'
                    $sql = "
            SELECT MAX(KM) as best_record 
            FROM registros 
            WHERE Estado = 'Finalizada'
        ";
                    $result = mysqli_query($conexion, $sql);
                    $row = mysqli_fetch_assoc($result);
                    echo number_format($row['best_record'] ?? 0, 1);
                    ?> km
                    Mejor Récord Personal
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
                <h5 class="mb-0">Corridas Recientes</h5>
                <!--
                <div class="d-flex gap-2">
                    <input type="text" class="form-control" placeholder="Buscar datos..."
                        style="width: 250px;" onkeyup="searchTable()">
                    <select class="form-select" style="width: 150px;">
                        <option>Todas</option>
                        <option>Finalizadas</option>
                        <option>Pendientes</option>
                        <option>Incompleta</option>
                    </select>
                </div>
                -->
            </div>
            <table class="table" id="activitiesTable">
                <thead>
                    <tr>
                        <th>Corredor</th>
                        <th>Fecha</th>
                        <th>Distancia</th>
                        <th>Tiempo</th>
                        <th>Ritmo</th>
                        <th>Ubicacion</th>
                        <th>Estado</th>
                        <th>Acciones</th>
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
                        $statusClass = $row['Estado'] == 'Finalizada' ? 'bg-success' : 'bg-warning';
                    ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar">
                                        <?php echo substr($row['Usuario'], 0, 1); ?>
                                    </div>
                                    <div>
                                        <div class="fw-semibold"><?php echo $row['Usuario']; ?></div>
              
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div><?php echo date('d-m-Y', strtotime($row['Fecha'])); ?></div>
                                <small class="text-muted"><?php echo date('h:i A', strtotime($row['Fecha'])); ?></small>
                            </td>

                            <td>
                                <div class="fw-semibold"><?php echo $row['KM']; ?> km</div>
                            </td>
                            <td><?php echo $row['Tiempo']; ?></td>
                            <td><?php echo $row['pace']?? 0; ?> min/km</td>
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
                                    <button class="btn btn-sm btn-light" data-bs-toggle="modal"
                                        data-bs-target="#editChildresn<?php echo $row['ID']; ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light text-danger" data-bs-toggle="modal"
                                        data-bs-target="#editChildresn1<?php echo $row['ID']; ?>">
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

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