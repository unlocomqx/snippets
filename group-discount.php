<?php

/** @var int $id_product */

if (isset($group_discount)) {
    $group_discount = Group::getReduction(Context::getContext()->customer->id);
}
