<?php

/**
 * Concrete implementation in strategy design pattern.
 * Represents Controller that is responsible for extracting
 * user input from the input field named "entry" and adding
 * together all of the digits from that field.
 */
class AdditionController implements Controller {

    /**
     * @var Templating Creates HTML file that will be sent back to user.
     */
    private $template;
    /**
     * @var User Holds data about current user or null if user not logged in.
     */
    private $user;

    /**
     * AdditionController constructor.
     * @param Templating $template Creates HTML file that will be sent back to user.
     * @param User|null $user Holds data about current user. If user is not logged
     *                        in than $user will be null.
     */
    public function __construct(Templating $template, User $user = null) {
        $this->template = $template;
        $this->user = $user;
    }

    /**
     * Extracts user input from the input field named "entry" and adds
     * together all of the digits from that field. If all characters from
     * input field are digits, result will be outputted on the screen and
     * if not, error message will be shown.
     * If user is not logged in, he will be redirected to login page.
     *
     * Example: input = "23", result = "5"
     *
     * @param Request $request Stores HTTP request information.
     * @return Response Object that sends HTTP response.
     */
    public function handle(Request $request): Response {
        if (null === $this->user) {
            return new RedirectResponse('index.php?controller=login');
        }

        if ('GET' === $request->getMethod() && isset($request->getGet()['submit'])) {
            $entry = $request->getGet()['entry'] ?? '';

            try {
                $result = add($entry);
            } catch (InvalidArgumentException $e) {
                return $this->htmlResponse($e->getMessage(), $entry);
            }

            return $this->htmlResponse('Result: '.$result);
        }

        return $this->htmlResponse();
    }

    /**
     * Creates object that stores HTML file which will be
     * sent to user as a response.
     *
     * @param string $result Final result.
     * @param string $entry User entry.
     * @return HTMLResponse Object containing HTML file which will be sent to user.
     */
    private function htmlResponse(string $result = '', string $entry = ''): HTMLResponse {
        return new HTMLResponse($this->template->render(
            'main.php', 
            [
                'title' => 'Addition',
                'body' => $this->template->render(
                    'additionTemp.php',
                    [
                        'result' => $result,
                        'entry' => $entry
                    ]),
                'loggedIn' => true,
                'userName' => $this->user->getName(),
                'type' => $this->user->getType()
            ]
        ));
    }

}