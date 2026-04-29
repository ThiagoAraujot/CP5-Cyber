<?php
// index.php — BlogFácil (PHP)
// BlogFácil — Sistema de blog simples. Contém vulnerabilidades propositais.

$page = $_GET['page'] ?? 'home';
$msg = '';

// ❌ VULNERABILIDADE PROPOSITAL: XSS Refletido + credencial hardcoded
if ($page === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';
    if ($usuario === 'admin' && $senha === 'admin123') {
        header('Location: /');
        exit;
    }
    // XSS: usuário refletido sem htmlspecialchars
    $msg = "<p style='color:red'>Usuário '{$usuario}' inválido!</p>";
}

// ❌ VULNERABILIDADE PROPOSITAL: XSS na busca
$query = $_GET['q'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>BlogFácil</title>
<style>
body{font-family:Arial;max-width:600px;margin:50px auto;padding:20px}
h1{color:#27ae60}input,button{padding:10px;margin:5px;width:90%}
button{background:#27ae60;color:#fff;border:none;cursor:pointer}
nav a{margin-right:15px;color:#27ae60}
</style>
</head>
<body>
<h1>📝 BlogFácil</h1>
<nav>
    <a href="/">Home</a>
    <a href="/?page=login">Login</a>
    <a href="/?page=buscar">Buscar</a>
</nav>

<?php if ($page === 'home'): ?>
    <p>Bem-vindo ao BlogFácil — sistema de blog simples.</p>
    <h3>Posts recentes:</h3>
    <ul>
        <li>Segurança em aplicações web</li>
        <li>Como o OWASP ZAP funciona</li>
        <li>DevSecOps na prática</li>
    </ul>

<?php elseif ($page === 'login'): ?>
    <h2>🔒 Login</h2>
    <?= $msg ?>
    <form method="POST" action="/?page=login">
        <input name="usuario" placeholder="Usuário"><br>
        <input name="senha" type="password" placeholder="Senha"><br>
        <button type="submit">Entrar</button>
    </form>

<?php elseif ($page === 'buscar'): ?>
    <h2>🔍 Buscar Posts</h2>
    <form method="GET">
        <input type="hidden" name="page" value="buscar">
        <!-- ❌ VULNERABILIDADE: query refletida sem htmlspecialchars (XSS) -->
        <input name="q" value="<?= $query ?>" placeholder="Buscar...">
        <button type="submit">Buscar</button>
    </form>
    <?php if ($query): ?>
        <p>Você buscou: <?= $query ?></p>
        <p>Nenhum resultado encontrado.</p>
    <?php endif; ?>
<?php endif; ?>

</body>
</html>
