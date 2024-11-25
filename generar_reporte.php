<?php
require('fpdf/fpdf.php');
require('config.php'); // Incluye la conexión a la base de datos

// Obtener datos del formulario
$tipoReporte = $_POST['tipoReporte']; // Puede ser "semanal" o "mensual"
$fechaInicio = $_POST['fechaInicio'];
$fechaFin = $_POST['fechaFin'];

// Ajuste para reportes semanales o mensuales si es necesario
if ($tipoReporte === 'semanal') {
    $fechaFin = date('Y-m-d', strtotime($fechaInicio . ' + 6 days'));
} elseif ($tipoReporte === 'mensual') {
    $fechaFin = date('Y-m-d', strtotime($fechaInicio . ' + 1 month - 1 day'));
}

// Consulta a la base de datos para obtener los datos de entradas, salidas y saldo en el período definido
$queryEntradas = "SELECT * FROM entrada_materiales WHERE fecha BETWEEN '$fechaInicio' AND '$fechaFin'";
$querySalidas = "SELECT * FROM salida_material WHERE fecha BETWEEN '$fechaInicio' AND '$fechaFin'";

// Ejecutar consultas
$resultEntradas = $conn->query($queryEntradas);
$resultSalidas = $conn->query($querySalidas);

// Calcular el saldo inicial, final y el total en dinero basado en las entradas
$saldoInicial = 0;
$saldoFinal = 0;
$totalDinero = 0;

// Crear el PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);

// Título del reporte
$pdf->Cell(0, 10, "Reporte $tipoReporte", 0, 1, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, "Periodo: $fechaInicio a $fechaFin", 0, 1, 'L');
$pdf->Ln(10);

// ==========================
// 1. Tabla de Saldo
// ==========================
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Tabla de Saldo', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(40, 10, 'Fecha', 1);
$pdf->Cell(40, 10, 'Saldo Anterior', 1);
$pdf->Cell(40, 10, 'Cantidad Movida', 1);
$pdf->Cell(40, 10, 'Saldo Actual', 1);
$pdf->Cell(30, 10, 'Costo Promedio', 1);
$pdf->Ln();

$datosSaldo = [
    ['2024-11-20', 1000, 200, 1200, 10.5],
    ['2024-11-21', 1200, 100, 1100, 10.7],
];

foreach ($datosSaldo as $fila) {
    $pdf->Cell(40, 10, $fila[0], 1);
    $pdf->Cell(40, 10, $fila[1], 1);
    $pdf->Cell(40, 10, $fila[2], 1);
    $pdf->Cell(40, 10, $fila[3], 1);
    $pdf->Cell(30, 10, $fila[4], 1);
    $pdf->Ln();
}
$pdf->Ln(10);

// ==========================
// 2. Tabla de Entradas
// ==========================
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Tabla de Entradas', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, 10, 'Fecha', 1);
$pdf->Cell(40, 10, 'Producto', 1);
$pdf->Cell(30, 10, 'Cantidad', 1);
$pdf->Cell(30, 10, 'Costo', 1);
$pdf->Cell(50, 10, 'Proveedor', 1);
$pdf->Ln();

while ($entrada = $resultEntradas->fetch(PDO::FETCH_ASSOC)) {
    // Convertir los valores a tipo float
    $cantidad = (float)$entrada['cantidad'];
    $costo = (float)$entrada['costo'];

    $pdf->Cell(30, 10, $entrada['fecha'], 1);
    $pdf->Cell(40, 10, $entrada['producto'], 1);
    $pdf->Cell(30, 10, $cantidad, 1);
    $pdf->Cell(30, 10, $costo, 1);
    $pdf->Cell(50, 10, $entrada['proveedor'], 1);
    $pdf->Ln();

    $totalDinero += $cantidad * $costo;
}
$pdf->Ln(10);

// ==========================
// 3. Tabla de Salidas
// ==========================
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Tabla de Salidas', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, 10, 'Fecha', 1);
$pdf->Cell(40, 10, 'Producto', 1);
$pdf->Cell(30, 10, 'Cantidad', 1);
$pdf->Cell(40, 10, 'Supervisor', 1);
$pdf->Cell(50, 10, 'Colonia', 1);
$pdf->Ln();

while ($salida = $resultSalidas->fetch(PDO::FETCH_ASSOC)) {
    $cantidad = (float)$salida['cantidad'];

    $pdf->Cell(30, 10, $salida['fecha'], 1);
    $pdf->Cell(40, 10, $salida['producto'], 1);
    $pdf->Cell(30, 10, $cantidad, 1);
    $pdf->Cell(40, 10, $salida['supervisor'], 1);
    $pdf->Cell(50, 10, $salida['colonia'], 1);
    $pdf->Ln();
}
$pdf->Ln(10);

// ==========================
// 4. Cálculo total en dinero
// ==========================
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Total en Dinero (basado en Entradas)', 0, 1, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Total: $' . number_format($totalDinero, 2), 0, 1, 'L');

// Salida del PDF
$pdf->Output();
?>
