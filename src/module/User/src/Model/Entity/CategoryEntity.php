<?php
declare(strict_types=1);
namespace User\Model\Entity;

class CategoryEntity{
    protected $cat_id;
    protected $name;
    protected $status;
    protected $created;
    protected $modified;

    //cat id
    public function getCatId()
    {
        # code...
        return $this->cat_id;
    }
    public function setCatId($cat_id)
    {
        # code...
        $this->cat_id=$cat_id;
    }
    //name
    public function getCatName()
    {
        # code...
        return $this->name;
    }
    public function setName($name)
    {
        # code...
        $this->name=$name;
    }
    //status
    public function getCatStatus()
    {
        # code...
        return $this->status;
    }
    public function setStatus($status)
    {
        # code...
        $this->status=$status;
    }
    //created
    public function getCatCreated()
    {
        # code...
        return $this->created;
    }
    public function setCreated($created)
    {
        # code...
        $this->created=$created;
    }
    //modified
    public function getCatModified()
    {
        # code...
        return $this->modified;
    }
    public function setModified($modified)
    {
        # code...
        $this->modified=$modified;
    }
    public function getArrayCopy()
    {
        return [
            //'cat_id' => $this->cat_id,
            'name' => $this->name,
           // 'status' => $this->status,
            //'created'=>$this->created,
            //'modified'=>$this->modified,
        ];
    }
}