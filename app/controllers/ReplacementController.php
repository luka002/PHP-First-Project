<?php

/**
 * Concrete implementation in strategy design pattern.
 * Represents Controller that is responsible transforming
 * a file that user has provided. Text in that file can be
 * transformed into bold text, italic text and underlined text.
 */
class ReplacementController implements Controller {

    /**
     * @var Templating Creates HTML file that will be sent back to user.
     */
    private $template;
    /**
     * @var User Holds data about current user or null if user not logged in.
     */
    private $user;

    /**
     * ReplacementController constructor.
     * @param Templating $template Creates HTML file that will be sent back to user.
     * @param User|null $user Holds data about current user or null if user not logged in.
     */
    public function __construct(Templating $template, User $user = null) {
        $this->template = $template;
        $this->user = $user;
    }

    /**
     * Transforms the file that user has uploaded and sends him back
     * transformed file as HTML file with extension .html. Transformation
     * works in such a way that it transforms text that is between
     * special tags. There are three special tags that can be used:
     * <ol><li>"#" - Everything between this tag will become bold</li>
     * <li>"*" - Everything between this tag will become italic</li>
     * <li>"'" - Everything between this tag will become underlined</li></ol><br><br>
     *
     * Examples:<br>
     * \#car\# -> transforms to -><strong>car</strong><br>
     * \*car* -> transforms to -><em>car</em><br>
     * 'car' -> transforms to -><u>car</u><br>
     * \*car\#car\*car\# -> transforms to -><em>car<strong>car</em>car</strong><br>
     * \#car\#car\#car\# -> transforms to -><strong>car</strong>car<strong>car</strong><br>
     * \*car\#car\#car\* -> transforms to -><em>car<strong>car</strong>car</em><br>
     * \*\#'car'\#\* -> transforms to -><em><strong><u>car</u></strong></em><br><br>
     *
     * \*Note:<br>
     * <ol><li>Opening tag has to be closed, tags that are not closed will result in an error</li>
     * <li>Uploaded file can not be larger than 1024 bytes</li></ol>
     *
     * @param Request $request Stores HTTP request information.
     * @return Response Object that sends HTTP response.
     */
    public function handle(Request $request): Response {
        if (null === $this->user) {
            return new RedirectResponse('index.php?controller=login');
        }

        if ('POST' === $request->getMethod()) {
            return $this->doPost($request);
        }

        return $this->htmlResponse();
    }

    /**
     * Checks if file uploaded is valid and if its
     * size is smaller that 1024 bytes. If everything
     * is valid, transformation is applied and transformed
     * file is sent back to user as attachment.
     *
     * @param Request $request Stores HTTP request information.
     * @return Response Object that sends HTTP response.
     */
    private function doPost(Request $request): Response {
        if (!isset($request->getFiles()['entry'])) {
            return $this->htmlResponse('Error, file not chosen.');
        }

        if (UPLOAD_ERR_OK != $request->getFiles()['entry']['error']) {
            return $this->htmlResponse('An error has occurred, please try again.');
        }

        if (1024 < $request->getFiles()['entry']['size']) {
            return $this->htmlResponse('File to large, max size allowed is 1024 bytes.');
        }

        $entry = $request->getFiles()['entry'];
        $content = file_get_contents($entry['tmp_name']);
        try {
            $content = transform($content);
        } catch (InvalidArgumentException $e) {
            return $this->htmlResponse($e->getMessage());
        }

        return new AttachmentResponse($content, 'transformed.html');
    }

    /**
     * Creates object that stores HTML file which will be
     * sent to user as a response.
     *
     * @param string $message Error message for user.
     * @return HTMLResponse Object containing HTML file which will be sent to user.
     */
    private function htmlResponse(string $message = ''): HTMLResponse {
        return new HTMLResponse($this->template->render(
            'main.php',
            [
                'title' => 'Replacement',
                'body' => $this->template->render(
                    'replacementTemp.php',
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