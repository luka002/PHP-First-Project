<?php

/**
 * Processes non existing page requests.
 */
class ErrorController implements Controller {

    /**
     * @var Templating Fills provided template file with provided variables.
     */
    private $template;
    /**
     * @var User Holds data about logged in user or null if user not logged in.
     */
    private $user;

    /**
     * ErrorController constructor.
     * @param Templating $template Fills provided template file with provided variables.
     * * @param User|null $user Holds data about logged in user or null if user
     *                        is not logged in.
     */
    public function __construct(Templating $template, User $user = null) {
        $this->template = $template;
        $this->user = $user;
    }

    /**
     * Sets response code to 404 and sends user a message.
     *
     * @param Request $request Stores HTTP request information.
     * @return Response Object that sends HTTP response.
     */
    public function handle(Request $request): Response {
        if (null === $this->user) {
            return new RedirectResponse('index.php?controller=login');
        }

        header("HTTP/1.0 404 Not Found");
        return $this->htmlResponse('Error code ' . http_response_code());
    }

    /**
     * Creates object that holds content which will
     * be sent to user as HTML response.
     *
     * @param string $message
     * @return HTMLResponse Object containing content which will be sent to user.
     */
    private function htmlResponse(string $message): HTMLResponse {
        return new HTMLResponse($this->template->render(
            'main.php', 
            [
                'title' => 'Error', 
                'body' => $this->template->render(
                    'errorTemp.php',
                    [
                        'message' => $message
                    ]),
                'loggedIn' => true,
                'userName' => $this->user->getName(),
                'type' => $this->user->getType()
            ]
        ));
    }

}