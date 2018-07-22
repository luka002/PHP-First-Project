<?php

/**
 * Concrete implementation in strategy design pattern.
 * Represents Controller that creates home page.
 */
class IndexController implements Controller {

    /**
     * @var Templating Creates HTML file that will be sent back to user.
     */
    private $template;
    /**
     * @var DBRepository Allows communication with data base.
     */
    private $dbRepository;
    /**
     * @var User Holds data about current user or null if user not logged in.
     */
    private $user;

    /**
     * IndexController constructor.
     * @param Templating $template Creates HTML file that will be sent back to user.
     * @param DBRepository $dbRepository Allows communication with data base.
     * @param User|null $user Holds data about current user or null if user not logged in.
     */
    public function __construct(Templating $template, DBRepository $dbRepository,
                                User $user = null) {
        $this->template = $template;
        $this->dbRepository = $dbRepository;
        $this->user = $user;
    }

    /**
     * Creates home page.
     *
     * @param Request $request Stores HTTP request information.
     * @return Response Object that sends HTTP response.
     */
    public function handle(Request $request): Response {
        if (null === $this->user) {
            return $this->htmlResponse(false);
        }

        return $this->htmlResponse(true);
    }

    /**
     * Creates object that stores HTML file which will be
     * sent to user as a response.
     *
     * @param bool $loggedIn True is user is logged in, else false.
     * @return HTMLResponse Object containing HTML file which will be sent to user.
     */
    private function htmlResponse(bool $loggedIn): HTMLResponse {
        return new HTMLResponse($this->template->render(
            'main.php', 
            [
                'title' => 'Index', 
                'body' => $this->template->render(
                    'indexTemp.php', []),
                'loggedIn' => $loggedIn,
                'userName' => null !== $this->user ? $this->user->getName() : '',
                'type' => null !== $this->user ? $this->user->getType() : ''
            ]
        ));
    }

}