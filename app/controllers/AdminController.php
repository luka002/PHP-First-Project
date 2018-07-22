<?php

/**
 * Concrete implementation in strategy design pattern.
 * Represents Controller responsible for processing actions made
 * by the administrator. All of the users and all of their normalizations
 * can be edited by the administrator. Action that are available
 * to the administrator are: updating user's "premium" status,
 * updating user's "admin" status, deleting a user, deleting
 * user's normalizations and adding a new user.
 */
class AdminController implements Controller {

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
     * AdminController constructor.
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
     * Executes action that administrator selected.
     * Action that are available to the administrator are:
     * updating user's "premium" status, updating user's
     * "admin" status, deleting a user, deleting user's
     * normalizations and adding a new user.
     *
     * @param Request $request Stores HTTP request information.
     * @return Response Object that sends HTTP response.
     */
    public function handle(Request $request): Response {
        if (null === $this->user || !$this->user->getAdmin()) {
            return new RedirectResponse('index.php?controller=login');
        }

        if ('POST' === $request->getMethod()) {
            $userId = $request->getPost()['id'] ?? '';

            if (isset($request->getPost()['premium'])) {
                $this->updatePremium($request, $userId);

            } else if (isset($request->getPost()['admin'])) {
                $this->updateAdmin($request, $userId);
                if ($userId == $this->user->getId()) {
                    return new RedirectResponse('index.php?controller=user');
                }

            } else if (isset($request->getPost()['delete'])) {
                $this->dbRepository->removeUser($userId);
                if ($userId == $this->user->getId()) {
                    return new RedirectResponse('index.php?controller=logout');
                }

            } else if (isset($request->getPost()['edit'])) {
                return $this->editUserNorms($userId);

            } else if (isset($request->getPost()['removeNorm'])) {
                return $this->removeUserNorm($request, $userId);

            } else if (isset($request->getPost()['addUser'])) {
                return $this->addUser();
            }
        }

        $users = $this->dbRepository->getAllUsers();
        return $this->htmlResponse($users, null, true, false,null);
    }

    /**
     * Updates user's "premium" status.
     *
     * @param Request $request Stores HTTP request information.
     * @param int $userId ID from user whose status will be updated.
     */
    private function updatePremium(Request $request, int $userId): void {
        $premium = 'NO' === $request->getPost()['premium'] ? true : false;
        $this->dbRepository->updatePremium($userId, $premium);
    }

    /**
     * Updates user's "admin" status.
     *
     * @param Request $request Stores HTTP request information.
     * @param int $userId ID from user whose status will be updated.
     */
    private function updateAdmin(Request $request, int $userId): void {
        $admin = 'NO' === $request->getPost()['admin'] ? true : false;
        $this->dbRepository->updateAdmin($userId, $admin);
    }

    /**
     * Fetches user and all of his norms.
     *
     * @param int $userId User ID.
     * @return HTMLResponse Object containing content which will be sent to user.
     */
    private function editUserNorms(int $userId): HTMLResponse {
        $norms = $this->dbRepository->getUserNorms($userId);
        $user = $this->dbRepository->getUserById($userId);
        return $this->htmlResponse(null, $norms, false, false, $user);
    }

    /**
     * Deletes selected norm from selected user.
     *
     * @param Request $request Stores HTTP request information.
     * @param int $userId ID from user whose norm will be deleted.
     * @return HTMLResponse Object containing content which will be sent to user.
     */
    private function removeUserNorm(Request $request, int $userId): HTMLResponse {
        $normId = $request->getPost()['removeNorm'];
        $this->dbRepository->deleteNorm($normId, $userId);

        $norms = $this->dbRepository->getUserNorms($userId);
        $user = $this->dbRepository->getUserById($userId);

        return $this->htmlResponse(null, $norms, false, false, $user);
    }

    /**
     * Adds user to data base if the form is valid.
     *
     * @return HTMLResponse Object containing content which will be sent to user.
     */
    private function addUser(): HTMLResponse {
        $this->form->validate();

        if ($this->form->hasErrors()) {
            $users = $this->dbRepository->getAllUsers();
            return $this->htmlResponse($users, null, true, false,null);
        }

        $this->form->saveUser();
        $users = $this->dbRepository->getAllUsers();
        return $this->htmlResponse($users, null, true, true,null);
    }

    /**
     * Creates object that holds content which will
     * be sent to user as HTML response.
     *
     * @param array|null $users All users in the data base.
     * @param array|null $norms Norms from the selected user.
     * @param bool $showUsers If true all of the users will be displayed,
     *                        if false all of the selected user normalizations
     *                        will be displayed.
     * @param bool $userAdded True is user added, otherwise false.
     * @param null $user Selected user.
     * @return HTMLResponse Object containing content which will be sent to user.
     */
    private function htmlResponse(array $users = null, array $norms = null, bool $showUsers,
                                  bool $userAdded, $user = null): HTMLResponse {
        return new HTMLResponse($this->template->render(
            'main.php',
            [
                'title' => 'Administrator',
                'body' => $this->template->render(
                    'adminTemp.php',
                    [
                        'userAdded' => $userAdded,
                        'users' => $users,
                        'norms' => $norms,
                        'showUsers' => $showUsers,
                        'user' => $user,
                        'form' => $this->form
                    ]),
                'loggedIn' => true,
                'userName' => $this->user->getName(),
                'type' => $this->user->getType()
            ]
        ));
    }

}