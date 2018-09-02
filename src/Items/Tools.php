<?php

namespace discounts\Items;


class Tools extends Item
{
    /**
     * Category id.
     */
    const ITEM_CATEGORY = 1;

    /**
     * Tools constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->category = self::ITEM_CATEGORY;
    }
}