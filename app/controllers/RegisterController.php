<?php

/**
 * Concrete implementation in strategy design pattern.
 * Represents Controller that is responsible for process
 * of registration.
 */
class RegisterController implements Controller {

    /**
     * @var Templating Fills provided template file with provided variables.
     */
    private $template;
    /**
     * @var DBRepository Allows communication with database.
     */
    private $dbRepository;
    /**
     * @var RegistrationForm Holds information that user inputted in the form.
     */
    private $form;
    /**
     * @var User Holds data about logged in user or null if user not logged in.
     */
    private $user;

    /**
     * RegisterController constructor.
     * @param Templating $template Fills provided template file with provided variables.
     * @param DBRepository $dbRepository Allows communication with database.
     * @param RegistrationForm $form Holds information that user inputted in the form.
     * @param User|null $user Holds data about logged in user or null if user
     *                        is not logged in.
     */
    public function __construct(Templating $template, DBRepository $dbRepository,
                                RegistrationForm $form, User $user = null) {
        $this->template = $template;
        $this->dbRepository = $dbRepository;
        $this->form = $form;
        $this->user = $user;
    }

    /**
     * Checks if user parameters are valid and if they are,
     * user is saved in the data base and registration is
     * successful. If parameters are not valid user will be
     * provided with message telling him what is invalid.
     *
     * @param Request $request Stores HTTP request information.
     * @return Response Object that sends HTTP response.
     */
    public function handle(Request $request): Response {     
        if (null !== $this->user) {
            return new RedirectResponse('index.php');
        }

        if ('POST' === $request->getMethod()) {
            $this->form->validate();
        
            if (!$this->form->hasErrors()) {
                $this->form->saveUser();
                return $this->htmlResponse(true);
            }
        }
        
        return $this->htmlResponse();
    }

    /**
     * Creates object that holds content which will
     * be sent to user as HTML response.
     *
     * @param bool $justRegistered True if user has successfully registered, else false.
     * @return HTMLResponse Object containing content which will be sent to user.
     */
    private function htmlResponse(bool $justRegistered = false): HTMLResponse {
        return new HTMLResponse($this->template->render(
            'main.php', 
            [
                'title' => 'Register',
                'body' => $this->template->render(
                    'registerTemp.php',
                    [
                        'justRegistered' => $justRegistered,
                        'form' => $this->form
                    ]),
                'loggedIn' => false
            ]
        ));
    }

}