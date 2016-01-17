<?php

use Knp\ETL\Extractor\CsvExtractor;
use Knp\ETL\Transformer\Doctrine\ObjectTransformer;
use Knp\ETL\Transformer\DataMap;
use Knp\ETL\Loader\Doctrine\ORMLoader;
use Knp\ETL\Context\Doctrine\ORMContext;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\ETL\Loader\Doctrine\Registry;
use Doctrine\ORM\Tools\Setup;
use Pimple\Container;

require_once __DIR__.'/vendor/autoload.php';

$c = new Container([
    'path' => __DIR__.'/csv',
]);
$c['doctrine'] = function($c) {
    $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/src"), true);
    $conn = array(
        'driver' => 'pdo_sqlite',
        'in_memory' => true,
    );

    $em = \Doctrine\ORM\EntityManager::create($conn, $config);
    $c['em'] = $em;

    $tool = new Doctrine\ORM\Tools\SchemaTool($em);
    $tool->createSchema([$em->getClassMetadata('Entity\User')]);

    return new Registry($c, 'ORM', ['default' => $conn], ['default' => $c['em']], 'default', 'default', 'Doctrine\ORM\Proxy\Proxy');
};
$c['etl'] = [
    'users' => new Container([
        'parent' => $c,
        'e' => function($c) { return new CsvExtractor($c['parent']['path'].'/users.csv', ';', '"', 0); },
        't' => function($c) {
            return new ObjectTransformer('Entity\User', new DataMap([
                0 => 'setId',
                1 => 'setUsername',
                2 => 'setEmail',
            ]), $c['parent']['doctrine']);
        },
        'l' => function($c) {
            return new ORMLoader($c['parent']['doctrine']);
        },
        'c' => function($c) { return new ORMContext; },
    ])
];

return $c;
