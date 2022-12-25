<?php

require_once '../vendor/autoload.php';
require_once '../framework/autoload.php';
require_once "../controllers/ContentController.php";
require_once "../controllers/Controller404.php";
require_once "../controllers/RowDeleteController.php";
require_once "../controllers/MainController.php";
require_once "../controllers/UserController.php";
require_once "../middleware/LoginRequiredMiddleWare.php";
require_once "../controllers/LoginController.php";
require_once "../controllers/LogoutController.php";
require_once "../controllers/CarEditController.php";
require_once "../controllers/DriverEditController.php";
require_once "../controllers/AddCarDriverController.php";
require_once "../controllers/AddOffenseController.php";

$loader = new \Twig\Loader\FilesystemLoader('../views');
$twig = new \Twig\Environment($loader, [
    "debug" => true
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());
$pdo = new PDO("sqlsrv:Server=192.168.56.1,1433;Database=гаи", "alex", "12345", []);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

session_start();
$router = new Router($twig, $pdo);
$router->add("/", MainController::class)
    ->middleware(new LoginRequiredMiddleWare());
$router->add("/login", LoginController::class);
$router->add("/user", UserController::class);
$router->add("/logout", LogoutController::class);
$router->add("/table", ContentController::class);
$router->add("/delete", RowDeleteController::class);
$router->add("/driver-edit", DriverEditController::class);
$router->add("/car-edit", CarEditController::class);
$router->add("/add", AddCarDriverController::class);
$router->add("/add-offense", AddOffenseController::class);
$router->get_or_default(Controller404::class);