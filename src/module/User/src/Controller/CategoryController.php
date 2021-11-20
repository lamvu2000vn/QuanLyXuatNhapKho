<?php

declare(strict_types=1);
namespace  User\Controller;

use Laminas\Authentication\AuthenticationService;
use Laminas\Db\Adapter\Adapter;
use Laminas\Http\Header\AbstractAccept;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use User\Form\Category\addCategoryForm;
use User\Model\Table\CategoryTable;
use User\Model\Table\ItemTable;

class CategoryController extends AbstractActionController{
    private $adapter;
    private $categoryTable;

    public function __construct(Adapter $adapter,CategoryTable $categoryTable)
    {
        # code...
        $this->adapter=$adapter;
        $this->categoryTable=$categoryTable;
    }
    public function indexAction()
    {
        
        $auth=new AuthenticationService();
        if(!$auth->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }
        return new ViewModel(['category'=>$this->categoryTable->allCategory()]);
    }
    public function editAction()
    {
        $form=new addCategoryForm();
        
        $id=$this->params()->fromRoute('id',0);
        $item=$this->categoryTable->selectCat($id);
        $form->bind($item);
        $request = $this->getRequest();
        if($request->isPost()){
            $data=$request->getPost()->toArray();
            $form->setData($data);
            
                $data['cat_id']=$id;
                $result=$this->categoryTable->updateCategory($data);
                return (new ViewModel(['category'=>$this->categoryTable->allCategory()]))->setTemplate('user\category\index');}
            else
            {
                return new ViewModel([
                    'form'=>$form,'id'=>$id
                ]);
            }
        return new ViewModel([
            'form'=>$form,'id'=>$id
        ]);
    }
    public function addAction()
    {
       $addForm=new addCategoryForm();
       $request=$this->getRequest();
        if($request->isPost()){
            $data=$request->getPost()->toArray();
            $addForm->setData($data);
            if($addForm->isValid())
            {
                $result=$this->categoryTable->addCategory($data);
                return (new ViewModel(['category'=>$this->categoryTable->allCategory()]))->setTemplate('user\category\index');
                
            }
            else
            {
                return new ViewModel([
                    'form'=>$addForm
                ]);
            }
        }
       
        return new ViewModel([
            'form'=>$addForm
        ]);
    }
    public function deleteAction()
    {    $request= $this->getRequest();
        if($request->isPost()){
            $id=$request->getPost()->toArray();
          
            $result=$this->categoryTable->deleteCat($id);
           
        }
       
        return (new ViewModel(['category'=>$this->categoryTable->allCategory()]))->setTemplate('user\category\index');
    }
    public function ajaxdeleAction()
    {
        $request= $this->getRequest();
        if($request->isXmlHttpRequest()){
            if($request->isPost){
                $id=$request->getPost('id');
                $data=[
                    ['id'=>$id,
                    'status'=>true,]
                ];
                $view =new JsonModel($data);
                $view->setTerminal(true);
                return $view;
            }
        }
    }
}