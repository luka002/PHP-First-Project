<?php

/**
 * Escapes specific characters to disable Cross-site Scripting (XSS) attack.
 *
 * @param string|array $entry User input.
 * @return string User input with escaped characters.
 */
function checkEntry($entry): string {
	if (is_array($entry)) return '';
	return htmlentities($entry ?? '', ENT_QUOTES, "UTF-8");
}

/**
 * Calculates how many times have characters from
 * $search string appeared in $entry string before character
 * in $stop string appears. $stop string can not have more than
 * one character.
 *
 * @param string $entry Any sequence of characters.
 * @param string $stop Character that marks the end of searching.
 * @param string[] ...$search Defines the characters whose occurrences will
 *                            be counted before the $stop character appears.
 * @throws InvalidArgumentException If any of parameters is empty, if $stop string
 *                                  is not one character long, if $stop string
 *                                  does not appear in the $entry, if $search array
 *                                  is not filled with one character long strings.
 * @return int How many times have characters from $search string appeared in
 *             $entry string before character in $stop string appears.
 */
function repetition(string $entry, string $stop, string ...$search): int {
    if (empty($entry) || empty($stop) || empty($search)) {
        throw new InvalidArgumentException('Empty fields not allowed.');
    }

    if(1 !== mb_strlen($stop)) {
        throw new InvalidArgumentException('"Stop Character" field expects only one character.');
    }

    $position = mb_strpos($entry, $stop);
    if (false === $position) {
        throw new InvalidArgumentException('Letter "' .$stop . '" does not appear in the entry.');
    }
    $entryBeforeSearch = mb_substr($entry, 0, $position);
    $totalCount = 0;

    foreach ($search as $character) {
        if (1 !== mb_strlen($character)) {
            throw new InvalidArgumentException('In "Search" field characters have to be separated by ",".');
        }

        $totalCount += mb_substr_count($entryBeforeSearch, $character);
    }

    return $totalCount;
}

/**
 * Adds together all of the digits from the $entry.
 *
 * @param string $entry Number in a string format.
 * @throws InvalidArgumentException If $entry string is not consisted of
 *                                  characters that can be converted to a digit.
 * @return int Addition result from adding all of the digits from the $entry.
 */
function add(string $entry): int {
    if (empty($entry)) {
        throw new InvalidArgumentException('Please provide number.');
    }

    $result = 0;

    for ($i = 0; isset($entry[$i]); $i++){
        if (!is_numeric($entry[$i])) {
            throw new InvalidArgumentException('Only numbers expected.');
        }

        $result += $entry[$i];
    }

    return $result;
}

/**
 * Transforms user input and returns transformed text. Transformation
 * works in such a way that it transforms text that is between
 * special tags. There are three special tags that can be used:
 * <ol>
 * <li>"#" - Everything between this tag will become bold</li>
 * <li>"*" - Everything between this tag will become italic</li>
 * <li>"'" - Everything between this tag will become underlined</li>
 * </ol>
 *
 * @param string $entry User input.
 * @throws InvalidArgumentException If input is empty.
 * @return string Transformed input.
 */
function transform(string $entry): string {
    if (!isset($entry)) {
        throw new InvalidArgumentException('Error, file is not provided.');
    }

    $entry = htmlentities($entry, ENT_NOQUOTES, 'UTF-8');

    replace("#", '<strong>', '</strong>', $entry);
    replace("*", '<em>', '</em>', $entry);
    replace("'", '<u>', '</u>', $entry);

    return $entry;
}

/**
 * If $subject contains $find string, $find is replaced.
 * Every Odd occurrence of $find is replaced with $begin and
 * every even occurrence of $find is replaced with $end.
 *
 * @param string $find Replaced string.
 * @param string $begin Every odd occurrence of $find is replaced with it.
 * @param string $end Every even occurrence of $find is replaced with it.
 * @param string $subject String in which replacement takes place.
 * @throws InvalidArgumentException If $find occurs odd amount of times in $subject.
 */
function replace(string $find, string $begin, string $end, string &$subject): void {
    $count = mb_substr_count($subject, $find);

    if ((0 != $count%2)) {
        throw new InvalidArgumentException('Error, tag "' . $find . '"opened but never closed.');
    }

    for ($i = 0; $i < $count/2; $i++) {
        $subject = preg_replace(['/[' . $find . ']/u'], [$begin], $subject, 1);
        $subject = preg_replace(['/[' . $find . ']/u'], [$end], $subject, 1);
    }
}

/**
 * Auto loading function.
 *
 * @param string $class Class to load.
 * @param string $dir Directory in which class is searched for.
 */
function autoload(string $class, string $dir = '../app/') {
    foreach(scandir($dir) as $file) {
        if (is_dir($dir.$file) && substr($file, 0, 1) !== '.') {
            autoload($class, $dir.$file.'/');
        }

        if (preg_match("/.php$/" , $file)) {
            if (str_replace('.php', '', $file) == $class) {
                include $dir . $file;
            }
        }
    }
}