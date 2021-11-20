<?php

declare(strict_types=1);
namespace  User\Controller;


use Laminas\Db\Adapter\Adapter;


use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;

use User\Form\Warehouse\WareForm;
use User\Model\Table\ItemTable;
use User\Model\Table\ProfileTable;
use User\Model\Table\UserTable;

class ProfileController extends AbstractActionController{
    private $adapter;
    private $profileTable;
    public function __construct(Adapter $adapter,ProfileTable $profileTable)
    {
        # code...
        $this->adapter=$adapter;
        $this->profileTable=$profileTable;
    }
    public function indexAction()
    {
        return new ViewModel(['warehouses'=>$this->profileTable->allProfile()]);
    }
    public function editAction(){
        $ware_id = $this->params()->fromRoute('id', 0);
        if(!$ware_id){
            return $this->redirect()->toRoute('project/warehosue');
        }

        $warehouse = $this->profileTable->getWarehouse($ware_id);
        

        $form = new WareForm('edit');
        
       
        // list item_id, item_name
        
        $item = new ItemTable($this->adapter);
        $tam= $item->getItem();
        $form->get('item_id')->setValueOptions($tam);

        $user=new UserTable($this->adapter);
        $users=$user->getUserName();
        // list username, username_name
        $form->get('username')->setValueOptions($users);
        //return new ViewModel(['form'=>$form]);

        // echo '<pre>';
        // print_r($warehouse->date_time);
        // echo '</pre>';
        // return false;

        // fix lỗi render ra view
        // VD: 2021-01-01 12:00:00 => 2021-01-01T12:00
        $datetimelocal = $warehouse->getDateTime();
        $date = str_replace(' ', 'T', $datetimelocal);
        $date = str_replace(':00', '', $date);
        $warehouse->setDateTime($date);

        var_dump($warehouse);

        $form->bind($warehouse);    

        $item_name = $item->getItemName($warehouse->getItemId());
        $date_time = $warehouse->getDateTime();
        $type = $warehouse->getType();

        $viewData = ['form'=>$form, 'ware_id'=>$ware_id, 'item_name' => $item_name, 'date_time'=>$date_time, 'type' => $type];

        $request = $this->getRequest();

        if(!$request->isPost()){
            return $viewData;
        }

        $data = $request->getPost()->toArray();
        var_dump($data);
      
        

        $this->profileTable->saveWarehouse($data);
        
        return $this->redirect()->toRoute('profile/warehouse');
    }
    public function addAction()
    {
        $form = new WareForm('add');

        $item = new ItemTable($this->adapter);
       $tam= $item->allItem();
       
        if(!$item){
           return $this->redirect()->toRoute('profile/item', ['action' => 'add']); 
        }
        $listItem_id = [];
        foreach($tam as $cat){
            $listItem_id[$cat['item_id']] = $cat['name'];
        }
        
        // list item_id, item_name
        $form->get('item_id')->setValueOptions($listItem_id);

        $user=new UserTable($this->adapter);
        $users=$user->getUserName();
        // list username, username_name
        $form->get('username')->setValueOptions($users);

        $request = $this->getRequest();

        if(!$request->isPost()){
            return (['form'=>$form]);
        }

        $data = $request->getPost()->toArray();
        var_dump($data);
        

        /*$warehouse = new Warehouse();
        $form->setInputFilter($warehouse->getInputFilter());
        $form->setData($data);

        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
        // return false;
        
        if(!$form->isValid()){
            return $this->redirect()->toRoute('profile/warehouse', ['action' => 'add']);
        }

        $warehouse->exchangeArray($form->getData());*/

        $this->profileTable->saveWarehouse($data);

        return $this->redirect()->toRoute('profile/warehouse');
    }
        // ajax Lấy số lượng tồn
    public function getQtyInStockAction(){
        $request = $this->getRequest();

        if($request->isXmlHttpRequest()){
            if($request->isPost()){
                $id = $request->getPost('id');

                $qty = $this->profileTable->getQtyInStock($id);
                
                    if(!$qty){
                    $data = [
                        [
                            'status' => false,
                        ],
                    ];
                } else{
                    $data = [
                        [
                            'qty' => $qty,
                            'status' => true,
                        ],
                    ];
                }

                $view = new JsonModel($data); 
                $view->setTerminal(true); 
                return $view;
            }
        }
        return $this->redirect()->toRoute('profile/warehouse');
    }
    public function testAction()
    {
        # code...
        $tam=$this->profileTable->getQtyInStock(1);
        var_dump($tam);
        return false;
    }
    public function deleteAction(){
        $request = $this->getRequest();

        if($request->isPost()){
            $ware_id = $this->params()->fromRoute('id', 0);

            $this->profileTable->deleteWarehouse($ware_id);
            return $this->redirect()->toRoute('profile/warehouse');    
        }

        return $this->redirect()->toRoute('profile/warehouse');
    }

}