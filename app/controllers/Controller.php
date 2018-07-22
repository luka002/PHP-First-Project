<?php

/**
 * Interface that represents strategy design pattern.
 * It is responsible for processing an incoming request.
 */
interface Controller {

    /**
     * Processes HTTP request and returns the HTTP response.
     *
     * @param Request $request Stores HTTP request information.
     * @return Response Object that sends HTTP response.
     */
    public function handle(Request $request): Response;

 }