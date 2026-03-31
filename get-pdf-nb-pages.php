<?php

use DynamicProduct\classes\models\DynamicInputField;

/** @var DynamicInputField[] $input_fields */
/** @var array $file */

/**
 * @param string $pdf
 * @return int
 */
function getPagesCount($pdf) {
    // read pdf file line by line
    $count = 0;
    $f = fopen($pdf, "r");
    while (!feof($f)) {
        $line = fgets($f, 1024);
        if (preg_match("/\/Count (\d+)/", $line, $m)) {
            $count = (int)$m[1];
        }
    }

    return $count;
}

if (isset($file)) {
    $file_input_field = $input_fields['file'];
    if($file_input_field->data && isset($file_input_field->data[0])){
        $file = $file_input_field->data[0];
        $path = $file_input_field->getFilePath($file_input_field->data[0]['file']);
        if(is_file($path)){
            $page_copy = getPagesCount($path);
        }
    }
}