<?php

declare(strict_types=1);
namespace  User\Controller;

use Laminas\Db\Adapter\Adapter;
use Laminas\Filter\File\Rename;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel ;
use Laminas\View\Model\ViewModel;
use User\Form\Item\AddItemForm;

use User\Model\Table\CategoryTable;
use User\Model\Table\ItemTable;





class ItemController extends AbstractActionController{
    private $adapter;
    private $itemTable;

    public function __construct(Adapter $adapter,ItemTable $itemTable)
    {
        # code...
        $this->adapter=$adapter;
        $this->itemTable=$itemTable;
    }
    public function indexAction(){
        $form=new AddItemForm();
        $this->itemTable->allItem();
       
        return new ViewModel(['form'=>$form,'items' => $this->itemTable->allItem()]);
    }
    
    public function addAction(){
        $form = new AddItemForm();
        $category=new CategoryTable($this->adapter);
        $category2=$category->allCategory();
        $listCat_id = [];
        foreach($category2 as $cat){
            $listCat_id[$cat['cat_id']] = $cat['name'];
        }
        $form->get('cat_id')->setValueOptions($listCat_id);
        $request = $this->getRequest();
        if(! $request->isPost()){
            return new ViewModel (['form'=>$form]);
        }
        $file = $request->getFiles()->toArray();
        foreach($file as $provisional){
            $tamname= $provisional[0]['name']; 
            echo $tamname;
        
        }
        $data = $request->getPost()->toArray();
        $data = array_merge_recursive($data, $file);
        $form->setData($data);
        if(!$form->isValid())//kiểm tra xem ng dùng đúng hay ko
        {
            return new ViewModel([
                'form'=>$form
            ]);
        }
        $data=$form->getData();
        $newname=date('Y-m-d-h-i-s').'-'.$tamname;
        $image=new Rename([
            'target'=>IMAGE_PATH.'hinhminhhoa/'.$newname,
            'overwrite'=>true,
        ]);
        foreach($file as $tam){
            
            $image->filter($tam[0]);
        }
        $data['image']=$newname;
        $result=$this->itemTable->addItem($data);
        $form=new AddItemForm();
        return (new ViewModel(['form'=>$form,'items' => $this->itemTable->allItem()]))->setTemplate('user\item\index');
    }
    public function editAction()
    {
        $form=new AddItemForm();
        $category=new CategoryTable($this->adapter);
        $category2=$category->allCategory();
        $listCat_id = [];
        foreach($category2 as $cat){
            $listCat_id[$cat['cat_id']] = $cat['name'];
        }
        $form->get('cat_id')->setValueOptions($listCat_id);
        $id=$this->params()->fromRoute('id',0);
        $item=$this->itemTable->selectItem($id);
        $form->bind($item);
        $request = $this->getRequest();
        if(!$request->isPost()){
            return new ViewModel(['form'=>$form,'image'=>$item->getItemImage(),'id'=>$id]);
        }
        $file = $request->getFiles()->toArray();
        $data = $request->getPost()->toArray();
        $errorsFile=0;
        $tamname="";
        foreach($file as $provisional){
            $errorsFile= $provisional[0]['error']; 
            $tamname= $provisional[0]['name']; 
        }
        if($errorsFile>0){
            $data['image']=$item->getItemImage();
        }
        else{
            $newname=date('Y-m-d-h-i-s').'-'.$tamname;
            $image=new Rename([
                'target'=>IMAGE_PATH.'hinhminhhoa/'.$newname,
                'overwrite'=>true,
            ]);
            
            foreach($file as $tam){
               $image->filter($tam[0]);
            }
            $data['image']=$newname;
        }
        $result=$this->itemTable->updateItem($data);
        $form=new AddItemForm();
        return (new ViewModel(['form'=>$form,'items' => $this->itemTable->allItem()]))->setTemplate('user\item\index');
        
    }
    /*public function deleteAction()
    {  
        $view = new ViewModel();
        $data=null;
        $isXmlHttpRequest=false;
        if($this->getRequest()->isXmlHttpRequest()==true){
            $isXmlHttpRequest=true;
            $arrayData=array(
                'php'=>'La ngon ngu hay',
                'zend'=>'zen nhu db'
            );
            $data=$arrayData;
        }
        $view->setVariables(array(
            'isXmlHttpRequest'=>$isXmlHttpRequest,
            'data'=>$data
        ));
        $view->setTerminal(true);
        return $view;

    }*/
    public function deleteAction()
    {    $request= $this->getRequest();
        if($request->isPost()){
            $id=$request->getPost()->toArray();
           
           $result=$this->itemTable->deleteItem($id);
           
        }
        $form=new AddItemForm();
        return (new ViewModel(['form'=>$form,'items' => $this->itemTable->allItem()]))->setTemplate('user\item\index');
    }
}