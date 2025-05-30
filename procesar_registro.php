<?php
// Parámetros de conexión, ajusta según tu entorno
$host = 'localhost';
$db = 'aurum';
$user = 'tu_usuario';
$pass = 'tu_contraseña';
$charset = 'utf8mb4';

// Conexión PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
  die("Error de conexión: " . $e->getMessage());
}

// Recibir datos y sanitizar
$nombre = trim($_POST['nombre'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$contrasena = $_POST['contrasena'] ?? '';
$confirmar = $_POST['confirmar'] ?? '';

// Validaciones
if (!$nombre || !$correo || !$telefono || !$contrasena || !$confirmar) {
  die("Faltan datos requeridos.");
}
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
  die("Correo inválido.");
}
if ($contrasena !== $confirmar) {
  die("Las contraseñas no coinciden.");
}

// Hashear contraseña
$hashContrasena = password_hash($contrasena, PASSWORD_DEFAULT);

try {
  $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo, telefono, contrasena) VALUES (?, ?, ?, ?)");
  $stmt->execute([$nombre, $correo, $telefono, $hashContrasena]);

  // Registro exitoso, redirigir a login o index
  header("Location: index.html?registro=exito");
  exit;

} catch (PDOException $e) {
  if ($e->getCode() == 23000) { // correo duplicado
    die("El correo electrónico ya está registrado.");
  }
  die("Error: " . $e->getMessage());
}
?>
