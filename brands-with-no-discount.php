<?php

/** @var int $id_product */
/** @var DynamicProduct $module */
/** @var float $product_price */
/** @var float $result */

/** @var DynamicInputField[] $input_fields */

use DynamicProduct\classes\models\DynamicInputField;

$brands_with_no_reduction = [1];

$reduction = $module->calculator->getProductReduction($id_product);
if (isset($reduction['reduction_type']) && $reduction['reduction_type'] === 'percentage') {
    $reduction_value = (float)$reduction['reduction'];
    $reverse_discount = 1 / (1 - $reduction_value);

    $in_cart = false;
    foreach ($input_fields as $input_field) {
        if ((int)$input_field->id_input) {
            $in_cart = true;
            break;
        }
    }

    $id_manufacturer = (int)Db::getInstance()->getValue('
        SELECT id_manufacturer 
        FROM ' . _DB_PREFIX_ . 'product 
        WHERE id_product = ' . (int)$id_product
    );

    if (!$in_cart && in_array($id_manufacturer, $brands_with_no_reduction)) {
        $product_price_diff = $product_price * ($reverse_discount - 1);
        $result = $result * $reverse_discount + $product_price_diff;
    }
}
