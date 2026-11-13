<?php

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
  http_response_code(200);
  exit;
}

// Reutilizamos exactamente el mismo conexion.php de la carpeta crud-ibero
require_once "conexion.php";

function responder(int $codigo, array $payload): void {
  http_response_code($codigo);
  echo json_encode($payload, JSON_UNESCAPED_UNICODE);
  exit;
}

$metodo = $_SERVER["REQUEST_METHOD"];
$id     = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
$datos  = json_decode(file_get_contents("php://input"), true) ?? [];

// -------------------------------------------------------------------------
// 1. LEER PRODUCTOS Y CALCULAR LOS 3 KPIs DEL INVENTARIO (GET)
//    (Equivale a las secciones 2 y 3 de nuestro index.php anterior)
// -------------------------------------------------------------------------
if ($metodo === "GET") {
  // Si piden un solo producto para editarlo: api.php?id=3
  if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->execute([$id]);
    $encontrado = $stmt->fetch();

    if (!$encontrado) {
      responder(404, ["ok" => false, "error" => "Producto no encontrado."]);
    }
    responder(200, ["ok" => true, "producto" => $encontrado]);
  }

  // Listar todos o filtrar con el buscador: api.php?q=teclado
  $busqueda = trim($_GET["q"] ?? "");
  if ($busqueda !== "") {
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE nombre LIKE ? OR categoria LIKE ? ORDER BY id DESC");
    $termino = "%" . $busqueda . "%";
    $stmt->execute([$termino, $termino]);
  } else {
    $stmt = $pdo->query("SELECT * FROM productos ORDER BY id DESC");
  }
  $productos = $stmt->fetchAll();

  // Calculamos los mismos 3 indicadores de Inventario IberoTech
  $totalUnidades = 0;
  $valorInventario = 0;
  foreach ($productos as $p) {
    $totalUnidades += intval($p["stock"]);
    $valorInventario += (floatval($p["precio"]) * intval($p["stock"]));
  }

  responder(200, [
    "ok" => true,
    "kpis" => [
      "total_productos"  => count($productos),
      "total_unidades"   => $totalUnidades,
      "valor_inventario" => $valorInventario
    ],
    "productos" => $productos
  ]);
}

// -------------------------------------------------------------------------
// 2. CREAR UN PRODUCTO NUEVO EN EL INVENTARIO (POST)
//    (Equivale al INSERT de $accion === "guardar" cuando $id == 0)
// -------------------------------------------------------------------------
if ($metodo === "POST") {
  $nombre    = trim($datos["nombre"] ?? "");
  $categoria = trim($datos["categoria"] ?? "");
  $precio    = $datos["precio"] ?? "";
  $stock     = $datos["stock"] ?? "";

  if ($nombre === "" || $categoria === "" || $precio === "" || $stock === "") {
    responder(400, ["ok" => false, "error" => "Todos los campos son obligatorios."]);
  }
  if (!is_numeric($precio) || floatval($precio) < 0 || !is_numeric($stock) || intval($stock) < 0) {
    responder(400, ["ok" => false, "error" => "El precio y el stock deben ser números mayores o iguales a 0."]);
  }

  $sql = "INSERT INTO productos (nombre, categoria, precio, stock) VALUES (?, ?, ?, ?)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$nombre, $categoria, floatval($precio), intval($stock)]);

  responder(201, [
    "ok"      => true,
    "mensaje" => "Operación realizada con éxito: registro creado.",
    "id"      => intval($pdo->lastInsertId())
  ]);
}

// -------------------------------------------------------------------------
// 3. ACTUALIZAR UN PRODUCTO EXISTENTE (PUT ?id=X)
//    (Equivale al UPDATE de $accion === "guardar" cuando $id > 0)
// -------------------------------------------------------------------------
if ($metodo === "PUT") {
  if ($id <= 0) {
    responder(400, ["ok" => false, "error" => "Falta el ID del producto en la URL (ej: api.php?id=1)."]);
  }

  $nombre    = trim($datos["nombre"] ?? "");
  $categoria = trim($datos["categoria"] ?? "");
  $precio    = $datos["precio"] ?? "";
  $stock     = $datos["stock"] ?? "";

  if ($nombre === "" || $categoria === "" || $precio === "" || $stock === "") {
    responder(400, ["ok" => false, "error" => "Todos los campos son obligatorios."]);
  }
  if (!is_numeric($precio) || floatval($precio) < 0 || !is_numeric($stock) || intval($stock) < 0) {
    responder(400, ["ok" => false, "error" => "El precio y el stock deben ser números mayores o iguales a 0."]);
  }

  $sql = "UPDATE productos SET nombre = ?, categoria = ?, precio = ?, stock = ? WHERE id = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$nombre, $categoria, floatval($precio), intval($stock), $id]);

  responder(200, [
    "ok"      => true,
    "mensaje" => "Operación realizada con éxito: registro actualizado."
  ]);
}

// -------------------------------------------------------------------------
// 4. ELIMINAR UN PRODUCTO DEL INVENTARIO (DELETE ?id=X)
//    (Equivale al bloque $accion === "eliminar" de index.php)
// -------------------------------------------------------------------------
if ($metodo === "DELETE") {
  if ($id <= 0) {
    responder(400, ["ok" => false, "error" => "Falta el ID del producto a eliminar (ej: api.php?id=3)."]);
  }

  $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
  $stmt->execute([$id]);

  responder(200, [
    "ok"      => true,
    "mensaje" => "Operación realizada con éxito: registro eliminado."
  ]);
}

responder(405, ["ok" => false, "error" => "Método HTTP no soportado."]);