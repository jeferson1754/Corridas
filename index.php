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
    // Si las horas o los minutos son cero, los mostramos en la salida
    return "{$dias}d {$horas}h";
}

$tiempo_sin_correr = calcularDiferencia($ultima_fecha, $fecha_actual);

?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Running Analytics</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

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
            --primary-color: #4F46E5;
            --secondary-color: #10B981;
            --danger-color: #EF4444;
            --background-color: #F3F4F6;
            --card-background: #FFFFFF;
            --text-primary: #1F2937;
            --text-secondary: #6B7280;
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
            background: var(--card-background);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.1);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(79, 70, 229, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .stat-icon i {
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }

        .trend {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            padding: 0.5rem;
            border-radius: 0.5rem;
            background: rgba(16, 185, 129, 0.1);
            width: fit-content;
        }

        .trend-up {
            color: var(--secondary-color);
        }

        .trend-down {
            color: var(--danger-color);
            background: rgba(239, 68, 68, 0.1);
        }

        .stat-card.highlight {
            background: linear-gradient(135deg, var(--primary-color), #6366F1);
            color: white;
        }

        .stat-card.highlight .stat-value,
        .stat-card.highlight .stat-label {
            color: white;
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

        .time-display {
            background: #f8f9fa;
            border-left: 4px solid #4CAF50;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 0 8px 8px 0;
        }

        .time-icon {
            color: #4CAF50;
            margin-right: 0.5rem;
        }

        .input-container {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .km-input {
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 0.8rem;
            padding-left: 2.5rem;
            font-size: 1.1rem;
            width: 100%;
            transition: all 0.3s ease;
        }

        .km-input:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25);
        }

        .km-icon {
            position: absolute;
            left: 0.8rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        #evaluarBtn {
            background-color: #4CAF50;
            border: none;
            padding: 0.8rem;
            width: 100%;
            font-size: 1.1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        #evaluarBtn:hover {
            background-color: #45a049;
            transform: translateY(-1px);
        }

        .resultado {
            margin-top: 1.5rem;
            padding: 1rem;
            border-radius: 8px;
            font-weight: 500;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }

        .resultado.show {
            opacity: 1;
            transform: translateY(0);
        }

        .resultado.recomendable {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .resultado.no-recomendable {
            background-color: #fff3cd;
            color: #856404;
            border-left: 4px solid #ffc107;
        }

        .resultado.peligroso {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.9rem;
            margin-top: 0.5rem;
            display: none;
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
                <div class="stat-icon">
                    <i class="fas fa-road"></i>
                </div>
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
                <div class="stat-icon">
                    <i class="fas fa-running"></i>
                </div>
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
                <div class="stat-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
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
                <div class="stat-icon">
                    <i class="fas fa-trophy"></i>
                </div>
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
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value">
                    <?php echo $tiempo_sin_correr ?>


                </div>
                <div class="stat-label">Tiempo Sin Correr</div>
                <!-- Icono de Información -->
                <button class="info-icon" data-bs-toggle="modal" data-bs-target="#infoModal">
                    <i class="fas fa-lightbulb"></i>
                </button>

            </div>

            <!-- Modal de Recomendación de Kilómetros -->
            <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="infoModalLabel">
                                <i class="fas fa-running me-2"></i>
                                Recomendación de Kilómetros
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="time-display">
                                <i class="fas fa-clock time-icon"></i>
                                Tiempo Sin Correr: <span id="tiempoSinCorrer"></span>
                            </div>

                            <div class="input-container">
                                <i class="fas fa-route km-icon"></i>
                                <input
                                    type="number"
                                    id="kilometros_modal"
                                    class="km-input"
                                    min="1"
                                    placeholder="¿Cuántos kilómetros planeas correr?"
                                    aria-label="Kilómetros a correr">
                                <div class="error-message">Por favor, ingresa una distancia válida</div>
                            </div>

                            <button id="evaluarBtn" class="btn btn-primary">
                                <i class="fas fa-calculator me-2"></i>
                                Evaluar Recomendación
                            </button>

                            <div id="resultado" class="resultado"></div>
                        </div>
                    </div>
                </div>
            </div>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const tiempoSinCorrer = "<?php echo $tiempo_sin_correr; ?>";
                    document.getElementById("tiempoSinCorrer").innerText = tiempoSinCorrer;

                    const [diasSinCorrer, horasSinCorrer] = tiempoSinCorrer.split(' ')
                        .map(part => parseInt(part.replace(/[a-z]/gi, '')));

                    const kilometrosInput = document.getElementById("kilometros_modal");
                    const evaluarBtn = document.getElementById("evaluarBtn");
                    const resultadoDiv = document.getElementById("resultado");
                    const errorMessage = document.querySelector(".error-message");

                    function validateInput() {
                        const value = kilometrosInput.value.trim();
                        const isValid = value !== "" && !isNaN(value) && parseFloat(value) > 0;

                        if (!isValid) {
                            errorMessage.style.display = "block";
                            kilometrosInput.classList.add("is-invalid");
                        } else {
                            errorMessage.style.display = "none";
                            kilometrosInput.classList.remove("is-invalid");
                        }

                        return isValid;
                    }

                    kilometrosInput.addEventListener("input", validateInput);

                    evaluarBtn.addEventListener("click", function() {
                        if (!validateInput()) return;

                        const kilometros = parseFloat(kilometrosInput.value);
                        const recomendacion = calcularRecomendacion(diasSinCorrer, kilometros);

                        // Determinar el tipo de resultado
                        let resultadoClass = "recomendable";
                        if (recomendacion.startsWith("No recomendable")) {
                            resultadoClass = "no-recomendable";
                        } else if (recomendacion.startsWith("Peligroso")) {
                            resultadoClass = "peligroso";
                        }

                        // Actualizar clases y contenido
                        resultadoDiv.className = `resultado ${resultadoClass}`;
                        resultadoDiv.innerHTML = `
                <i class="fas fa-${resultadoClass === 'recomendable' ? 'check-circle' : 
                                   resultadoClass === 'no-recomendable' ? 'exclamation-circle' : 
                                   'exclamation-triangle'} me-2"></i>
                ${recomendacion}
            `;

                        // Forzar el recálculo del CSS para activar la animación
                        resultadoDiv.offsetHeight;
                        resultadoDiv.classList.add("show");
                    });

                    function calcularRecomendacion(dias, km) {
                        if (dias < 3) {
                            return km <= 5 ? "Recomendable: Perfecto para mantener la actividad." :
                                "No recomendable: Reduce la distancia.";
                        } else if (dias >= 3 && dias <= 7) {
                            return km <= 5 ? "Recomendable: Ideal para mantener la resistencia." :
                                km <= 10 ? "No recomendable: Considera distancias más cortas." :
                                "Peligroso: Distancia muy larga.";
                        } else if (dias > 7 && dias <= 30) {
                            return km <= 3 ? "Recomendable: Reintroducción gradual." :
                                "Peligroso: Distancia muy larga.";
                        } else {
                            return km <= 3 ? "Recomendable: Inicia con distancias cortas." :
                                "Peligroso: Demasiado tiempo sin correr.";
                        }
                    }
                });
            </script>




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