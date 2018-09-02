<?php

namespace discounts\Items;


class Switches extends Item
{

    /**
     * Category id.
     */
    const ITEM_CATEGORY = 2;

    /**
     * Switches constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->category = self::ITEM_CATEGORY;
    }

}