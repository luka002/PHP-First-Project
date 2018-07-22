<?php

/**
 * Abstract class that represents template method design pattern.
 * Class is designed to transform text in transform method. Its
 * subclass will initialize transformation in initTransform method.
 */
abstract class Transform {

    /**
     * Transform constructor.
     */
    public function __construct() {
    }

    /**
     * Initializes transformation. Initializes $patterns array with regular
     * expressions, $replacements array with replacements for when those
     * regular expression are matched and $normType that tells which normalization
     * is taking place.
     *
     * @param array $patterns Array with regular expressions.
     * @param array $replacements Replacement for when regular expressions are matched.
     * @param string $normType Tells which normalization is taking place.
     */
    protected abstract function initTransform(array &$patterns, array &$replacements,
                                              string &$normType): void;

    /**
     * Template method in template method design pattern.
     * Executes transformation. If any regular expression pattern
     * from $patterns array has a match in the $rows array, that
     * part in the $rows array will be replaced with row from
     * $replacements array. $patterns array and $replacements array
     * work in pair. If $patterns array with index 2 finds a match
     * that match will be replaced with index 2 from the $replacements
     * array.
     *
     * @param array $rows Contains text ready for transformation.
     * @param array $result Stores result.
     */
    public final function transform(array &$rows, array &$result): void {
        $patterns = [];
        $replacements = [];
        $normType = '';

        $this->initTransform($patterns, $replacements, $normType);

        for ($i = 0; $i < count($rows); $i++) {
            $count = 0;
            $new = preg_replace($patterns, $replacements, $rows[$i], -1, $count);
            $result[$normType] += $count;
            $result['total'] += $count;
            $rows[$i] = $new;
        }
    }

}