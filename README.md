# Discounts service
*By [avrix]*

This code offers a first hand implementation to a discounts service.

## Installation

Use [Composer](https://getcomposer.org/) to install the library.

``` bash
$ composer require discounts
```
## Basic usage

```php
    use discounts\DiscountService;

    $order = file_get_contents(__DIR__ .'/../data/order2.json');

    $discountService = new DiscountService();
    $result = $discountService->processOrder($order);

    var_dump($result);
```
