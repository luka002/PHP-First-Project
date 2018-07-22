<?php

/**
 * Concrete implementation in strategy design pattern.
 * Represents Controller which enables user to change his
 * current password.
 */
class EditProfileController implements Controller {

    /**
     * @var Templating Fills provided template file with provided variables.
     */
    private $template;
    /**
     * @var EditProfileForm Holds information that user inputted in the form.
     */
    private $form;
    /**
     * @var User Holds data about logged in user or null if user not logged in.
     */
    private $user;

    /**
     * EditProfileController constructor.
     * @param Templating $template Fills provided template file with provided variables.
     * @param EditProfileForm $form Holds information that user inputted in the form.
     * @param User|null $user Holds data about logged in user or null if user
     *                        is not logged in.
     */
    public function __construct(Templating $template, EditProfileForm $form,
                                User $user = null) {
        $this->template = $template;
        $this->form = $form;
        $this->user = $user;
    }

    /**
     * Updates user password if user has entered his
     * old password correctly and if new password is
     * at least 12 characters long.
     *
     * @param Request $request Stores HTTP request information.
     * @return Response Object that sends HTTP response.
     */
    public function handle(Request $request): Response {
        if (null === $this->user) {
            return new RedirectResponse('index.php?controller=login');
        }

        if ('POST' === $request->getMethod()) {
            $this->form->validate();

            if (!$this->form->hasErrors()) {
                $this->form->updatePassword();
                return $this->htmlResponse(true, false);
            }
        } 

        return $this->htmlResponse(false, true);
    }

    /**
     * Creates object that holds content which will
     * be sent to user as HTML response.
     *
     * @param bool $success True if password has been successfully updated,
     *                      otherwise false.
     * @param bool $showForm True if form for password update has to be shown,
     *                       otherwise false.
     * @return HTMLResponse Object containing content which will be sent to user.
     */
    private function htmlResponse(bool $success, bool $showForm): HTMLResponse {
        return new HTMLResponse($this->template->render(
            'main.php', 
            [
                'title' => 'Edit Profile',
                'body' => $this->template->render(
                    'editProfileTemp.php',
                    [
                        'name' => $this->user->getName(),
                        'success' => $success,
                        'showForm' => $showForm,
                        'form' => $this->form
                    ]),
                'loggedIn' => true,
                'userName' => $this->user->getName(),
                'type' => $this->user->getType()
            ]
        ));
    }

}