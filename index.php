<!DOCTYPE html>
<html lang="en">
<?php require 'bd.php';

$fecha_actual = date('Y-m-d H:i:s');


// Función para clasificar los valores de pace en rangos automáticamente
function clasificarPace($pace_values)
{
    // Calcular el valor mínimo y máximo de pace
    $pace_min = min($pace_values);
    $pace_max = max($pace_values);

    // Convertir pace_min y pace_max a minutos (extraemos solo los minutos como números)
    $pace_min_minutes = (int)explode(":", $pace_min)[0];
    $pace_max_minutes = (int)explode(":", $pace_max)[0];

    // Definir el tamaño del paso para los rangos (aquí lo dividimos en 5 rangos)
    $range_step = ($pace_max_minutes - $pace_min_minutes) / 5;

    // Inicializar el array de rangos
    $pace_ranges = [
        '<' . ($pace_min_minutes + $range_step) => 0,
        ($pace_min_minutes + $range_step) . '-' . ($pace_min_minutes + 2 * $range_step) => 0,
        ($pace_min_minutes + 2 * $range_step) . '-' . ($pace_min_minutes + 3 * $range_step) => 0,
        ($pace_min_minutes + 3 * $range_step) . '-' . ($pace_min_minutes + 4 * $range_step) => 0,
        '>' . ($pace_min_minutes + 4 * $range_step) => 0
    ];

    // Clasificar los valores de pace en los rangos
    foreach ($pace_values as $pace) {
        $pace_minutes = (int)explode(":", $pace)[0];

        if ($pace_minutes < $pace_min_minutes + $range_step) {
            $pace_ranges['<' . ($pace_min_minutes + $range_step)]++;
        } elseif ($pace_minutes >= $pace_min_minutes + $range_step && $pace_minutes < $pace_min_minutes + 2 * $range_step) {
            $pace_ranges[($pace_min_minutes + $range_step) . '-' . ($pace_min_minutes + 2 * $range_step)]++;
        } elseif ($pace_minutes >= $pace_min_minutes + 2 * $range_step && $pace_minutes < $pace_min_minutes + 3 * $range_step) {
            $pace_ranges[($pace_min_minutes + 2 * $range_step) . '-' . ($pace_min_minutes + 3 * $range_step)]++;
        } elseif ($pace_minutes >= $pace_min_minutes + 3 * $range_step && $pace_minutes < $pace_min_minutes + 4 * $range_step) {
            $pace_ranges[($pace_min_minutes + 3 * $range_step) . '-' . ($pace_min_minutes + 4 * $range_step)]++;
        } else {
            $pace_ranges['>' . ($pace_min_minutes + 4 * $range_step)]++;
        }
    }

    // Retornar los rangos y las cantidades
    return $pace_ranges;
}

// Ejecutar la consulta SQL para obtener la última fecha y la diferencia de tiempo
$query = "SELECT 
            MAX(Fecha) AS ultima_fecha,
            TIMEDIFF(NOW(), MAX(Fecha)) AS tiempo_sin_registro
          FROM 
            registros";

$result = mysqli_query($conexion, $query);

// Verificar si la consulta fue exitosa
if ($result) {
    // Obtener los resultados
    $row = mysqli_fetch_assoc($result);
    $ultima_fecha = $row['ultima_fecha'];
    $tiempo_sin_registro = $row['tiempo_sin_registro'];
}

function calcularDiferencia($ultima_fecha, $fecha_actual)
{
    // Convertimos las fechas a objetos DateTime
    $fecha_ultimo = new DateTime($ultima_fecha);
    $fecha_actual = new DateTime($fecha_actual);

    // Calculamos la diferencia entre las dos fechas
    $intervalo = $fecha_ultimo->diff($fecha_actual);

    // Obtenemos los días, horas y minutos
    $dias = $intervalo->days;
    $horas = $intervalo->h;
    $minutos = $intervalo->i;

    // Si las horas o los minutos son cero, los mostramos en la salida
    return "$dias Días, $horas Horas, $minutos Minutos";
}



