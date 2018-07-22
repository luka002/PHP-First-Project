<?php

/**
 * Initializes phone normalization with patterns that will transform
 * text.<br><br>
 *
 * Examples:<br>
 * "Abcdefg" -> normalizes to -> "A/bcd-efg"<br>
 * "Abcdefgh" -> normalizes to -> "A/bcde-fgh"<br>
 * "A/bcdefg" -> normalizes to -> "A/bcd-efg"<br>
 * "A/bcdefgh" -> normalizes to -> "A/bcde-fgh"<br>
 * "A.bcde.fgh" -> normalizes to -> "A/bcde-fgh"<br>
 * "A.bcd.efgh" -> normalizes to -> "A/bcde-fgh"<br>
 * "A.bcd.efg" -> normalizes to -> "A/bcd-efg"<br>
 * "A-bc-de-fg" -> normalizes to -> "A/bcd-efg"<br>
 * "A-bcd-ef-gh" -> normalizes to -> "A/bcde-fgh"<br>
 * "A/bc-de-fg" -> normalizes to -> "A/bcd-efg"<br>
 * "A/bcd-ef-gh" -> normalizes to -> "A/bcde-fgh"<br><br>
 */
class TransformPhone extends Transform {

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
        $normType = 'phone';

        array_push($patterns, '#(01|\d{3})[\/]?(\d{3,4})(\d{3})#');
        array_push($patterns, '#(01|\d{3})[.](\d{4})[.](\d{3})#');
        array_push($patterns, '#(01|\d{3})[.](\d{3})[.](\d)(\d{3})#');
        array_push($patterns, '#(01|\d{3})[.](\d{3})[.](\d{3})#');
        array_push($patterns, '#(01|\d{3})[-/](\d{2,3})[-](\d)(\d)[-](\d\d)#');

        array_push($replacements, '\1/\2-\3');
        array_push($replacements, '\1/\2-\3');
        array_push($replacements, '\1/\2\3-\4');
        array_push($replacements, '\1/\2-\3');
        array_push($replacements, '\1/\2\3-\4\5');
    }

}