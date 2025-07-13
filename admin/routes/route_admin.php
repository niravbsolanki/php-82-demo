<?php session_start();
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

//(only for localhost)
$baseFolder = '/php-demo'; //run project ex. localhost/php-demo/
if (strpos($request, $baseFolder) === 0) {
    $request = substr($request, strlen($baseFolder));
}

// Normalize
$request = rtrim($request, '/');
if ($request === '') $request = '/';

$prefix = '/admin';

switch ($request) {
   
    case $prefix:
        require 'views/auth/login.php';
        break;

    case $prefix .'/dashboard':
        
        if (!isset($_SESSION['admin_logged_in'])) {
            header("Location: /admin/login");
            exit;
        }
        require 'views/dashboard.php';
        break;

    case $prefix .'/login':
        
        if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']) {
            header("Location: /admin/dashboard");
                exit;
        }
        require 'views/auth/login.php';
        break;

    case $prefix .'/login/post':
        call_controller('auth/LoginController', 'LoginController', 'login');
        break;

    case $prefix .'/logout':
        call_controller('auth/LoginController', 'LoginController', 'logout');
        break;

    default:
        http_response_code(404);
        echo "404 Page Not Found";
        break;
}

function call_controller(string $filePath, string $className, string $method): void
{
    require_once "controllers/{$filePath}.php";
    $controller = new $className();
    $controller->$method();
}

?>