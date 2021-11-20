<?php

namespace User\Form\Item;

use Laminas\Form\Form;

class ItemForm extends Form
{

    private $action;

    public function __construct($action = 'add'){
        $this->action = $action;
        parent::__construct('item');
        $this->getElement();
        
    }

    public function getElement(){

        // item_id
        $this->add([
            'name' => 'item_id2',
            'type' => 'hidden',
        ]);

        // name
        $this->add([
            'name' => 'name',
            'type' => 'text',
            'options' => [
                'label' => 'Name',
            ],
            'attributes' => [
                'class' => 'form-control',
                'required' => 'required',
            ],
        ]);

        // image
        if($this->action == 'add'){
            $this->add([
                'name' => 'image',
                'type' => 'file',
                'options' => [
                    'label' => 'Choose Image',
                ],
                'attributes' => [
                    'class' => 'inputfile',
                    'data-id' => '#img_add #inp_add',
                    'id' => 'inp_add',
                    'accept' => 'image/*',
                    'required' => 'required',
                    'multiple'=>true
                ],
            ]);
        } else { // nếu action là edit thì hình không bắt buộc thêm
            $this->add([
                'name' => 'image',
                'type' => 'file',
                'options' => [
                    'label' => 'Choose Image',
                ],
                'attributes' => [
                    'class' => 'inputfile',
                    'data-id' => '#img_edit #inp_edit',
                    'id' => 'inp_edit',
                    'accept' => 'image/*',
                    'multiple'=>true
                ],
            ]);
        }

        // price
        $this->add([
            'name' => 'price',
            'type' => 'number',
            'options' => [
                'label' => 'Price',
            ],
            'attributes' => [
                'class' => 'form-control',
                'min' => 1,
                'max' => 1000000000,
                'required' => 'required',
            ],
        ]);

        // type
        $this->add([
            'name' => 'cat_id',
            'type' => 'select',
            'options' => [
                'label' => 'Type',
            ],
            'attributes' => [
                'class' => 'form-select',
            ],
        ]);

        // quantity
        $this->add([
            'name' => 'qty',
            'type' => 'number',
            'options' => [
                'label' => 'Quantity',
            ],
            'attributes' => [
                'class' => 'form-control',
                'min' => 1,
                'max' => 1000,
                'required' => 'required',
            ],
        ]);

        // status
        $this->add([
            'name' => 'status',
            'type' => 'select',
            'options' => [
                'label' => 'Status',
                'value_options' => [
                    '1' => 'Business',
                    '2' => 'Stop Business',
                ],
            ],
            'attributes' => [
                'class' => 'form-select',
            ],
        ]);

        // submit
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Submit',
                'class' => 'btn btn-primary',
            ],
        ]);

    }
}