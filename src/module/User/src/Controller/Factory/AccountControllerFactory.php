<?php
declare(strict_types=1);
namespace User\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\ServiceManager\Factory\FactoryInterface;
use User\Controller\AccountController;
use User\Model\Table\AccountTable;

class AccountControllerFactory implements FactoryInterface{

    public function __invoke(ContainerInterface $container,$requestedName,array $options=null)
    {
            return new AccountController(
                $container->get(Adapter::class),
                $container->get(AccountTable::class),
            );
    }
}

?>