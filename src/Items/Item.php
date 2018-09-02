<?php

namespace discounts\Items;


class Item
{
    /**
     * Item id.
     *
     * @string
     */
    public $id;

    /**
     * Total sum per item type.
     *
     * @var string
     */
    public $total;

    /**
     * Ordered quantity.
     *
     * @var int
     */
    public $quantity;

    /**
     * Price per item.
     *
     * @var float
     */
    public $price;

    /**
     * Class constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['product-id'];
        $this->quantity = $data['quantity'];
        $this->price = $data['unit-price'];
        $this->total = $data['total'];
    }
}