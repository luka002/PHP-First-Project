<?php

/**
 * Concrete implementation in strategy design pattern.
 * Represents Controller that handles log in process.
 */
class LoginController implements Controller {

    /**
     * @var Templating Fills provided template file with provided variables.
     */
    private $template;
    /**
     * @var DBRepository Allows communication with database.
     */
    private $dbRepository;
    /**
     * @var LoginForm Holds information that user inputted in the form.
     */
    private $form;
    /**
     * @var User Holds data about logged in user or null if user not logged in.
     */
    private $user;

    /**
     * LoginController constructor.
     * @param Templating $template Fills provided template file with provided variables.
     * @param DBRepository $dbRepository Allows communication with database.
     * @param LoginForm $form Holds information that user inputted in the form.
     * @param User|null $user Holds data about logged in user or null if user
     *                        is not logged in.
     */
    public function __construct(Templating $template, DBRepository $dbRepository,
                                LoginForm $form, User $user = null) {
        $this->template = $template;
        $this->dbRepository = $dbRepository;
        $this->form = $form;
        $this->user = $user;
    }

    /**
     * Handles the process of logging in. If user name
     * and password provided by user are valid, user name
     * is stored as session parameter.
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
                $request->getSession()['name'] = $this->form->getName();
                return new RedirectResponse('index.php');
            }
        }

        return $this->htmlResponse();
    }

    /**
     * Creates object that holds content which will
     * be sent to user as HTML response.
     *
     * @return HTMLResponse Object containing content which will be sent to user.
     */
    private function htmlResponse(): HTMLResponse {
        return new HTMLResponse($this->template->render(
            'main.php', 
            [
                'title' => 'Login',
                'body' => $this->template->render(
                    'loginTemp.php',
                    [
                        'form' => $this->form
                    ]),
                'loggedIn' => false
            ]
        ));    
    }

}