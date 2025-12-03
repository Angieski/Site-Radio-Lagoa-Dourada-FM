<?php
$servername = "localhost";
$username = "raditfm_site";
$password = "Kakaka11*Ma";
$dbname = "raditfm_site";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consulta para a transmissão ao vivo
$sql_strea = "SELECT android, metada_url FROM strea WHERE id = 17";
$result_strea = $conn->query($sql_strea);
$radio_data = $result_strea->fetch_assoc();

// Consulta para programas (filtro por grupo RdsLag e status ativo)
$programas_por_dia = [];
$sql_programas = "SELECT nome, inf, inicio, dia FROM programas 
                 WHERE grupo = 'RdsLag' AND status = '1' 
                 ORDER BY dia, inicio";
$result_programas = $conn->query($sql_programas);

$query_esporte = "SELECT * FROM eqpesporte";
$result_esporte = $conn->query($query_esporte); // Corrigido para usar $conn
$equipe_esporte = $result_esporte ? $result_esporte->fetch_all(MYSQLI_ASSOC) : [];

$query_equipe = "SELECT * FROM equipelagoa ORDER BY id ASC";
$result_equipe = $conn->query($query_equipe);
$equipe_geral = $result_equipe ? $result_equipe->fetch_all(MYSQLI_ASSOC) : [];

if ($result_programas->num_rows > 0) {
    while ($row = $result_programas->fetch_assoc()) {
        $dia = $row['dia'];
        if (!isset($programas_por_dia[$dia])) {
            $programas_por_dia[$dia] = [];
        }
        array_push($programas_por_dia[$dia], $row);
    }
}

$conn->close();
?>