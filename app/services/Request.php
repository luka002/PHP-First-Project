<?php

/**
 * Stores information from user's HTTP request.
 */
class Request {

    /**
     * @var string Method used for sending HTTP request.
     */
    private $method;
    /**
     * @var array Contains user provided parameters when GET method has been used.
     */
    private $get;
    /**
     * @var array Contains user provided parameters when POST method has been used.
     */
    private $post;
    /**
     * @var array Contains data about uploaded files.
     */
    private $files;
    /**
     * @var array Contains data about user session.
     */
    private $session;

    /**
     * Request constructor.
     * @param string $method Method used for sending HTTP request.
     * @param array $get Contains user provided parameters when GET method has been used.
     * @param array $post Contains user provided parameters when POST method has been used.
     * @param array $files Contains data about uploaded files.
     * @param array $session Contains data about user session.
     */
    public function __construct(string $method, array $get, array $post,
                                array $files, array &$session) {
        $this->method = $method;
        $this->get = $get;
        $this->post = $post;
        $this->files = $files;
        $this->session = &$session;
    }

    /**
     * @return string Method used for sending HTTP request.
     */
    public function getMethod(): string {
        return $this->method;
    }

    /**
     * @return array User provided parameters when GET method has been used.
     */
    public function getGet(): array {
        return $this->get;
    }

    /**
     * @return array User provided parameters when POST method has been used.
     */
    public function getPost(): array {
        return $this->post;
    }

    /**
     * @return array Data about uploaded files.
     */
    public function getFiles(): array {
        return $this->files;
    }

    /**
     * @return array User session.
     */
    public function &getSession(): array {
        return $this->session;
    }

}