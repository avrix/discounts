<?php

namespace discounts;

use discounts\Items\Item;
use discounts\Items\ItemList;

/**
 * Order object implementation.
 *
 * @package discounts
 */
class Order
{
    /**
     * Order id.
     *
     * @var int
     */
    private $id;

    /**
     * @var Customer
     */
    private $customer;

    /**
     * @var ItemList
     */
    private $items;

    /**
     * @var float
     */
    private $total;

    /**
     * Order constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->customer = new Customer($data['customer-id']);
        $this->items = new ItemList($data['items']);
        $this->total = $data['total'];
    }


    /**
     * Order id getter.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Order's customer getter.
     *
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * Items list getter.
     *
     * @return ItemList|mixed
     */
    public function getItems(): ItemList
    {
        return $this->items;
    }

    /**
     * Total getter.
     *
     * @return float
     */
    public function getTotal(): float
    {
        return $this->total;
    }

    /**
     * Get one random item of given category from item list.
     *
     * @param int $categoryId
     *
     * @return Item
     */
    public function getItemForCategory(int $categoryId): Item
    {
        foreach ($this->items->getItems() as $item) {
            if ($item->category == $categoryId) {
                return $item;
            }
        }
    }
}