<?php
declare(strict_types=1);

namespace User\Model\Table;
use Laminas\Crypt\Password\Bcrypt;
use Laminas\Db\Adapter\Adapter;
use Laminas\Filter;
use Laminas\InputFilter;
use Laminas\Db\TableGateway\AbstractTableGateway;
use Laminas\Validator;
use Laminas\I18n;
use Laminas\Hydrator\ClassMethodsHydrator;
use User\Model\Entity\UserEntity;

class UserTable extends AbstractTableGateway{
    protected $adapter;
    protected $table='users';

    public function __construct(Adapter $adapter)
    {
        $this->adapter=$adapter;
        $this->initialize();
    }
    public function getCreateFormFilter()
    {
        # code...
        $inputFilter=new InputFilter\InputFilter();
        $factory=new InputFilter\Factory();

        $inputFilter->add(
            $factory->createInput(
                [
                    'name'=>'username',
                    'required'=>true,
                    'filters'=>[
                        ['name'=>Filter\StripTags::class], #stips html tags
                        ['name'=>Filter\StringTrim::class], #remove empty spaces
                        ['name'=>I18n\Filter\Alnum::class] # alows only [a-zA-Z0-9] characters
                    ],
                    'validators'=>[
                        ['name'=>Validator\NotEmpty::class],
                        [
                            'name'=>Validator\StringLength::class,
                            'options'=>[
                                'min'=>2,
                                'max'=>25,
                                'messages'=>[
                                    Validator\StringLength::TOO_SHORT=>'user name must have ayt laeast 2 character',
                                    Validator\StringLength::TOO_SHORT=>'user name must have at most 25 character'
                                ],
                            ],
                        ],
                        [
                            'name'=>I18n\Validator\Alnum::class,
                            'options'=>[
                                'messages'=>[
                                    I18n\Validator\Alnum::NOT_ALNUM=>'user must consist of alphaanumeric characters only'
                                ],
                            ],
                        ],
                        [
                            'name'=>'Laminas\Validator\Db\NoRecordExists',
                            'options'=>[
                                'table'=>$this->table,# here we are checking if a username already exists. that's all
                                'filed'=>'username',
                                'adapter'=>$this->adapter,
                            ],
                        ],
                    ],
                ]
            )
        );
        #filter and validate gender select field
        $inputFilter->add(
            $factory->createInput(
                [
                    'name'=>'gender',
                    'required'=>true,
                    'filters'=>[
                        ['name'=>Filter\StripTags::class],#stips html tags
                        ['name'=>Filter\StringTrim::class],#remove empty spaces
                    ],
                    'validators'=>[
                        ['name'=>Validator\NotEmpty::class],
                        [
                            'name'=>Validator\InArray::class,
                            'options'=>[
                                'haystack'=>['Female','Male','Other'],
                            ]
                        ]
                    ],
                ]
            )
        ); 
        #filter and validate email input field
        $inputFilter->add(
            $factory->createInput(
                [
                    'name'=>'email',
                    'required'=>true,
                    'filters'=>[
                        ['name'=>Filter\StripTags::class],#stips html tags
                        ['name'=>Filter\StringTrim::class],#remove empty spaces
                        ['name'=>Filter\StringToLower::class]
                    ],
                    'validators'=>[
                        ['name'=>Validator\NotEmpty::class],
                        ['name'=>Validator\EmailAddress::class],#check if in proper email format
                        [
                            'name'=>Validator\StringLength::class,
                            'options'=>[
                                'min'=>6,
                                'max'=>128,
                                'messages'=>[
                                    Validator\StringLength::TOO_SHORT=>'Email address must have at least 6 character',
                                    Validator\StringLength::TOO_LONG=>'Email address must have at most 128 character',
                                ],
                            ],
                        ],
                        [
                            'name'=>'Laminas\Validator\Db\NoRecordExists',
                            'options'=>[
                                'table'=>$this->table,
                                'field'=>'email',
                                'adapter'=>$this->adapter
                            ],
                        ],
                    ],
                ]
            )
        ) ;
         #filter and validate confirm_email input field
        $inputFilter->add(
            $factory->createInput(
                [
                    'name'=>'confirm_email',
                    'required'=>true,
                    'filters'=>[
                        ['name'=>Filter\StripTags::class],#stips html tags
                        ['name'=>Filter\StringTrim::class],#remove empty spaces
                        ['name'=>Filter\StringToLower::class]
                    ],
                    'validators'=>[
                        ['name'=>Validator\NotEmpty::class],
                        ['name'=>Validator\EmailAddress::class],#check if in proper email format
                        [
                            'name'=>Validator\StringLength::class,
                            'options'=>[
                                'min'=>6,
                                'max'=>128,
                                'messages'=>[
                                    Validator\StringLength::TOO_SHORT=>'Email address must have at least 6 character',
                                    Validator\StringLength::TOO_LONG=>'Email address must have at most 128 character',
                                ],
                            ],
                        ],
                        [
                            'name'=>'Laminas\Validator\Db\NoRecordExists',
                            'options'=>[
                                'table'=>$this->table,
                                'field'=>'email',
                                'adapter'=>$this->adapter
                            ],
                        ],
                        [
                            'name'=>Validator\Identical::class,#compares the specified fields
                            #in our case the email and confrim_email to check if the match
                            'options'=>[
                                'token'=>'email',#field to compare against
                                'messages'=>[#if do not match issue flowing message
                                    Validator\Identical::NOT_SAME=>'Email addresses do not match!',
                            ],
                        ],
                        ],
                    ],
                ]
            )
        );
        #field and validate password input  field
        $inputFilter->add(
            $factory->createInput(
                [
                    'name'=>'password',
                    'required'=>true,
                    'filters'=>[
                        ['name'=>Filter\StripTags::class],#stips html tags
                        ['name'=>Filter\StringTrim::class],#remove empty spaces
                    ],
                    'validators'=>[
                        ['name'=>Validator\NotEmpty::class],
                        [
                            'name'=>Validator\StringLength::class,
                            'options'=>[
                                'min'=>8,
                                'max'=>25,
                                'messages'=>[
                                    Validator\StringLength::TOO_LONG=>'Password must have at most 25 character',
                                    Validator\StringLength::TOO_SHORT=>'Passowrd must have at least 8 character'
                                ]
                            ]
                        ],
                        
                    ],
                ]
            )
        );

        #filter and validate birthday dateselect field
        $inputFilter->add([
            $factory->createInput([
                'name'=>'birthday',
                'required'=>true,
                'filters'=>[
                    ['name'=>Filter\StripTags::class],
                    ['name'=>Filter\StringTrim::class]
                ],
                'validators'=>[
                    ['name'=>Validator\NotEmpty::class],
                    [
                        'name'=>Validator\Date::class,
                        'options'=>[
                            'format'=>'Y-m-d',
                        ],
                    ],
                ],
            ])
        ]);
        #just checking for errors there are too many commas and litt;e stuff to guard against

        #filter and validate confirm_password field
        $inputFilter->add(
            $factory->createInput(
                [
                    'name'=>'confirm_password',
                    'required'=>true,
                    'filters'=>[
                        ['name'=>Filter\StripTags::class],#stips html tags
                        ['name'=>Filter\StringTrim::class],#remove empty spaces
                    ],
                    'validators'=>[
                        ['name'=>Validator\NotEmpty::class],
                        [
                            'name'=>Validator\StringLength::class,
                            'options'=>[
                                'min'=>8,
                                'max'=>25,
                                'messages'=>[
                                    Validator\StringLength::TOO_LONG=>'Password must have at most 25 character',
                                    Validator\StringLength::TOO_SHORT=>'Passowrd must have at least 8 character'
                                ],
                            ],
                        ],
                        [
                            'name'=>Validator\Identical::class,
                            'options'=>[
                                'token'=>'password',
                                'messages'=>[
                                    Validator\Identical::NOT_SAME=>'Passwords do not match!',
                                ],
                            ],
                        ],
                    ],
                ]
            )
        );

        #finally yay!
        #csrf field
          $inputFilter->add(
            $factory->createInput(
                [
                    'name'=>'csrf',
                    'required'=>true,
                    'filters'=>[
                        ['name'=>Filter\StripTags::class],#stips html tags
                        ['name'=>Filter\StringTrim::class],#remove empty spaces
                    ],
                    'validators'=>[
                        ['name'=>Validator\NotEmpty::class],
                        [
                            'name'=>Validator\Csrf::class,
                            'options'=>[
                                'messages'=>[
                                    Validator\Csrf::NOT_SAME=>'Oops! Refill the form',
                                ],
                            ],
                        ],
                        
                    ],
                ]
            )
        );
        return $inputFilter;
    }
    public function saveAccount(array $data)
    {   
        $bcrypt = new Bcrypt();
        $securePass = $bcrypt->create($data['password']);
        $timeNow=date('Y-m-d H:i:s');
        $values=[
            'username'=>ucfirst($data['username']),
            'email'=>mb_strtolower($data['email']),
            'password'=>$securePass,
            'birthday'=>$data['birthday'],
            'gender'=>$data['gender'],
            'photo'=>'1.jpg',
            'role_id'=>$this->assignRoleId(),
            'active'=>'1',
            'created'=>$timeNow,
            'modified'=>$timeNow
        ];
        $sqlQuery=$this->sql->insert()->values($values);
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        return $sqlStmt->execute();
    }
    private function assignRoleId(){
        #the thinking here is thus:
        #any new user must be assign a role
        #first we check if the role id of 1 which is the admin exists
        #if it exists all other users get a role id of 2 which is member
        #recal from our roles table these roles

        $sqlQuery =$this->sql->select()->where(['role_id'=>1]);
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        $handler=$sqlStmt->execute()->current();

        return(!$handler)?1:2;
    }
  # that was long . hopefully we made no errors typing in the data
  # let us see if everything works well.
    public function checkAccount($password,$email)
    {
        $bcrypt = new Bcrypt();
        $securePass = $bcrypt->create($password);
        $sqlQuery =$this->sql->select()->where(['email'=>$email]);
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        $handler=$sqlStmt->execute()->current();
        return $handler;
    }
    public function fetchAccountByEmail(string $email){
        $sqlQuery =$this->sql->select()
        ->join('role','role.id='.$this->table.'.role_id',[])
        ->where(['email'=>$email]);
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        $handler=$sqlStmt->execute()->current();
       if(!$handler)
        {
            return null;
        }
        $classMethod=new ClassMethodsHydrator();
        $entity= new UserEntity();
        $classMethod->hydrate($handler,$entity);
        return $entity;
    }
    public function getUserName()
    {
        $sqlQuery =$this->sql->select()->columns(['id','email'])->where(['active'=>'1']);
        $sqlStmt=$this->sql->prepareStatementForSqlObject($sqlQuery);
        $handler=$sqlStmt->execute();
        
        $listUser = [];
        foreach($handler as $user){
            $listUser[$user['id']] = $user['email'];
        }

        return $listUser;
    }
    
}


?>