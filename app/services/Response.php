<?php

/**
 * Interface that represents HTTP response
 * that will be sent back to the user.
 */
interface Response {

    /**
     * Sends HTTP response back to the user.
     */
    public function send(): void;

}