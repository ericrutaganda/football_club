<?php
declare(strict_types=1);
require_once __DIR__.'/config/config.php';
require_once __DIR__.'/helpers/functions.php';
require_once __DIR__.'/app/core/Database.php';
require_once __DIR__.'/app/core/Controller.php';
foreach (glob(__DIR__.'/app/models/*.php') as $file) require_once $file;
foreach (glob(__DIR__.'/app/controllers/*.php') as $file) require_once $file;

$route=$_GET['route']??(isset($_SESSION['user'])?'dashboard':'login');
$routes=[
 'login'=>[AuthController::class,'login'],'logout'=>[AuthController::class,'logout'],
 'account/password'=>[AuthController::class,'changePassword'],
 'dashboard'=>[DashboardController::class,'index'],
 'players'=>[PlayerController::class,'index'],'players/form'=>[PlayerController::class,'form'],'players/save'=>[PlayerController::class,'save'],'players/delete'=>[PlayerController::class,'delete'],
 'matches'=>[MatchController::class,'index'],'matches/form'=>[MatchController::class,'form'],'matches/save'=>[MatchController::class,'save'],'matches/delete'=>[MatchController::class,'delete'],
 'trainings'=>[TrainingController::class,'index'],'trainings/form'=>[TrainingController::class,'form'],'trainings/save'=>[TrainingController::class,'save'],'trainings/delete'=>[TrainingController::class,'delete'],
 'api/summary'=>[ApiController::class,'summary']
];
if(!is_string($route)||!isset($routes[$route])){http_response_code(404);exit('Page not found.');}
try {
    [$class,$method]=$routes[$route];
    (new $class())->$method();
} catch (Throwable $e) {
    error_log('Application error: ' . $e->getMessage());
    http_response_code(500);
    echo APP_DEBUG
        ? 'Application error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')
        : 'An unexpected error occurred. Please try again later.';
}
