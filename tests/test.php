<?php
/**
 * Created by PhpStorm.
 * User: nico
 * Date: 02.09.2018
 * Time: 20:10
 */

namespace {
    require __DIR__ . '/../vendor/autoload.php';

    use discounts\DiscountService;

    $order = file_get_contents(__DIR__ .'/../data/order2.json');

    $discountService = new DiscountService();
    $result = $discountService->processOrder($order);

    var_dump($result);
}



