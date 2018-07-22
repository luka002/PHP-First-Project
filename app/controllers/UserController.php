<?php

/**
 * Concrete implementation in strategy design pattern.
 * Represents Controller that enables user to change his
 * current status. If user is "regular" he can become "premium"
 * and if user is "premium" he can become "admin".<br><br>
 *
 * Note*:<br>
 * User can change his status just by clicking. It is made this way
 * for a simpler demonstration.
 */
class UserController implements Controller {

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
     * UserController constructor.
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
     * Updates user status is users has selected so,
     * otherwise gives user option to do so.
     *
     * @param Request $request Stores HTTP request information.
     * @return Response Object that sends HTTP response.
     */
    public function handle(Request $request): Response {
        if (null === $this->user) {
            return new RedirectResponse('index.php?controller=login');
        }

        if ('POST' === $request->getMethod()) {
            if (isset($request->getPost()['admin'])) {
                $this->dbRepository->updateAdmin($this->user->getId(), true);
                return $this->htmlResponse(true, true, 'admin',
                    'You have successfully become an administrator!'
                );
            }

            if (isset($request->getPost()['premium'])) {
                $this->dbRepository->updatePremium($this->user->getId(), true);
                return $this->htmlResponse( false, true, 'premium',
                    'You have successfully become a premium user!'
                );
            }
        }

        return $this->htmlResponse(
            $this->user->getAdmin(),
            $this->user->getPremium(),
            $this->user->getType()
        );
    }

    /**
     * Creates object that stores HTML file which will be
     * sent to user as a response.
     *
     * @param bool $admin True is user is admin, else false.
     * @param bool $premium True is user is premium, else false.
     * @param string $type User type, "premium", "regular" and "admin" possible.
     * @param string $message Message for user.
     * @return HTMLResponse Object containing HTML file which will be sent to user.
     */
    private function htmlResponse(bool $admin, bool $premium, string $type,
                                  string $message = ''): HTMLResponse {
        return new HTMLResponse($this->template->render(
            'main.php',
            [
                'title' => 'User',
                'body' => $this->template->render(
                    'userTemp.php',
                    [
                        'message' => $message,
                        'id' => $this->user->getId(),
                        'premium' => $premium,
                        'admin' => $admin
                    ]),
                'loggedIn' => true,
                'userName' => $this->user->getName(),
                'type' => $type
            ]
        ));
    }
}