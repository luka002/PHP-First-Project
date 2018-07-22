<?php

/**
 * Sends string to user as HTML response.
 */
class HTMLResponse implements Response {

    /**
     * @var string String that will be sent to user and outputted on his screen.
     */
    private $content;

    /**
     * HTMLResponse constructor.
     * @param string $content String that will be sent to the user.
     */
    public function __construct(string $content) {
        $this->content = $content;
    }

    /**
     * Sends string to user as HTML response.
     */
    public function send(): void {
        echo $this->content;
    }

}