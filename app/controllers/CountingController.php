<?php

/**
 * Concrete implementation in strategy design pattern.
 * Represents Controller that is responsible for extracting
 * user input from the input fields named "text", "stop" and
 * "search" and calculating how many times have characters from
 * "search" field appeared in "text" field before character
 * in "stop" field appears.
 */
class CountingController implements Controller {

    /**
     * @var Templating Creates HTML file that will be sent back to user.
     */
    private $template;
    /**
     * @var User Holds data about current user or null if user not logged in.
     */
    private $user;

    /**
     * CountingController constructor.
     * @param Templating $template Creates HTML file that will be sent back to user.
     * @param User|null $user Holds data about current user or null if user not logged in.
     */
    public function __construct(Templating $template, User $user = null) {
        $this->template = $template;
        $this->user = $user;
    }

    /**
     * Extracts user input from the input fields named "text", "stop" and
     * "search" and calculates how many times have characters from
     * "search" field appeared in "text" field before character
     * in "stop" field appears.
     * Definition of each field:
     *      - "text" - accepts any sequence of characters
     *      - "stop" - marks the end of searching
     *      - "search" - defines the characters whose occurrences will be
     *                   counted before the stop character appears (characters
     *                   have to be separated by comma without any spaces).
     *
     * Examples:<br><br>
     *
     * <strong>"text" = "abcdefhij", "stop" = "c", "search" = "a,h" => Result = 1;</strong><br>
     * <em>Counts cumulative number of occurrences of letters "a" and "h" before letter "c".</em><br><br>
     * <strong>"text" = "žđščćžđš", "stop" = "ć", "search" = "ž,š" => Result = 2;</strong><br>
     * <em>Both letters "ž" and "š" have appeared once before character "ć".</em><br><br>
     * <strong>"text" = "abcdef", "stop" = "a", "search" = "b,c" => Result = 0;</strong><br>
     * <strong>"text" = "abcdef", "stop" = "a", "search" = "b, c" => Result = Error;</strong><br>
     * <em>Error because there is a space between "," and "c".</em><br><br>
     * <strong>"text"  = "!@#$%", "stop" = "$", "search" = "@,!" => Result = 2;</strong><br><br>
     * <strong>"text"  = "abcdefghij", "stop" = "l", "search" = "a,b" => Result = 0;</strong><br>
     * <em>Result is 0 because letter "l" does not exist in the text.</em><br><br>
     *
     * @param Request $request Stores HTTP request information.
     * @return Response Object that sends HTTP response.
     */
    public function handle(Request $request): Response {
        if (null === $this->user) {
            return new RedirectResponse('index.php?controller=login');
        }

        if ('GET' === $request->getMethod() && isset($request->getGet()['submit'])) {
            $entry = $request->getGet()['text'] ?? '';
            $search = $request->getGet()['stop'] ?? '';
            $count = $request->getGet()['search'] ?? '';

            $count = explode(',', $count);

            try {
                $result = repetition($entry, $search, ...$count);
            } catch (InvalidArgumentException $e) {
                return $this->htmlResponse($request, $e->getMessage(), true);
            }

            return $this->htmlResponse($request, 'Result: '.$result, false);
        }

        return $this->htmlResponse($request);
    }

    /**
     * Creates object that stores HTML file which will be
     * sent to user as a response.
     *
     * @param Request $request Stores HTTP request information.
     * @param string $result Final result.
     * @param bool $error True if an error occurred, otherwise false.
     * @return HTMLResponse Object containing HTML file which will be sent to user.
     */
    private function htmlResponse(Request $request, string $result = '',
                                  bool $error = false ): HTMLResponse {
        return new HTMLResponse($this->template->render(
            'main.php', 
            [
                'title' => 'Counting',
                'body' => $this->template->render(
                    'countingTemp.php',
                    [
                        'result' => $result,
                        'error' => $error,
                        'text' => $request->getGet()['text'] ?? '',
                        'stop' => $request->getGet()['stop'] ?? '',
                        'search' => $request->getGet()['search'] ?? ''
                    ]),
                'loggedIn' => true,
                'userName' => $this->user->getName(),
                'type' => $this->user->getType()
            ]
        ));
    }

}