<?php

/** @var int $id_product */
/** @var int $id_attribute */
/** @var int $quantity */

/** @var float $result */

/** @var float $product_price */

use DynamicProduct\classes\DynamicTools;
use DynamicProduct\classes\module\DynamicCalculator;

$reverse_discount = 1;
$dynamicCalculator = new DynamicCalculator(DynamicTools::getModule(), DynamicTools::getContext());
$specific_price = $dynamicCalculator->getReduction([
    'id_product' => $id_product,
    'id_product_attribute' => $id_attribute,
    'quantity' => $quantity,
    'id_cart' => 0,
]);
if (is_array($specific_price) && $specific_price['reduction_type'] === 'percentage') {
  $discount = $specific_price['reduction'];
  if ($discount > 0) {
    // You can multiply your price by this value to reverse the discount
    // Before: [width] * [height] * 10
    // After: ([width] * [height] * 10) * $reverse_discount
    $reverse_discount = 1 / (1 - $discount);
    $result *= $reverse_discount;
  }
}
