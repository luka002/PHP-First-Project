<?php

/**
 * Concrete implementation in strategy design pattern.
 * Represents Controller that enables user to set new password
 * if he has forgotten it.
 */
class ChangePasswordController implements Controller {

    /**
     * @var Templating Fills provided template file with provided variables.
     */
    private $template;
    /**
     * @var DBRepository Allows communication with database.
     */
    private $dbRepository;
    /**
     * @var User Holds data about logged in user or null if user not logged in.
     */
    private $user;
    /**
     * @var ChangePasswordForm Holds information that user inputted in the form.
     */
    private $form;

    /**
     * ChangePasswordController constructor.
     * @param Templating $template Fills provided template file with provided variables.
     * @param DBRepository $dbRepository Allows communication with database.
     * @param ChangePasswordForm $form Holds information that user inputted in the form.
     * @param User|null $user Holds data about logged in user or null if user
     *                        is not logged in.
     */
    public function __construct(Templating $template, DBRepository $dbRepository,
                                User $user = null, ChangePasswordForm $form) {
        $this->template = $template;
        $this->dbRepository = $dbRepository;
        $this->user = $user;
        $this->form = $form;
    }

    /**
     * If "GET" method is used and link for setting new password
     * is valid, user will be provided with form to set new password
     * and if "POST" method is used new password will be set if it's valid.
     *
     * @param Request $request Stores HTTP request information.
     * @return Response Object that sends HTTP response.
     */
    public function handle(Request $request): Response {
        if (null !== $this->user) {
            return new RedirectResponse('index.php');
        }

        if ('GET' === $request->getMethod()) {
            return $this->doGet($request);
        }

        if ('POST' === $request->getMethod()) {
            return $this->doPost();
        }
    }

    /**
     * Checks if link is valid and if it is, user
     * is provided with form to set new password.
     *
     * @param Request $request Stores HTTP request information.
     * @return HTMLResponse Object containing content which will be sent to user.
     */
    private function doGet(Request $request): HTMLResponse {
        $email = $request->getGet()['email'] ?? '';
        $token = $request->getGet()['token'] ?? '';

        if ($this->dbRepository->linkIsValid($email, $token)) {
            return $this->htmlResponse(
                $email,
                'Enter new password:',
                true
            );
        }

        return $this->htmlResponse(
            $email,
            'Link not available.',
            false
        );
    }

    /**
     * Checks if user input is valid. If input is valid, new password
     * is set and link for changing password is disabled.
     *
     * @return HTMLResponse Object containing content which will be sent to user.
     */
    private function doPost(): HTMLResponse {
        $this->form->validate();

        if ($this->form->hasErrors()) {
            return $this->htmlResponse(
                $this->form->getEmail(),
                'Enter new password:',
                true
            );
        }

        $this->dbRepository->updatePasswordByEmail(
            $this->form->getEmail(),
            password_hash($this->form->getPasswordNew(), PASSWORD_ARGON2I)
        );

        $this->dbRepository->disableLink($this->form->getEmail());

        return $this->htmlResponse(
            $this->form->getEmail(),
            'Password successfully updated.',
            false
        );
    }

    /**
     * Creates object that holds content which will
     * be sent to user as HTML response.
     *
     * @param string $email User email.
     * @param string $message Message for user.
     * @param bool $showForm If true form will be shown, if false it won't.
     * @return HTMLResponse Object containing content which will be sent to user.
     */
    private function htmlResponse(string $email, string $message, bool $showForm): HTMLResponse {
        return new HTMLResponse($this->template->render(
            'main.php', 
            [
                'title' => 'Change Password',
                'body' => $this->template->render(
                    'changePasswordTemp.php',
                    [
                        'form' => $this->form,
                        'message' => $message,
                        'showForm' => $showForm,
                        'email' => $email
                    ]),
                'loggedIn' => false
            ]
        ));
    }

}