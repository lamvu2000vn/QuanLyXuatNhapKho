<?php
declare(strict_types=1);
namespace User\Form\Category;

use Laminas\Form\Element;
use Laminas\Form\Form;
use Laminas\InputFilter;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\StringLength;
use Laminas\Validator\Csrf;
class addCategoryForm extends Form{
    public function __construct($name=null)
    {
        # code...
        parent::__construct('add_category');
        $this->setAttribute('method','post');
        $this->setElement();
        $this->validatorForm();

        
    }
    private function setElement(){
        $this->add([
            'type'=>Element\Hidden::class,
            'name'=>'cat_id',
            'attributes'=>[
                'id'=>'cat_id'
            ]
        ]);
        $this->add([
            'type'=>Element\Text::class,
            'name'=>'name',
            'options'=>[
                'label'=>'Name Category : ',
            ],
            'attributes'=>[
                'required'=>true,
                'size'=>40,
                'minlength'=>3,
                'maxlength'=>128,
                'class'=>'form-control',
                'title'=>'Name Category',
                'placeholder'=>'Enter Category'
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
            'name'=>'addCategory',
            'attributes'=>[
                'value'=>'Add Category',
                'class'=>'btn btn-primary'
            ],
        ]);
        $this->add([
            'type'=>Element\Submit::class,
            'name'=>'editCategory',
            'attributes'=>[
                'value'=>'Update Category',
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
                             NotEmpty::IS_EMPTY=>"Enter Category"  
                         ]
                     ]
                 ],
                 [
                    'name'=>'stringlength',
                    'options'=>[
                        'max'=>128,
                        'min'=>3,
                        'messages'=>[ 
                            StringLength::TOO_SHORT=>'Category must have at least 3 character',
                            StringLength::TOO_LONG=>'Category must have at most 128 character',
                        ]
                    ]
                ],
             ]
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