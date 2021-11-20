<?php
 declare(strict_types=1);
 namespace User\Form\Item;

use Laminas\Form\Annotation\Validator;
use Laminas\Form\Form;
use Laminas\Form\Element;

use Laminas\InputFilter;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\StringLength;
use Laminas\Validator\Csrf;
use Laminas\Form\Element\Hidden;

use Laminas\Validator\Digits;

use Laminas\InputFilter\FileInput;
use Laminas\Stdlib\Message;
use Laminas\Validator\File\Size;
use Laminas\Validator\File\MimeType;
use Laminas\Filter;

class AddItemForm extends Form{
    public function __construct($name=null)
    {
        # code...
        parent::__construct('add_category');
        $this->setAttribute('method','post');
        $this->setElement();
        $this->validatorForm();

        
    }
    private function setElement(){
        //id
        $this->add([
            'type'=>Element\Hidden::class,
            'name'=>'item_id2',
            'attributes'=>[
                'id'=>'item_id2']

        ]);
        //name
        $this->add([
            'type'=>Element\Text::class,
            'name'=>'name',
            'options'=>[
                'label'=>'Name Item : ',
            ],
            'attributes'=>[
                'required'=>true,
                'size'=>40,
                'maxlength'=>128,
                'class'=>'form-control',
                'title'=>'Name Item',
                'placeholder'=>'Enter name Item',
                'value'=>1
            ]
        ]);
        //img
        $this->add([
            'type'=>Element\File::class,
            'name'=>'image',
            'options'=>[
                'label'=>'Image Item'
            ],
            'attributes'=>[
                'id'=>'image_file',
                'required accept'=>'image/x-png,image/gif,image/jpeg',
                'required'=>true,
                'multiple'=>true,
                'class'=>'form-control',
                'onchange'=>"addImage(this)"
            ]
        ]);
        //img
        $this->add([
            'type'=>Element\File::class,
            'name'=>'imageedit',
            'options'=>[
                'label'=>'Image Item'
            ],
            'attributes'=>[
                'id'=>'image_file',
                'multiple'=>true,
                'class'=>'form-control',
                'onchange'=>"addImage(this)"
            ]
        ]);
        //price
        $this->add([
            'type'=>Element\Number::class,
            'name'=>'price',
            'options'=>[
                'label'=>'Price Item : ',
            ],
            'attributes'=>[
                'required'=>true,
                'max'=>10000000,
                'class'=>'form-control',
                'title'=>'Price Item',
                'placeholder'=>'Enter Price Item',
                'value'=>1
            ]
        ]);
        //cat_id
        $this->add([
            'type'=>Element\Select::class,
            'name'=>'cat_id',
            'options'=>[
                'label'=>'Type Item : ',
            ],
            'attributes'=>[
                'class'=>'form-control',
                'title'=>'Type Item',
               
            ]
        ]);
        //qty
        $this->add([
            'type'=>Element\Number::class,
            'name'=>'qty',
            'options'=>[
                'label'=>'Quantity Item : ',
            ],
            'attributes'=>[
                'required'=>true,
                'max'=>10000000,
                'class'=>'form-control',
                'title'=>'Quantity Item',
                'placeholder'=>'Enter Quantity Item',
                'value'=>17
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
            'name'=>'addItem',
            'attributes'=>[
                'value'=>'Add Item',
                'class'=>'btn btn-primary'
            ],
        ]);       
        
    }
    private function validatorForm(){
        $inputFilter=new InputFilter\InputFilter();
         $this->setInputFilter($inputFilter);

         $inputFilter->add([
             'name'=>'name',
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
                             NotEmpty::IS_EMPTY=>"Enter Name Item"  
                         ]
                     ]
                 ],
                 [
                    'name'=>'stringlength',
                    'options'=>[
                        'max'=>128,
                        'min'=>3,
                        'messages'=>[ 
                            StringLength::TOO_SHORT=>'Item must have at least 3 character',
                            StringLength::TOO_LONG=>'Item must have at most 128 character',
                        ]
                    ]
                ],
             ]
         ]);
         
        $inputFilter->add([
            'name'=>'image',
            'required'=>true,
            'filters'=>[
                ['name'=>'StringTrim']
            ],
            'validator'=>[
                [
                    'name'=>'NotEmpty',
                    'options'=>[
                        'message'=>[
                            NotEmpty::IS_EMPTY =>'Please Enter Image',
                    ]
                
                ]
                ],
                [
                    'name'=>'fileSize',
                    'options'=>[
                        'max'=>2*1024*1024,
                        'message'=>[
                            Size::TOO_BIG=>'File must have at most  %max%'
                        ]
                    ]
                ],
                [
                    'name'=>'fileMimeType',
                    'options'=>[
                        'mimeType'=>'image/png,image/jpeg,image/jpg,image/gif',
                        'messages'=>[
                            MimeType::FALSE_TYPE=>" File %type% is not allowed to choose",
                            MimeType::NOT_DETECTED=>"File type unknown",
                            MimeType::NOT_READABLE=>"Mime Type cannot read"
                        ]
                    ]
                ]
            ]
        ]);
        $inputFilter->add([
            'name'=>'price',
            'required'=>true,
            'filters'=>[
                ['name'=>'StringTrim'],
                ['name'=>'StripTags'],
            ],
            'validators'=>[
                [
                    'name'=>'NotEmpty'
                ]
            ],
            ]);
        $inputFilter->add([
            'name'=>'qty',
            'required'=>true,
            'filters'=>[
                ['name'=>'StringTrim'],
                ['name'=>'StripTags'],
            ],
            'validators'=>[
                ['name'=>'NotEmpty'],
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