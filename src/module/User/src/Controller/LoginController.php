<?php
declare(strict_types=1);
namespace User\Controller;

use Laminas\Authentication\Adapter\DbTable\CredentialTreatmentAdapter;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Result;
use Laminas\Crypt\Password\Bcrypt;
use Laminas\Db\Adapter\Adapter;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Session\SessionManager;
use Laminas\View\Model\ViewModel;
use User\Form\Auth\LoginForm;
use User\Model\Table\UserTable;

class LoginController extends AbstractActionController{
    private $adapter;
    private $userTable;

    public function __construct(Adapter $adapter,UserTable $userTable)
    {
        # code...
        $this->adapter=$adapter;
        $this->userTable=$userTable;
    }
    /*public function indexAction()
    {
        # code...
        $auth=new AuthenticationService();
        if($auth->hasIdentity()){
            return $this->redirect()->toRoute('slugin');
        }
        $loginForm=new LoginForm();
        $request=$this->getRequest();
        if($request->isPost()){
            $data=$request->getPost()->toArray();
            $loginForm->setData($data);
            if($loginForm->isValid())//kiểm tra xem ng dùng đúng hay ko
            {
                
                $authAdapter=new CredentialTreatmentAdapter($this->adapter);
                $authAdapter->setTableName($this->userTable->getTable('users'))
                                                            ->setIdentityColumn('email')
                                                            ->setCredential('password')
                                                            ->getDbSelect()->where(['active'=>1]);
                
                 #data form login
                $data=$loginForm->getData();
                $authAdapter->setIdentity($data['email']);
               
                #pass hasding  class
                $hash=new Bcrypt();
                #well let us use the email address from the form 
                $info=$this->userTable->fetchAccountByEmail($data['email']);
                //var_dump($info->getPassword());
                
                #now compare password from form input with that alrealy in the table
                if($hash->verify($data['password'],$info->getPassword())){

                    $authAdapter->setCredentialColumn($info->getPassword());# why ? to gracefully handle errors
                }else
                {
                    $authAdapter->setCredential('');#why? to 
                }
                
                $authResult=$auth->authenticate($authAdapter);
                return false;
                switch($authResult->getCode()){
                    case Result::FAILURE_IDENTITY_NOT_FOUND:
                        $this->flashMessenger()->addErrorMessage('Unkow email address !');
                        return $this->redirect()->refresh();
                        break;
                    case Result::FAILURE_CREDENTIAL_INVALID:
                        $this->flashMessenger()->addErrorMessage('Incorrect Password!');
                        return $this->redirect()->refresh();
                        break;
                    case Result::SUCCESS:
                        if($data['recal']==1){
                            $ssm=new SessionManager();
                            $ttl=1814400;#21 day
                            $ssm->rememberMe($ttl);
                        }
                        $storage=$auth->getStorage();
                        # what we are saying here is: get all columns from row corresponding
                        #to this user data except for the coloumn of created and modified
                        $storage->write($authAdapter->getResultRowObject(null,['created','modified']));
                        #readching  here  should  mean  everything  is fine 
                        # let us now  create  the proifile route  and will be done
                        return $this->redirect()->toRoute(
                            'profile',
                            [
                                'id'=>$info->getUserId(),
                                'username'=>mb_strtolower($info->getUserName())
                            ]
                            );
                        break;
                    default:
                        $this->flashMessenger()->addErrorMessage('Authentication failed. Tray agin');
                        return $this->redirect()->refresh();
                        break;
                }
            }
        }
        
        
        
        /*$tam=$this->userTable->fetchAccountByEmail('tien@gmai.com');
        
        return (new ViewModel(['form'=>$loginForm]))->setTemplate('user\auth\login');
    
    }*/
    public function indexAction()
    {
        # code...
        $auth=new AuthenticationService();
       if($auth->hasIdentity()){
            return $this->redirect()->toRoute('profile');
        }
        $loginForm=new LoginForm();
        $request=$this->getRequest();
        if($request->isPost()){
            $data=$request->getPost()->toArray();
            $loginForm->setData($data);
            if($loginForm->isValid())//kiểm tra xem ng dùng đúng hay ko
            {
                $authAdapter=new CredentialTreatmentAdapter($this->adapter);
                $info=$this->userTable->fetchAccountByEmail($data['email']);
                if($info->getPassword()==$data['password']){
                    if($data['recall']==1){
                        
                        $ssm=new SessionManager();
                        $ttl=3*60;#21 day
                        $ssm->rememberMe($ttl);
                        $storage=$auth->getStorage();
                        # what we are saying here is: get all columns from row corresponding
                        #to this user data except for the coloumn of created and modified
                        $storage->write($authAdapter->getResultRowObject(null,['created','modified']));
                        #readching  here  should  mean  everything  is fine 
                        # let us now  create  the proifile route  and will be done
                       
                        return $this->redirect()->toRoute(
                            'profile',
                            [
                                'id'=>$info->getUserId(),
                                'username'=>mb_strtolower($info->getUserName())
                            ]
                            );
                            
                    }
                    else{
                        return $this->redirect()->toRoute(
                            'profile',
                            [
                                'id'=>$info->getUserId(),
                                'username'=>mb_strtolower($info->getUserName())
                            ]
                            );
                    }

                }else
                {
                    return (new ViewModel(['form'=>$loginForm]))->setTemplate('user\auth\login');
                }
                
                
            }
        }
        /*$tam=$this->userTable->fetchAccountByEmail('tien@gmai.com');*/
        
        return (new ViewModel(['form'=>$loginForm]))->setTemplate('user\auth\login');
    
    }

}