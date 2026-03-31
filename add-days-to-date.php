<?php

/** @var DynamicInputField[] $input_fields */

use DynamicProduct\classes\models\DynamicInputField;

include_once __DIR__ . '/date-helper.php';

foreach ($input_fields as $input_field) {
  $name = $input_field->name;
  if (preg_match('/^delivery_(\d+)/', $name, $matches)) {
    $nb_days = $matches[1];
    $$name = date_format(addDaysToDate(date_create(), $nb_days), 'd/m/Y');
  }
}
