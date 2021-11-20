<?php
declare(strict_types=1);

namespace User\Controller;

use Laminas\Authentication\AuthenticationService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use RuntimeException;
use User\Form\Auth\CreateForm;
use User\Model\Table\UserTable;

class AuthController extends AbstractActionController{

    private $userTable;
    public function __construct(UserTable $userTable)
    {
        # code...
        $this->userTable=$userTable;
    }
    public function createAction()
    {   
        #make sure only visitors no sesion access this page
       /* $auth=new AuthenticationService();
        if($auth->hasIdentity())
        {
            #if user has session take them some else
            return $this->redirect()->toRoute('login');
        }*/

        $createForm=new CreateForm();
        $request=$this->getRequest();
        #process data from  form and save
        if($request->isPost()){
            $formData=$request->getPost()->toArray();
            //$createForm->setInputFilter($this->userTable->getCreateFormFilter());
           // $createForm->setData($formData);
            
            $this->userTable->saveAccount($formData);#not created yet
            return false;
        if($createForm->isValid()){
            try{
                $data=$createForm->getData();
                
                $this->userTable->saveAccount($data);#not created yet
                $this->flashMessenger()->addSuccessMessage('Accout successfully created. you can now login');
                #we have not create the login route yet. so we will redirect to the homepage
                #for now
                #be sure to change the route form 'home' to 'login' here
                return $this->redirect()->toRoute('login');#@todo create login route
            }catch(RuntimeException $exception){
                $this->flashMessager()->addErrorMessage($exception->getMessage());
                return $this->redirect()->refresh();#refresh this page to view errors
            }
        }
    } 
    
     $view= new ViewModel(['form'=>$createForm]);
        return $view;
    }
    
}


?>