<?php
declare(strict_types = 1);
mb_internal_encoding('UTF-8');
session_start();

require_once "../app/functions.php";

spl_autoload_register( 'autoload' );

$dbRepository = new DBRepository();
$templating = new Templating('../app/templates/');
$request = new Request(
    $_SERVER['REQUEST_METHOD'], $_GET, $_POST, $_FILES, $_SESSION
);
$param = 'GET' === $request->getMethod() ? $request->getGet()['controller'] ?? ''
                                         : $request->getPost()['controller'] ?? '';
$user = null;
if (isset($request->getSession()['name'])) {
    $user = $dbRepository->findUserByName($request->getSession()['name']);
}

$controller = (new ControllerFactory())->getController(
    $dbRepository,
    $templating,
    $request,
    $param,
    $user
);

$response = $controller->handle($request);
$response->send();