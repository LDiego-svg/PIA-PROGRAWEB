<?php
session_start();

if (!isset($_SESSION['usuario_correo'])) {
  die("Acceso no autorizado. Inicia sesión.");
}

$correo = $_SESSION['usuario_correo'];

// Parámetros de conexión
$host = 'localhost';
$db = 'aurum';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// Conexión PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);

  // Obtener datos del usuario por correo
  $stmt = $pdo->prepare("SELECT nombre, correo, telefono FROM usuarios WHERE correo = ?");
  $stmt->execute([$_SESSION['usuario_correo']]);

  $usuario = $stmt->fetch();

  if (!$usuario) {
    throw new Exception("Usuario no encontrado.");
  }

} catch (Exception $e) {
  die("Error al obtener los datos del usuario: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cuenta - Aurum Residencia</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
  />
  <link rel="stylesheet" href="estilos_comunes.css">
</head>
<body>

  <h1 class="titulo">
    <a href="index.html" style="text-decoration: none; color: inherit;">Aurum Residencia</a>
  </h1>

  <nav class="menu">
    <ol>
      <li class="menu-item"><a href="index.html">Home</a></li>
      <li class="menu-item"><a href="sobre_nosotros.html">Sobre Nosotros</a></li>
      <li class="menu-item"><a href="ubicacion.html">Ubicación</a></li>
      <li class="menu-item active"><a href="cuenta.php">Cuenta</a></li>
      <li class="menu-item"><a href="reservas.html">Reservas</a></li>
      <li class="menu-item">
        <form method="POST" action="logout.php" style="display:inline;">
          <button type="submit" class="btn btn-link btn-sm">Cerrar sesión</button>
        </form>
      </li>
    </ol>
  </nav>

  <section class="container my-5">
    <h2 class="mb-4">Mi Cuenta</h2>
    <div class="border rounded p-4 shadow-sm">
      <p><strong>Nombre completo:</strong> <?= htmlspecialchars($usuario['nombre']) ?></p>
      <p><strong>Correo electrónico:</strong> <?= htmlspecialchars($usuario['correo']) ?></p>
      <p><strong>Teléfono:</strong> <?= htmlspecialchars($usuario['telefono']) ?></p>
    </div>
  </section>

  <footer>
    <p>&copy; 2025 Aurum Residencia. Proyecto universitario - FIME UANL.</p>
    <p>Contacto: info@aurumresidencia.com | Teléfono: 81 5623 5489</p>    
  </footer>

</body>
</html>
