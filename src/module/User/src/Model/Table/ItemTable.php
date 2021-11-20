<?php
declare(strict_types=1);

namespace User\Model\Table;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\TableGateway\AbstractTableGateway;
use Laminas\Hydrator\ClassMethodsHydrator;
use User\Model\Entity\ItemEntity;

class ItemTable extends AbstractTableGateway{
    protected $adapter;
    protected $table='item';

    public function __construct(Adapter $adapter)
    {
        $this->adapter=$adapter;
        $this->initialize();
    }
    public function allItem()
    {
        $sqlQuery =$this->sql->select()
        ->join(['cat'=>'category'],'cat.cat_id='.$this->table.'.cat_id',['catName'=>'name'])->where(['item.status'=>1]);
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        $handler=$sqlStmt->execute();
        
        return $handler;
    }
    public function selectItem($id)
    {
        $sqlQuery =$this->sql->select()->where(['item_id'=>$id]);
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        $handler=$sqlStmt->execute()->current();
        if(!$handler)
        {
            return null;
        }
        $entity= new ItemEntity();
        $entity->getItem($handler);
        
        return $entity;
    }
    public function getItemEntity($id)
    {
        # code...
        $sqlQuery =$this->sql->select()->where(['item_id'=>$id]);
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        $handler=$sqlStmt->execute()->current();
       if(!$handler)
        {
            return null;
        }
        
        $classMethod=new ClassMethodsHydrator();
        $entity= new ItemEntity();
        $classMethod->hydrate($handler,$entity);
        
        var_dump($handler);
        return $entity;
    }
    public function addItem($data)
    {
        
        $values=[
            'name'=>ucfirst($data['name']),
            'image'=>$data['image'],
            'price'=>$data['price'],
            'cat_id'=>$data['cat_id'],
            'qty'=>$data['qty'],
            'status'=>'1',
        ];
        
        $sqlQuery=$this->sql->insert()->values($values);
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        return $sqlStmt->execute();
    }
    public function updateItem($data)
    {
        $values=[
            'name'=>ucfirst($data['name']),
            'image'=>$data['image'],
            'price'=>$data['price'],
            'cat_id'=>$data['cat_id'],
            'qty'=>$data['qty'],
            'status'=>'1',
        ];
        $sqlQuery=$this->sql->update()->set($values)->where(['item_id'=>$data['item_id']]);
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        return $sqlStmt->execute();
    }
    public function deleteItem($id){
        
        $sqlQuery=$this->sql->update()->set(['status'=>2])->where(['item_id'=>$id['item_id2']]);
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        return $sqlStmt->execute();
    }
    public function updateQtyItem($item_id, $qty){
        $sqlQuery=$this->sql->update()->set(['qty'=>$qty])->where(['item_id'=>$item_id]);
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        return $sqlStmt->execute();
    }
    public function getItem()
    {
        # code...
        $sqlQuery =$this->sql->select()->columns(['item_id','name'])->where(['status'=>'1']);
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        $handler=$sqlStmt->execute();
        
        $listUser = [];
        foreach($handler as $user){
            $listUser[$user['item_id']] = $user['name'];
        }

        return $listUser;
    }
    public function getItemName($id)
    {
        $sqlQuery =$this->sql->select()->columns(['name'])->where(['item_id'=>$id]);
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        $handler=$sqlStmt->execute();
        
        $listUser = [];
        foreach($handler as $user){
            $listUser[0] = $user['name'];
        }

        return $listUser[0];

    }
}
?>