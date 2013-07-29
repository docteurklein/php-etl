<?php

namespace Knp\ETL\Loader\Doctrine;

use Doctrine\Common\Persistence\AbstractManagerRegistry;

class Registry extends AbstractManagerRegistry
{
    private $c;

    public function __construct($c, $name, array $connections, array $managers, $defaultConnection, $defaultManager, $proxyInterfaceName)
    {
        $this->c = $c;
        parent::__construct($name,  $connections,  $managers, $defaultConnection, $defaultManager, $proxyInterfaceName);

    }

    public function getService($name)
    {
        return $this->c['em'];
    }

    public function resetService($name)
    {
        $closure = $this->c->raw('em');
        $this->c['em'] = $closure($this->c);
    }

    public function getAliasNamespace($alias)
    {
    }
}
