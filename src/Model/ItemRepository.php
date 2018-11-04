<?php

namespace BlueRestAPI\Model;

use BlueRestAPI\Model\Exception\CannotCreateItemException;
use BlueRestAPI\Model\Exception\CannotRemoveItemException;
use BlueRestAPI\Model\Exception\CannotUpdateItemException;
use BlueRestAPI\Model\Exception\ItemNotFoundException;
use Illuminate\Support\Collection;

/**
 * Class ItemRepository
 * @package BlueRestAPI\Model
 */
class ItemRepository
{
    /**
     * @param $id
     * @return Item
     */
    public function findById(int $id): Item
    {
        $item = Item::find($id);
        if (!$item) {
            throw new ItemNotFoundException('Item does not exists');
        }

        return $item;
    }

    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return Item::all();
    }

    /**
     * @param $id
     * @throws \Exception
     */
    public function delete(int $id): void
    {
        $item = $this->findById($id);
        $item->delete();

        if (!$item->trashed()) {
            throw new CannotRemoveItemException('Something went wrong and i cant remove item with id: ' . $id);
        }
    }

    /**
     * @param string $name
     * @param int $amount
     * @return Item
     */
    public function create(string $name, int $amount): Item
    {
        $item = new Item();
        $item->name = $name;
        $item->amount = $amount;
        $item->save();

        if (!$item->id) {
            throw new CannotCreateItemException('Item has not been saved in database');
        }

        return $item;
    }

    /**
     * @param int $id
     * @param string|null $name
     * @param int|null $amount
     * @return Item
     */
    public function update(int $id, string $name = null, int $amount = null): Item
    {
        $item = $this->findById($id);
        $item->name = $name ?? $item->name;
        $item->amount = $amount ?? $item->amount;
        $item->save();

        if (!$item->wasChanged()) {
            throw new CannotUpdateItemException('This item was not updated. Probably values you given are the same.');
        }

        return $item;
    }
}