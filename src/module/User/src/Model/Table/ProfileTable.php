<?php
declare(strict_types=1);

namespace User\Model\Table;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\TableGateway\AbstractTableGateway;
use Laminas\Hydrator\ClassMethodsHydrator;
use User\Model\Entity\WareEntity;
use User\Model\Entity\WareHouseEntity;

class ProfileTable extends AbstractTableGateway{
    protected $adapter;
    protected $table='warehouse';

    public function __construct(Adapter $adapter)
    {
        $this->adapter=$adapter;
        $this->initialize();
    }
    public function allProfile()
    {
        $sqlQuery =$this->sql->select()
        ->join(['i'=>'item'],'i.item_id='.$this->table.'.item_id',['name'])
        ->join(['u'=>'users'],'u.id='.$this->table.'.id_user',['username'])
        ->order('date_time DESC');
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        $handler=$sqlStmt->execute();
        return $handler;

    }
    //lấy ra số lượng tồn của item theo item_id
    public function getQtyInStock($item_id){
        // nếu chưa có dữ liệu nào trong bảng
        # code...
        if(count($this->allProfile())==0){
            return false;
        }
        
        $sqlQuery =$this->sql->select()->columns(['ware_id', 'qty_in_stock'])->where(['item_id'=>$item_id])->order('ware_id DESC')->limit(1,1);
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        $handler=$sqlStmt->execute();
       
        
        if(count($handler) == 0){
            return false;
        }
        $listItem = [];
       
        foreach($handler as $user){
            $listItem[$user['ware_id']] = $user['qty_in_stock'];
            $qty = $listItem[$user['ware_id']];
        }
        //var_dump($qty);
        // số lượng tồn
        return $qty;
    }
    public function saveWarehouse($data)
    {
        $ware_id=$data['ware_id'];
        # code...
        if(!$ware_id){
            $value = [
                'item_id' => $data['item_id'],
                'date_time' => $data['date_time'],
                'id_user' => $data['username'],
                'imp_qty' => $data['imp_qty'],
                'qty_in_stock' => 0,
                'type' => $data['type'],
            ];
            // nếu như số lượng tồn = 0 thì số lượng tồn = số lượng nhập
            if(!$data['qty_in_stock']){
                $value['qty_in_stock'] =  $data['imp_qty'];
            } else{
                // nếu là nhập hàng
                if($data['type'] == 1){
                    // lấy số lượng nhập hiện tại + số lượng tồn
                    $qty_new = $data['imp_qty'];
                    $qty_current = $this->getQtyInStock($data['item_id']);

                    $value['qty_in_stock'] = ($qty_current + $qty_new);
                } else{
                    // lấy số lượng tồn - số lượng hiện tại
                    $qty_new = $data['imp_qty'];
                    $qty_current = $this->getQtyInStock($data['item_id']);

                    $value['qty_in_stock'] = ($qty_current - $qty_new);
                }
            }
            // update số lượng trong bảng item
            $item=new ItemTable($this->adapter);
            $item->updateQtyItem($value['item_id'],$value['qty_in_stock']);
            $sqlQuery=$this->sql->insert()->values($value);
            $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
            return $sqlStmt->execute();

        }else{ // cập nhật
            $value = [
                'item_id' => $data['item_id'],
                'date_time' => $data['date_time'],
                'id_user' => $data['username'],
                'imp_qty' => $data['imp_qty'],
                'qty_in_stock' => $data['qty_in_stock'],
                'type' => $data['type'],
            ];

            $listWarehosue = $this->getWarehouseByItemId($value['item_id']);

            $temp = $listWarehosue[count($listWarehosue) - 1];

            // nhập kho
            if($value['type'] == 1){
                // nếu số lượng mới < số lượng cũ
                if($value['imp_qty'] < $temp['imp_qty']){
                    // số lượng tồn - (số lượng nhập cũ - số lượng nhập mới)
                    $value['qty_in_stock'] -= ($temp['imp_qty'] - $value['imp_qty']);
                } else if($value['imp_qty'] > $temp['imp_qty']){ // nếu số lượng mới > số lượng cũ
                    // số lượng tồn + (số lượng nhập mới  - số lượng nhập cũ) 
                    $value['qty_in_stock'] += ($value['imp_qty'] - $temp['imp_qty']);
                }

            } else { // xuất kho
                // nếu số lượng mới < số lượng cũ
                if($value['imp_qty'] < $temp['imp_qty']){
                    // số lượng tồn - (số lượng nhập cũ - số lượng nhập mới)
                    $value['qty_in_stock'] += ($temp['imp_qty'] - $value['imp_qty']);
                } else if($value['imp_qty'] > $temp['imp_qty']){ // nếu số lượng mới > số lượng cũ
                    // số lượng tồn + (số lượng nhập mới  - số lượng nhập cũ) 
                    $value['qty_in_stock'] -= ($value['imp_qty'] - $temp['imp_qty']);
                }
            }

           /* echo '<pre>';
            print_r($data);
            echo '</pre>';
            return false;*/
            
            // update số lượng trong bảng item và history
            $item=new ItemTable($this->adapter);
            $item->updateQtyItem($value['item_id'],$value['qty_in_stock']);
            //update
            $this->updateWare($value, ['ware_id' => $ware_id]);
        }
    }
    public function updateWare($data,$id)
    {
        $sqlQuery=$this->sql->update()->set($data)->where(['ware_id'=>$id]);
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        return $sqlStmt->execute();
    }
    public function getWarehouse($id)
    {
        # code...
        $sqlQuery =$this->sql->select()->where(['ware_id'=>$id]);
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        $handler=$sqlStmt->execute()->current();
       if(!$handler)
        {
            return null;
        }
        
        $classMethod=new ClassMethodsHydrator();
        $entity= new WareEntity();
        $classMethod->hydrate($handler,$entity);
        
        
        return $entity;
    }
    public function getWarehouseByItemId($item_id){


        $sqlQuery =$this->sql->select()->where(['item_id'=>$item_id]);
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        $handler=$sqlStmt->execute();

        

        $listItem = []; $i = 0;
        foreach($handler as $item){
            // $listItem[$item->imp_qty] = $item->qty_in_stock;
            $data = [
                'imp_qty' => $item['imp_qty'],
                'qty_in_stock' => $item['qty_in_stock'],
            ];

            $listItem[$i] = $data;
            $i++;
        }

        return $listItem;
    }
    public function deleteWarehouse($ware_id){
        // lấy dòng với ware_id
        $warehouse = $this->getWarehouseId($ware_id);
        
        $item_id = $warehouse['item_id'];

        // danh sách kho theo item_id
        $list = $this->getListWarehouseByItemId($warehouse['item_id']);
        echo 1;
        // nếu xóa item cuối cùng trong kho thì cập nhật lại số lượng bảng item là 0
        if(count($list) == 1){
            echo 2;
            $item=new ItemTable($this->adapter);
            $item->updateQtyItem($item_id,0);
            $sqlQuery =$this->sql->delete()->where(['ware_id'=>$ware_id]);
            $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
            $handler=$sqlStmt->execute();
        } else {
            echo 3;
            $currentQtyInStock = $warehouse['qty_in_stock'];
            $max = 1;

            // kiểm tra dòng đang xét có số lượng tồn lớn nhất hay không
            foreach($list as $ware){
                if((int)$currentQtyInStock < (int)$ware['qty_in_stock']){
                    $max = 0;
                }
            }

            // xóa dòng theo ware_id
            if(!$max){
                $sqlQuery =$this->sql->delete()->where(['ware_id'=>$ware_id]);
                $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
                $handler=$sqlStmt->execute();
            } else {
                // nếu xóa dòng mới nhất (số lượng tồn cũng lớn nhất) thì xóa tất cả dòng còn lại
                $item=new ItemTable($this->adapter);
                $item->updateQtyItem($item_id,0);
                $this->deleteWarehouseByItemId($warehouse['item_id']);
            }
        }
    }
    public function getWareHouseId($id)
    {
        $sqlQuery =$this->sql->select()->where(['ware_id'=>$id]);
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        $handler=$sqlStmt->execute()->current();
        return $handler;
    }
    public function getListWarehouseByItemId($item_id){
        $sqlQuery =$this->sql->select()->where(['item_id'=>$item_id]);
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        $handler=$sqlStmt->execute();
        
        $listWare = []; $i = 0;
        foreach($handler as $ware){
            $data = [
                'ware_id' => $ware['ware_id'],
                'item_id' => $ware['item_id'],
                'date_time' => $ware['date_time'],
                'username' => $ware['id_user'],
                'imp_qty' => $ware['imp_qty'],
                'qty_in_stock' => $ware['qty_in_stock'],
                'type' => $ware['type'],
            ];

            $listWare[$i] = $data;
            $i++;
        }

        return $listWare;
    }
    public function deleteWarehouseByItemId($item_id){
        $sqlQuery =$this->sql->delete()->where(['item_id'=>$item_id]);
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        $handler=$sqlStmt->execute();
    }
    
}