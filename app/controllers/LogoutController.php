<?php

/**
 * Concrete implementation in strategy design pattern.
 * Represents Controller that handles log out process.
 */
class LogoutController implements Controller {

    /**
     * Handles log out process by destroying user's session.
     *
     * @param Request $request Stores HTTP request information.
     * @return Response Object that sends HTTP response.
     */
    public function handle(Request $request): Response {
        session_destroy();
        return new RedirectResponse('index.php');
    }

}