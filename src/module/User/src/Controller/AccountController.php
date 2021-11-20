<?php 

declare(strict_types=1);
namespace User\Controller;

use Laminas\Db\Adapter\Adapter;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use User\Model\Table\AccountTable;
use Laminas\Cache\StorageFactory;
use Laminas\Serializer;

class AccountController extends AbstractActionController{
    private $adapter;
    private $accountTable;
    public function __construct(Adapter $adapter,AccountTable $accountTable)
    {
        # code...
        $this->adapter=$adapter;
        $this->accountTable=$accountTable;
    }
    public function indexAction()
    {
        $result=$this->accountTable->allAccount();
       
        
        return new ViewModel(['account'=>$result]);
    }
    public function deleteAction()
    {    $request= $this->getRequest();
        if($request->isPost()){
            $id=$request->getPost()->toArray();
          
            $result=$this->accountTable->deleteUsers($id);
           
        }
        
        return (new ViewModel(['account'=>$this->accountTable->allAccount()]))->setTemplate('user\account\index');
    }
    public function testAction()
    {
        $cache = StorageFactory::factory([
            'adapter' => [
                'name'=>'filesystem',
                'option'=>[
                    'namespace'=>'test2',
                    //'ttl'=>5,
                   // 'cache_dir'=>'D:/cache'
                ]
            ],
            'plugins' => [
                'serializer',
                'exception_handler' => ['throw_exceptions' => false],
        ],
        ]);
        $data = $cache->getItem('test2', $success);
        var_dump($data);
        return false;
    }
    public function tamAction()
    {
        // All at once:
        $cache = StorageFactory::factory([
            'adapter' => [
                'name'=>'filesystem',
                'option'=>[
                    'namespace'=>'test2',
                    'ttl'=>900,
                    //'cache_dir'=>'data/cache'
                ]
            ],
            'plugins' => [
                'serializer',
                //'exception_handler' => ['throw_exceptions' => false,]
        ],
        ]);
        
        $data = $cache->getItem('test2', $success);

        
        if (!$success) {
            
            $data = [1, 2, 3,4];
        
           
            $cache->setItem('test2', $data);
        }
        $data = $cache->getItem('test2', $success);
        var_dump($data);
        return false;
    }
}