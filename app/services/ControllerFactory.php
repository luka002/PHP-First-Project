<?php

/**
 * Class responsible for creating a controller.
 */
class ControllerFactory {

    /**
     * Chooses which controller to create based on the parameter
     * $parameter and when controller is created it is returned.
     *
     * @param DBRepository $dbRepository Allows communication with database.
     * @param Templating $templating Fills provided template file with provided variables.
     * @param Request $request Stores HTTP request information.
     * @param string $parameter Determines which controller will be created.
     * @param User|null $user Holds data about logged in user or null if user
     *                        is not logged in.
     * @return Controller Created controller.
     */
    public function getController(DBRepository $dbRepository, Templating $templating,
                                  Request $request, string $parameter,
                                  User $user = null): Controller {
        if (empty($parameter)) {
            return new IndexController($templating, $dbRepository, $user);
        }

        switch ($parameter) {
            case 'addition' :
                return new AdditionController($templating, $user);

            case 'admin' :
                return new AdminController($templating, $dbRepository, $user,
                    new RegistrationForm($request->getPost(), $dbRepository)
                );

            case 'changePassword' :
                return new ChangePasswordController($templating, $dbRepository, $user,
                    new ChangePasswordForm($request->getPost())
                );

            case 'counting' :
                return new CountingController($templating, $user);

            case 'editProfile' :
                return new EditProfileController($templating, $user,
                    new EditProfileForm($request->getPost(), $dbRepository)
                );

            case 'forgottenPassword' :
                return new ForgottenPasswordController($templating, $dbRepository, $user);

            case 'login' :
                return new LoginController($templating, $dbRepository, $user,
                    new LoginForm($request->getPost(), $dbRepository)
                );

            case 'logout' :
                return new LogoutController();

            case 'normalize' :
                return new NormalizeController($templating, $dbRepository, $user);

            case 'register' :
                return new RegisterController($templating, $dbRepository, $user,
                    new RegistrationForm($request->getPost(), $dbRepository)
                );

            case 'replacement' :
                return new ReplacementController($templating, $user);

            case 'user' :
                return new UserController($templating, $dbRepository, $user);

            default :
                return new ErrorController($templating, $user);
        }
    }

}