<?php
declare(strict_types=1);
namespace User\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\ServiceManager\Factory\FactoryInterface;
use User\Controller\CategoryController;
use User\Controller\ItemController;
use User\Model\Table\CategoryTable;
use User\Model\Table\ItemTable;

class CategoryControllerFactory implements FactoryInterface{
    public function __invoke(ContainerInterface $container,$requestedName,array $options=null)
    {
            return new CategoryController(
                $container->get(Adapter::class),
                $container->get(CategoryTable::class),
            );
    }
}

?>