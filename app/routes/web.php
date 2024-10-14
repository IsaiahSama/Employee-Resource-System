<?php 

require_once 'framework/AuthGuard.php';
require_once 'framework/Status.php';

# Repositories
require_once 'app/repositories/UserRepository.php';
require_once 'app/repositories/TaskRepository.php';
require_once 'app/repositories/RoleRepository.php';

// Controllers
require_once 'app/controllers/ErrorController.php';
require_once 'app/controllers/IndexController.php';
require_once 'app/controllers/RegisterController.php';
require_once 'app/controllers/LoginController.php';
require_once 'app/controllers/LogoutController.php';
require_once 'app/controllers/DashboardController.php';
require_once 'app/controllers/DashboardAdminController.php';
require_once 'app/controllers/DashboardEmployeeController.php';
require_once 'app/controllers/DashboardManagerController.php';
require_once 'app/controllers/TaskController.php';

$get_routes = [];
$post_routes = [];
$delete_routes = [];
$put_routes = [];

// Errors
$get_routes["/401"] = ['controller' => 'ErrorController', 'method' => 'error401', 'httpMethod' => 'GET'];
$get_routes["/404"] = ['controller' => 'ErrorController', 'method' => 'error404', 'httpMethod' => 'GET'];
$get_routes["/500"] = ['controller' => 'ErrorController', 'method' => 'error500', 'httpMethod' => 'GET'];

// Get Routes

$get_routes['/'] = ['controller' => 'IndexController', 'method' => 'index', 'httpMethod' => 'GET'];
$get_routes['/register'] = ['controller' => 'RegisterController', 'method' => 'index', 'httpMethod' => 'GET'];
$get_routes['/login'] = ['controller' => 'LoginController', 'method' => 'index', 'httpMethod' => 'GET'];
$get_routes['/logout'] = ['controller' => 'LogoutController', 'method' => 'logout', 'httpMethod' => 'GET'];
$get_routes['/dashboard'] = ['controller' => 'DashboardController', 'method' => 'index', 'httpMethod' => 'GET'];

# Authd Get Routes
$get_routes['/dashboard/admin'] = ['controller' => 'DashboardAdminController', 'method' => 'index', 'httpMethod' => 'GET'];
$get_routes['/dashboard/manager'] = ['controller' => 'DashboardManagerController', 'method' => 'index', 'httpMethod' => 'GET'];
$get_routes['/dashboard/employee'] = ['controller' => 'DashboardEmployeeController', 'method' => 'index', 'httpMethod' => 'GET'];
$get_routes['/tasks/create'] = ['controller' => 'TaskController', 'method' => 'index', 'httpMethod' => 'GET'];
$get_routes['/tasks/edit'] = ['controller' => 'TaskController', 'method' => 'edit', 'httpMethod' => 'GET'];
$get_routes['/users/delete'] = ['controller' => 'DashboardAdminController', 'method' => 'deleteUser', 'httpMethod' => 'GET'];
$get_routes['/tasks/filter'] = ['controller' => 'TaskController', 'method' => 'filterTasks', 'httpMethod' => 'GET'];


# Post Routes
$post_routes['/register'] = ['controller' => 'RegisterController', 'method' => 'handleSubmission', 'httpMethod' => 'POST'];
$post_routes['/login'] = ['controller' => 'LoginController', 'method' => "handleSubmission", 'httpMethod' => 'POST'];
$post_routes['/users/create'] = ['controller' => 'RegisterController', 'method' => 'adminAddNewUser', 'httpMethod' => 'POST'];
$post_routes['/tasks/create'] = ['controller' => 'TaskController', 'method' => 'createTask', 'httpMethod' => 'POST'];
$post_routes['/tasks/edit'] = ['controller' => 'TaskController', 'method' => 'updateTask', 'httpMethod' => 'POST'];

$methods = [];

$methods["GET"] = $get_routes;
$methods["POST"] = $post_routes;
$methods["PUT"] = $put_routes;
$methods["DELETE"] = $delete_routes;

function route($path) {
    $separators = ['/', '\\'];

    global $methods;

    $path = DIRECTORY_SEPARATOR . $path;

    $routes = $methods[$_SERVER["REQUEST_METHOD"]];

    foreach ($routes as $route => $info) {

        $route = str_replace($separators, DIRECTORY_SEPARATOR, $route);
        if ($path === $route && $_SERVER["REQUEST_METHOD"] === $info['httpMethod']) {
            $controller = new $info['controller']();
            $controller->{$info['method']}();
            return true;
        }
    }

    return false;

}
