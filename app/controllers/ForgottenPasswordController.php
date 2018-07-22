<?php

/**
 * Concrete implementation in strategy design pattern.
 * Represents Controller responsible for sending to user
 * a link for setting up a new password if he has
 * forgotten his current password.
 */
class ForgottenPasswordController implements Controller {

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
     * ForgottenPasswordController constructor.
     * @param Templating $template Fills provided template file with provided variables.
     * @param DBRepository $dbRepository Allows communication with database.
     * @param User|null $user Holds data about logged in user or null if user
     *                        is not logged in.
     */
    public function __construct(Templating $template, DBRepository $dbRepository,
                                User $user = null) {
        $this->template = $template;
        $this->dbRepository = $dbRepository;
        $this->user = $user;
    }

    /**
     * Fetches "email" parameter that user entered and
     * if user with email equal to that parameter exists,
     * link for setting up a new password will be sent to
     * that email.
     *
     * Note*: Link will not be sent to user email but instead it
     * will be outputted in the browser for purpose of demonstration.
     * In the real world it would have been sent to user email.
     *
     * @param Request $request Stores HTTP request information.
     * @return Response Object that sends HTTP response.
     */
    public function handle(Request $request): Response {
        if (null != $this->user) {
            return new RedirectResponse('index.php?controller=login');
        }

        $email = $request->getPost()['email'] ?? '';

        if ('POST' === $request->getMethod()) {
            if (!$this->dbRepository->userWithEmailExists($email)) {
                return $this->htmlResponse($email,false, null,
                    'User with given email does not exist.'
                );
            }

            try {
                $token = bin2hex(random_bytes(40));
            } catch (Exception $e) {
                return new RedirectResponse('index.php');
            }

            $link = 'index.php?controller=changePassword&email='.$email.'&token='.$token;
            $this->dbRepository->saveParametersForPasswordUpdateLink($email, $token);

            return $this->htmlResponse($email,true, $link,
                'Link for password update has been sent to your email.'
            );
        } 

        return $this->htmlResponse($email,false,null,
            'Enter email to which a link will be sent to.'
        );
    }

    /**
     * Creates object that holds content which will
     * be sent to user as HTML response.
     *
     * @param string $email Email that user entered.
     * @param bool $success True if link has been sent, else false.
     * @param string|null $link Link for setting up a new password.
     * @param string $message Message for user.
     * @return HTMLResponse Object containing content which will be sent to user.
     */
    private function htmlResponse(string $email, bool $success, string $link = null,
                                  string $message): HTMLResponse {
        return new HTMLResponse($this->template->render(
            'main.php', 
            [
                'title' => 'Forgotten Password',
                'body' => $this->template->render(
                    'forgottenPasswordTemp.php',
                    [
                        'message' => $message,
                        'success' => $success,
                        'email' => $email,
                        'link' => $link
                    ]),
                'loggedIn' => false
            ]
        ));
    }

}