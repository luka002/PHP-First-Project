<?php
// todo
class ErrorController implements Controller {

    private $template;

    public function __construct(Templating $template) {
        $this->template = $template;
    }

    public function handle(Request $request): Response {
        http_response_code(404);
        return $this->htmlResponse('Error code ' . http_response_code());
    }

    private function htmlResponse(string $error): HTMLResponse {
        return new HTMLResponse($this->template->render(
            'main.php', 
            [
                'title' => 'Error', 
                'body' => $this->template->render(
                            'errorTemp.php',
                            [
                                'error' => $error
                            ])
            ]
        ));
    }

}