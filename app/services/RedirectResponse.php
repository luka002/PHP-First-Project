<?php

/**
 * Redirects user to specified location.
 */
class RedirectResponse implements Response {

    /**
     * @var string Location where user will be redirected.
     */
    private $location;

    /**
     * RedirectResponse constructor.
     * @param string $location Location where user will be redirected.
     */
    public function __construct(string $location) {
        $this->location = $location;
    }

    /**
     * Redirects user to specified location.
     */
    public function send(): void {
        header("Location: " . $this->location);
    }

}