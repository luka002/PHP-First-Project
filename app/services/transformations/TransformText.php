<?php

/**
 * Initializes text normalization with patterns that will transform
 * text.<br><br>
 *
 * Examples:<br>
 * "My&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;car." -> normalizes to -> "My car."<br>
 * "Red car.Blue car." -> normalizes to -> "Red car. Blue car."<br><br>
 */
class TransformText extends Transform {

    /**
     * TransformText constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    public function initTransform(&$patterns, &$replacements, &$normType): void {
        $normType = 'text';

        array_push($patterns, '/\s\s+/');
        array_push($patterns, '/\s+(\r\n|\r|\n)/');
        array_push($patterns, '/([,?.!])([\p{L}])/u');

        array_push($replacements, ' ');
        array_push($replacements, '');
        array_push($replacements, '\1 \2');
    }

}