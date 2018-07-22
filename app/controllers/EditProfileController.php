<?php

/**
 * Concrete implementation in strategy design pattern.
 * Represents Controller which enables user to change his
 * current password.
 */
class EditProfileController implements Controller {

    /**
     * @var Templating Creates HTML file that will be sent back to user.
     */
    private $template;
    /**
     * @var EditProfileForm Holds information that user inputted in the form.
     */
    private $form;
    /**
     * @var User Holds data about current user or null if user not logged in.
     */
    private $user;

    /**
     * EditProfileController constructor.
     * @param Templating $template Creates HTML file that will be sent back to user.
     * @param EditProfileForm $form Holds information that user inputted in the form.
     * @param User|null $user Holds data about current user or null if user not logged in.
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
     * Creates object that stores HTML file which will be
     * sent to user as a response.
     *
     * @param bool $success True if password has been successfully updated,
     *                      otherwise false.
     * @param bool $showForm True if form for password update has to be shown,
     *                       otherwise false.
     * @return HTMLResponse Object containing HTML file which will be sent to user.
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