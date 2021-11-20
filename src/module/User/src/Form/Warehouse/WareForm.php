<?php

declare(strict_types=1);

namespace User\Form\Warehouse;

use Laminas\Form\Form;

class WareForm extends Form{
    private $action;

    public function __construct($action = 'add'){
        $this->action = $action;

        parent::__construct('warehouse');
        $this->getElement();
    }

    public function getElement(){

        // ware_id
        $this->add([
            'name' => 'ware_id',
            'type' => 'hidden',
        ]);

        if($this->action == 'add'){
            // item_id
            $this->add([
                'name' => 'item_id',
                'type' => 'select',
                'options' => [
                    'label' => 'Item',
                ],
                'attributes' => [
                    'class' => 'form-select',
                    'id' => 'item_id',
                ],
            ]);

            // Date
            $this->add([
                'name' => 'date_time',
                'type' => 'datetimelocal',
                'options' => [
                    'label' => 'Date',
                    'format' => 'Y-m-d\TH:i',
                ],
                'attributes' => [
                    'class' => 'form-control',
                    'id' => 'date_time',
                    'required' => true,
                ],
            ]);

            // Type
            $this->add([
                'name' => 'type',
                'type' => 'select',
                'options' => [
                    'label' => 'Type',
                    'value_options' => [
                        '1' => 'Import',
                        '2' => 'Export',
                    ],
                ],
                'attributes' => [
                    'class' => 'form-select',
                    'id' => 'type',
                ]
            ]);
        } else {
            // item_id
            $this->add([
                'name' => 'item_id',
                'type' => 'select',
                'options' => [
                    'label' => 'Item',
                ],
                'attributes' => [
                    'class' => 'form-select',
                    'id' => 'item_id',
                    'hidden' => true,
                ],
            ]);

            // Date
            $this->add([
                'name' => 'date_time',
                'type' => 'datetimelocal',
                'options' => [
                    'label' => 'Date',
                    'format' => 'Y-m-dTH:i:s',
                ],
                'attributes' => [
                    'class' => 'form-control',
                    'id' => 'date_time',
                    'required' => true,
                    'hidden' => true,
                ],
            ]);

            // type
            $this->add([
                'name' => 'type',
                'type' => 'select',
                'options' => [
                    'label' => 'Type',
                    'value_options' => [
                        '1' => 'Import',
                        '2' => 'Export',
                    ],
                ],
                'attributes' => [
                    'class' => 'form-select',
                    'id' => 'type',
                    'hidden' => true,
                ]
            ]);
        }

        // Username
        $this->add([
            'name' => 'username',
            'type' => 'select',
            'options' => [
                'label' => 'Username',
            ],
            'attributes' => [
                'class' => 'form-select',
            ],
        ]);

        // Import
        $this->add([
            'name' => 'imp_qty',
            'type' => 'number',
            'options' => [
                'label' => 'Import',
            ],
            'attributes' => [
                'class' => 'form-control',
                'min' => 1,
                'max' => 1000000,
                'id' => 'imp_qty',
                'required' => true,
            ],
        ]);

        // qty_in_stock
        $this->add([
            'name' => 'qty_in_stock',
            'type' => 'number',
            'options' => [
                'label' => 'Quantity in stock',
            ],
            'attributes' => [
                'class' => 'form-control',
                'id' => 'qty_in_stock',
                'readonly' => true,
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