?>


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



        /* Estilos del encabezado */
        .stat-header {
            margin-bottom: 18px;
            text-align: center;
            position: relative;
        }

        /* Fuente del título */
        .stat-title {
            font-size: 20px;
            font-weight: 700;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Estilos para el valor */
        .stat-time {
            font-size: 24px;
            font-weight: bold;
            color: #4A90E2;
            text-align: center;
        }

        /* Icono de Información */
        .info-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: transparent;
            border: none;
            font-size: 24px;
            color: #4A90E2;
            cursor: pointer;
        }

        /* Estilos para el modal */
        .modal-content {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
        }

        .modal-header {
            border-bottom: 2px solid #ddd;
        }

        .modal-footer {
            border-top: 2px solid #ddd;
            text-align: right;
        }

        /* Estilo del botón de cerrar */
        .btn-close {
            background: none;
            border: none;
            color: #4A90E2;
            font-size: 20px;
            cursor: pointer;
        }
    </style>
</head>

<body>

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

                // Consulta combinada para obtener el mejor registro con estado 'Completado' o 'Finalizada'
                $query = "
          SELECT SUM(KM) as best_record 
          FROM registros 
          WHERE Estado = 'Finalizada'
      ";
                $result = $conexion->query($query);

                if ($result) {
                    $data = $result->fetch_assoc();
                    $distancia_actual = $data['best_record'] ?? 0;

                    echo "<div class='stat-value'>" . number_format($distancia_actual ?? 0, 1) . " KM</div><div class='stat-label'>Total Distancia Recorrida</div>";
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
                <div class="stat-label">Total Corridas Este Mes</div>
                <div class="trend <?php echo $percentage_change >= 0 ? 'trend-up' : 'trend-down'; ?>">
                    <i class="fas fa-arrow-<?php echo $percentage_change >= 0 ? 'up' : 'down'; ?>"></i>
                    <?php echo number_format(abs($percentage_change), 2); ?>% vs ultimo mes
                </div>
            </div>


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
                <div class="stat-label">Mayor Distancia Este Mes</div>
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
            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">Tiempo Sin Correr</h3>
                    <!-- Icono de Información -->
                    <button class="info-icon" data-bs-toggle="modal" data-bs-target="#infoModal">
                        <i class="fas fa-info-circle"></i>
                    </button>
                </div>
                <div class="stat-body">
                    <div class="stat-time">
                        <?php echo calcularDiferencia($ultima_fecha, $fecha_actual); ?>
                    </div>
                </div>
            </div>

            <!-- Modal de Información -->
            <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="infoModalLabel">Información sobre el Tiempo Sin Correr</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            El "Tiempo Sin Correr" se calcula como la diferencia entre la última vez registrada que corriste y la fecha actual. Este valor te ayuda a medir cuánto tiempo ha pasado sin actividad.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
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
                        $pace[] = $row['pace'];
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
                            <td><?php echo $row['pace'] ?? 0; ?> min/km</td>
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
                <h5>Distancia Recorrida por Fecha</h5>
                <canvas id="distanceChart"></canvas>
            </div>
            <div class="chart-card">
                <h5>Ritmo Promedio por Intervalo de Tiempo</h5>
                <canvas id="paceChart"></canvas>
            </div>
        </div>
    </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <?php

    // Consulta SQL para obtener las fechas y las distancias
    $sql_distancia = "SELECT Fecha, KM FROM `registros`ORDER by Fecha DESC limit 5"; // Ajusta la tabla y las columnas
    $result_distancia = $conexion->query($sql_distancia);

    // Inicializar los arrays
    $labels = [];
    $data = [];

    if ($result_distancia->num_rows > 0) {
        // Llenar los arrays con los datos de la consulta
        while ($row = $result_distancia->fetch_assoc()) {
            $labels[] = $row['Fecha']; // Usar la fecha formateada como etiqueta
            $data[] = $row['KM'];  // Distancia correspondiente
        }
    }
    // Llamar a la función para clasificar los pace en rangos
    $pace_ranges = clasificarPace($pace);

    $labels_pace = array_keys($pace_ranges);
    $data_pace = array_values($pace_ranges);

    ?>

    <script>
        // Pasar los datos de PHP a JavaScript
        const labels = <?php echo json_encode($labels); ?>; // Las fechas obtenidas de la consulta
        const data = <?php echo json_encode($data); ?>; // Las distancias obtenidas de la consulta
        const paceLabels = <?php echo json_encode($labels_pace); ?>;
        const paceData = <?php echo json_encode($data_pace); ?>;

        // Distance Chart
        const distanceCtx = document.getElementById('distanceChart').getContext('2d');
        new Chart(distanceCtx, {
            type: 'line',
            data: {
                labels: labels, // Las fechas como etiquetas
                datasets: [{
                    label: 'Distancia (km)',
                    data: data, // Las distancias correspondientes
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
                labels: paceLabels,
                datasets: [{
                    label: 'Number of Runs',
                    data: paceData,
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