<?php
declare(strict_types=1);
namespace User\Model\Table;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\TableGateway\AbstractTableGateway;

class AccountTable extends AbstractTableGateway{
    protected $adapter;
    protected $table='users';

    public function __construct(Adapter $adapter)
    {
        $this->adapter=$adapter;
        $this->initialize();
    }
    public function allAccount()
    {
        $sqlQuery =$this->sql->select()->where(['active'=>'2']);
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        $handler=$sqlStmt->execute();
        return $handler;
    }
    public function deleteUsers($id)
    {
        # code...
        $sqlQuery=$this->sql->update()->set(['active'=>2])->where(['id'=>$id['users_id']]);
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        return $sqlStmt->execute(); 
    }
   
}