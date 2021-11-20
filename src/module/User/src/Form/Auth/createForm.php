<?php
namespace User\Form\Auth;
use Laminas\Form\Form;
use Laminas\Form\Element;
class CreateForm extends Form{
    public function __construct($name=null)
    {
        # code...
        parent::__construct('new_account');
        $this->setAttribute('method','post');

      $this->add([
            'type'=>Element\Text::class,
            'name'=>'username',
            'options'=>[
                'label'=>'Username'
            ],
            'attributes'=>[
                'required'=>true,
                'size'=>40,
                'maxlength'=>25,
                'pattern'=>'^[a-zA-Z0-9]+$',# enforcing what type of data we accept
                'data-toggle'=>'tooltip',
                'class'=>'form-control',
                'title'=>'Username must consist of alphanumeric characters only',
                'placeholder'=>'Enter your name',
            ]
        ]);
        $this->add([
            'type'=>Element\Select::class,
            'name'=>'gender',
            'options'=>[
                'label'=>'Gender',
                'empty_option'=>'Select...',
                'value_options'=>[
                    'Female'=>'Female',
                    'Male'=>'Male',
                    'Other'=>'Other'
                ],
            ],
            'attributes'=>[
                'required'=>true,
                'class'=>'custom-select', #styling
            ]
        ]);
        #email address
        $this->add([
            'type'=>Element\Email::class,
            'name'=>'email',
            'options'=>[
                'label'=>'Email Address'
            ],
            'attributes'=>[
                'required'=>true,
                'size'=>40,
                'maxlength'=>128,
                'pattern'=>'^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$',
                'autocomplete'=>false,
                'data-toggle'=>'tooltip',
                'class'=>'form-control',
                'title'=>'Provide a valid and working email address',
                'placeholder'=>'Enter your Email'
            ],
        ]);
         #confirm_email address
         $this->add([
            'type'=>Element\Email::class,
            'name'=>'confirm_email',
            'options'=>[
                'label'=>'Veryfy Email Address'
            ],
            'attributes'=>[
                'required'=>true,
                'size'=>40,
                'maxlength'=>128,
                'pattern'=>'^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$',
                'autocomplete'=>false,
                'data-toggle'=>'tooltip',
                'class'=>'form-control',
                'title'=>'Email address must match that provided above',
                'placeholder'=>'Enter your Email'
            ]
        ]);
        #birth day select field
        $this->add([
            'type'=>Element\DateSelect::class,
            'name'=>'birthday',
            'options'=>[
                'label'=>'Select Your Date of Birth',
                'create_empty_opiton'=>true,
                'max_year'=>date('Y')-13,#here we want over the age of 13 only
                'year_attributes'=>[
                    'class'=>'custom-select  w-30'
                ],
                'month_attributes'=>[
                    'class'=>'custom-select  w-30'
                ],
                'day_attributes'=>[
                    'class'=>'custom-select  w-30',
                    'id'=>'day'
                ],
            ],
            'attributes'=>[
                'required'=>true
            ]
        ]);
        #password input text field 
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
                'title'=>'Passoword must have between 8 and 25 characters',
                'placeholder'=>'Enter your password'
            ]
        ]);
        #confirm password input field
        $this->add([
            'type'=>Element\Password::class,
            'name'=>'confirm_password',
            'options'=>[
                'label'=>'Verify Password'
            ],
            'attributes'=>[
                'required'=>true,
                'size'=>40,
                'maxlength'=>25,
                'autocomplete'=>false,
                'data-toggle'=>'tooltip',
                'class'=>'form-control',#styling
                'title'=>'Passoword  must match that provided above',
                'placeholder'=>'Enter your password again'
            ]
        ]);
        #cross-site-request forgery (csrf) field
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
            'name'=>'create_account',
            'attributes'=>[
                'value'=>'Create Account',
                'class'=>'btn btn-primary'
            ],
        ]);

    }
}

?>