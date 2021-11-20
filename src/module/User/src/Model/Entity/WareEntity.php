<?php 
declare(strict_types=1);

namespace User\Model\Entity;

class WareEntity{
    protected $ware_id;
    protected $item_id;
    protected $date_time;
    protected $user_id;
    protected $imp_qty;
    protected $qty_in_stock;
    protected $type;


    public function setWareId($ware_id)
    {
        $this->ware_id=$ware_id;
        
    }
    public function setItemId($item_id)
    {
        $this->item_id=$item_id;
        
    }
    public function getItemId()
    {
        # code...
        return $this->item_id;
    }
    public function setDateTime($date_time)
    {
        $this->date_time=$date_time;
        
    }
    public function getDateTime()
    {
        # code...
        return $this->date_time;
    }
    public function setUserId($user_id)
    {
        $this->user_id=$user_id;
        
    }
    public function setImpQty($imp_qty)
    {
        $this->imp_qty=$imp_qty;
        
    }
    public function setQtyInStock($qty_in_stock)
    {
        $this->qty_in_stock=$qty_in_stock;
        
    }
    public function setType($type)
    {
        $this->type=$type;
        
    }
    public function getType()
    {
        
        return $this->type;
    }
    public function getArrayCopy(){
        return [
            'ware_id' => $this->ware_id,
            'item_id' => $this->item_id,
            'date_time' => $this->date_time,
            'username' => $this->user_id,
            'imp_qty' => $this->imp_qty,
            'qty_in_stock' => $this->qty_in_stock,
            'type' =>  $this->type,
        ];
    }

}