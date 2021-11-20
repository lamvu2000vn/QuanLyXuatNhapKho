<?php 

declare(strict_types=1);
namespace User\Form\Account;

use Laminas\Form\Element;
use Laminas\Form\Form;

class AddAccountForm extends Form{

    public function __construct($name=null)
    {
        # code...
        parent::__construct('add_account');
        $this->setAttribute('method','post');
        $this->setElement();
       // $this->validatorForm();

        
    }
    private function setElement(){
        $this->add([
            'type'=>Element\Hidden::class,
            'name'=>'users_id',
            'attributes'=>[
                'id'=>'users_id'
            ]
        ]);
    }
}