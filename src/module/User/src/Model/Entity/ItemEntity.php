<?php

declare(strict_types=1);
namespace User\Model\Entity;

class ItemEntity{

    protected $item_id;
    protected $name;
    protected $image;
    protected $price;
    protected $cat_id;
    protected $qty;
    protected $status;

    public function getArrayCopy(){
        return [
            'item_id' => $this->item_id,
            'name' => $this->name,
            'image' => $this->image,
            'price' => $this->price,
            'cat_id' => $this->cat_id,
            'qty' => $this->qty,
            'status' => $this->status,
        ];
    }
    public function getItem($data)
    {
        # code...
        $this->item_id=$data['item_id'];
        $this->name=$data['name'];
        $this->image=$data['image'];
        $this->price=$data['price'];
        $this->cat_id=$data['cat_id'];
        $this->qty=$data['qty'];
        $this->status=$data['status'];

    }
    public function getItemId()
    {
        return $this->item_id;
    }
    public function setItemId($item_id)
    {
        # code...
        $this->item_id=$item_id;
    }
    public function getItemName()
    {
        return $this->name;
    }
    public function setItemName($name)
    {
        # code...
        $this->name=$name;
    }
    public function getItemImage()
    {
        return $this->image;
    }
    public function setItemImage($image)
    {
        # code...
        $this->image=$image;
    }
    public function getItemPrice()
    {
        return $this->price;
    }
    public function setItemPrice($price)
    {
        # code...
        $this->price=$price;
    }
    public function getItemCatId()
    {
        return $this->cat_id;
    }
    public function setItemCatId($cat_id)
    {
        # code...
        $this->cat_id=$cat_id;
    }
    public function getItemQty()
    {
        return $this->qty;
    }
    public function setItemQty($qty)
    {
        # code...
        $this->qty=$qty;
    }
    public function getItemStatus()
    {
        return $this->status;
    }
    public function setItemStatus($status)
    {
        # code...
        $this->status=$status;
    }
}