<?php
declare(strict_types=1);
namespace User\Form\Auth;

use Laminas\Form\Element;
use Laminas\Form\Form;
use Laminas\Validator;
use Laminas\InputFilter;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\StringLength;
use Laminas\I18n;
use Laminas\Validator\Csrf;
use Laminas\Validator\InArray;
use Laminas\Filter;
use Laminas\Validator\EmailAddress;

class LoginForm extends Form{

    public function __construct($name=null)
    {
        # code...
        parent::__construct('sign_in');
        $this->setAttribute('method','post');
        $this->setElement();
        $this->validatorForm();

        
    }
    private function setElement()
    {
        $this->add([
            'type'=>Element\Email::class,
            'name'=>'email',
            'options'=>[
                'label'=>'Email Address',
            ],
            'attributes'=>[
                'required'=>true,
                'size'=>40,
                'maxlength'=>128,
                'pattern'=>'^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$',
                'autocomplete'=>false,
                'data-toggle'=>'tooltip',
                'class'=>'form-control',
                'title'=>'Provide your email address',
                'placeholder'=>'Enter your email address'
            ]
        ]);
        $this->add([
            'type'=>Element\Password::class,
            'name'=>'password',
            'options'=>[
                'label'=>'Password'
            ],
            'attributes'=>[
                'required'=>true,
                'size'=>40,
                'maxlength'=>25,
                'autocomplete'=>false,
                'data-toggle'=>'tooltip',
                'class'=>'form-control',#styling
                'title'=>'Provide your password',
                'placeholder'=>'Enter your password'
            ]
        ]);
        $this->add([
            'type'=>Element\Checkbox::class,
            'name'=>'recall',
            'options'=>[
                'label'=>'Remember me ?',
                'label_attributes'=>[
                    'class'=>'custom-control-label',
                ],
                'use_hidden_element'=>true,
                'checked_value'=>1,
                'uncheck_value'=>0,
            ],
            'attributes'=>[
                //'required'=>true,
                'value'=>0,
                'id'=>'recall',
                'class'=>'custom-control-input'
                
            ]
        ]);
        $this->add([
            'type' => Element\Csrf::class,
            'name' => 'csrf',
            'options' => [
                'csrf_options' => [
                    'timeout' => 600,
                ],
            ],
        ]);
        #button
        $this->add([
            'type'=>Element\Submit::class,
            'name'=>'account_login',
            'attributes'=>[
                'value'=>'Account Login',
                'class'=>'btn btn-primary'
            ],
        ]);

    }
    private function validatorForm(){
        $inputFilter=new InputFilter\InputFilter();
         $this->setInputFilter($inputFilter);

         $inputFilter->add([
             'name'=>'email',
             'required'=>true,
             'filters'=>[
                 ['name'=>'StringTrim'],
                 ['name'=>'StripTags'],
                 ['name'=>'StringToLower']
             ],
             'validators'=>[
                 [
                     'name'=>'NotEmpty',
                     'options'=>[
                         'messages'=>[
                             NotEmpty::IS_EMPTY=>"Enter yout Email Address please!"  
                         ]
                     ]
                 ],
                 [
                    'name'=>'stringlength',
                    'options'=>[
                        'max'=>128,
                        'min'=>6,
                        'messages'=>[ 
                            StringLength::TOO_SHORT=>'Email address must have at least 6 character',
                            StringLength::TOO_LONG=>'Email address must have at most 128 character',
                        ]
                    ]
                ],
                ['name'=>'EmailAddress'],
                
             ]
         ]);

         $inputFilter->add([
            'name'=>'password',
            'required'=>true,
            'filters'=>[
                ['name'=>'StringTrim'],
                ['name'=>'StripTags']
            ],
            'validators'=>[
                ['name'=>'NotEmpty',],
                [
                   'name'=>'stringlength',
                   'options'=>[
                       'min'=>8,
                       'max'=>25,
                       'messages'=>[
                        StringLength::TOO_SHORT=>'Password must have at least 8 character',
                        StringLength::TOO_LONG=>'Password must have at most 25 character',
                       ]
                   ]
               ],
            ]
        ]);
        
        $inputFilter->add([
            'name'=>'recall',
            'required'=>true,
            'filters'=>[
                ['name'=>'StringTrim'],
                ['name'=>'StripTags'],
                ['name'=>'ToInt'],
            ],
            'validators'=>[
                //['name'=>'NotEmpty'],
                ['name'=>I18n\Validator\IsInt::class],
                [
                   'name'=>'inarray',
                   'options'=>[
                       'haystack'=>[0,1]
                       ]
                ]
               ],
        ]);
        $inputFilter->add([
            'name'=>'csrf',
            'required'=>true,
            'filters'=>[
                ['name'=>'StripTags'],#stips html tags
                ['name'=>'StringTrim'],#remove empty spaces
            ],
            'validators'=>[
                ['name'=>'NotEmpty'],
                [
                    'name'=>'Csrf',
                    'options'=>[
                        'messages'=>[
                            Csrf::NOT_SAME=>'Oops! Refill the form',
                        ],
                    ],
                ],
                
            ],
        ]);
    }
}


?>
