<?php

/**
 * Initializes date normalization with patterns that will transform
 * text of format YYYY-MM-dd to dd.MM.YYYY<br><br>
 *
 * Example:<br>
 * "1994-9-26" -> normalizes to -> "26.09.1994"
 */
class TransformDate extends Transform  {

    /**
     * TransformDate constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    public function initTransform(&$patterns, &$replacements, &$normType): void {
        $normType = 'date';

        array_push($patterns, '#(\d{4})[-](\d{2})[-](\d{2})#');
        array_push($patterns, '#(\d{4})[-](\d{2})[-](\d{1})#');
        array_push($patterns, '#(\d{4})[-](\d{1})[-](\d{2})#');
        array_push($patterns, '#(\d{4})[-](\d{1})[-](\d{1})#');

        array_push($replacements, '\3.\2.\1');
        array_push($replacements, '0\3.\2.\1');
        array_push($replacements, '\3.0\2.\1');
        array_push($replacements, '0\3.0\2.\1');
    }

}