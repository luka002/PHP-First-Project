<?php

/**
 * Uses provided template file and inserts
 * provided variables into that template file.
 */
class Templating {

    /**
     * @var string Path to template file.
     */
    private $path;

    /**
     * Templating constructor.
     * @param string $path Path to template file.
     */
    public function __construct(string $path) {
        $this->path = $path;
    }

    /**
     * Extracts variables from $arguments array and inserts them
     * into $file template.
     *
     * @param string $file Template file.
     * @param array $arguments Arguments.
     * @return string Template file filled with arguments as a string.
     */
    public function render(string $file, array $arguments = []): string {
        $file = $this->path . $file;
        
        ob_start();
        extract($arguments);
        include($file);

        return ob_get_clean();
    }

}