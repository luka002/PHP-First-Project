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

if (!empty($param)) {
    switch ($param) {
        case 'login' :
            $loginForm = new LoginForm($request->getPost(), $dbRepository);
            $controller = new LoginController($templating, $dbRepository,
                                                $loginForm, $user);
            break;

        case 'register' :
            $registerForm = new RegistrationForm($request->getPost(), $dbRepository);
            $controller = new RegisterController($templating, $dbRepository,
                                                $registerForm, $user);
            break;

        case 'replacement' :
            $controller = new ReplacementController($templating, $user);
            break;

        case 'addition' :
            $controller = new AdditionController($templating, $user);
            break;

        case 'counting' :
            $controller = new CountingController($templating, $user);
            break;

        case 'normalize' :
            $controller = new NormalizeController($templating, $dbRepository, $user);
            break;

        case 'logout' :
            $controller = new LogoutController();
            break;

        case 'forgottenPassword' :
            $controller = new ForgottenPasswordController($templating, $dbRepository, $user);
            break;

        case 'changePassword' :
            $changePasswordForm = new ChangePasswordForm($request->getPost());
            $controller = new ChangePasswordController($templating, $dbRepository,
                                                        $changePasswordForm, $user);
            break;

        case 'editProfile' :
            $editProfileForm = new EditProfileForm($request->getPost(), $dbRepository);
            $controller = new EditProfileController($templating, $editProfileForm, $user);
            break;

        case 'admin' :
            $registerForm = new RegistrationForm($request->getPost(), $dbRepository);
            $controller = new AdminController($templating, $dbRepository,
                                                $registerForm, $user);
            break;

        case 'user' :
            $controller = new UserController($templating, $dbRepository, $user);
            break;

        default :
            $controller = new ErrorController($templating, $user);
    }
} else {
    $controller = new IndexController($templating, $dbRepository, $user);
}

$response = $controller->handle($request);
$response->send();