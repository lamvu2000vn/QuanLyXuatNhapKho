<?php
declare(strict_types=1);

namespace User\Model\Table;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\TableGateway\AbstractTableGateway;
use User\Model\Entity\CategoryEntity;
use Laminas\Hydrator\ClassMethodsHydrator;
class CategoryTable extends AbstractTableGateway{
    protected $adapter;
    protected $table='category';

    public function __construct(Adapter $adapter)
    {
        $this->adapter=$adapter;
        $this->initialize();
    }
    public function allCategory()
    {
        $sqlQuery =$this->sql->select()->where(['status'=>'1']);
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        $handler=$sqlStmt->execute();
        return $handler;
    }
    public function addCategory($data)
    {
        $timeNow=date('Y-m-d H:i:s');
        $values=[
            'name'=>ucfirst($data['name']),
            'status'=>'1',
            'created'=>$timeNow,
            'modified'=>$timeNow
        ];
        $sqlQuery=$this->sql->insert()->values($values);
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        return $sqlStmt->execute();
    }
    public function deleteCat($id){
        
        $sqlQuery=$this->sql->update()->set(['status'=>2])->where(['cat_id'=>$id['cat_id']]);
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        return $sqlStmt->execute();
    }
    public function selectCat($id)
    {
        $sqlQuery =$this->sql->select()->where(['cat_id'=>$id]);
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        $handler=$sqlStmt->execute()->current();
        if(!$handler)
        {
            return null;
        }
        $classMethod=new ClassMethodsHydrator();
        $entity= new CategoryEntity();
        $classMethod->hydrate($handler,$entity);
        
        return $entity;
    }
    public function updateCategory($data)
    {
        $sqlQuery=$this->sql->update()->set(['name'=>$data['name']])->where(['cat_id'=>$data['cat_id']]);
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        return $sqlStmt->execute();
    }
}   
?>