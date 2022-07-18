<?php











namespace Composer;

use Composer\Autoload\ClassLoader;
use Composer\Semver\VersionParser;






class InstalledVersions
{
private static $installed = array (
  'root' => 
  array (
    'pretty_version' => 'dev-master',
    'version' => 'dev-master',
    'aliases' => 
    array (
    ),
    'reference' => '934d9f52ab04435743892445f92afe6e048d3cc0',
    'name' => 'laravel/laravel',
  ),
  'versions' => 
  array (
    'classpreloader/classpreloader' => 
    array (
      'pretty_version' => '3.2.0',
      'version' => '3.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '4729e438e0ada350f91148e7d4bb9809342575ff',
    ),
    'cordoval/hamcrest-php' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'danielstjules/stringy' => 
    array (
      'pretty_version' => '1.10.0',
      'version' => '1.10.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '4749c205db47ee5b32e8d1adf6d9aff8db6caf3b',
    ),
    'dataworkstr/revisionable' => 
    array (
      'pretty_version' => 'v1.0.8',
      'version' => '1.0.8.0',
      'aliases' => 
      array (
      ),
      'reference' => '0284ddda01878651c594718ba664eaa218f3b4f9',
    ),
    'davedevelopment/hamcrest-php' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'dnoegel/php-xdg-base-dir' => 
    array (
      'pretty_version' => '0.1',
      'version' => '0.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '265b8593498b997dc2d31e75b89f053b5cc9621a',
    ),
    'doctrine/annotations' => 
    array (
      'pretty_version' => 'v1.6.0',
      'version' => '1.6.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c7f2050c68a9ab0bdb0f98567ec08d80ea7d24d5',
    ),
    'doctrine/cache' => 
    array (
      'pretty_version' => 'v1.8.0',
      'version' => '1.8.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd768d58baee9a4862ca783840eca1b9add7a7f57',
    ),
    'doctrine/collections' => 
    array (
      'pretty_version' => 'v1.5.0',
      'version' => '1.5.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a01ee38fcd999f34d9bfbcee59dbda5105449cbf',
    ),
    'doctrine/common' => 
    array (
      'pretty_version' => 'v2.5.3',
      'version' => '2.5.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '10f1f19651343f87573129ca970aef1a47a6f29e',
    ),
    'doctrine/dbal' => 
    array (
      'pretty_version' => 'v2.5.1',
      'version' => '2.5.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '628c2256b646ae2417d44e063bce8aec5199d48d',
    ),
    'doctrine/inflector' => 
    array (
      'pretty_version' => 'v1.3.0',
      'version' => '1.3.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '5527a48b7313d15261292c149e55e26eae771b0a',
    ),
    'doctrine/instantiator' => 
    array (
      'pretty_version' => '1.1.0',
      'version' => '1.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '185b8868aa9bf7159f5f953ed5afb2d7fcdc3bda',
    ),
    'doctrine/lexer' => 
    array (
      'pretty_version' => 'v1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '83893c552fd2045dd78aef794c31e694c37c0b8c',
    ),
    'ezyang/htmlpurifier' => 
    array (
      'pretty_version' => 'v4.14.0',
      'version' => '4.14.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '12ab42bd6e742c70c0a52f7b82477fcd44e64b75',
    ),
    'fzaninotto/faker' => 
    array (
      'pretty_version' => 'v1.8.0',
      'version' => '1.8.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f72816b43e74063c8b10357394b6bba8cb1c10de',
    ),
    'genealabs/phpgmaps' => 
    array (
      'pretty_version' => '0.3.9',
      'version' => '0.3.9.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd258433d0491226597e7cd62b7ca388c35afe943',
    ),
    'hamcrest/hamcrest-php' => 
    array (
      'pretty_version' => 'v1.2.2',
      'version' => '1.2.2.0',
      'aliases' => 
      array (
      ),
      'reference' => 'b37020aa976fa52d3de9aa904aa2522dc518f79c',
    ),
    'illuminate/auth' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.1.46',
      ),
    ),
    'illuminate/broadcasting' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.1.46',
      ),
    ),
    'illuminate/bus' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.1.46',
      ),
    ),
    'illuminate/cache' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.1.46',
      ),
    ),
    'illuminate/config' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.1.46',
      ),
    ),
    'illuminate/console' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.1.46',
      ),
    ),
    'illuminate/container' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.1.46',
      ),
    ),
    'illuminate/contracts' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.1.46',
      ),
    ),
    'illuminate/cookie' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.1.46',
      ),
    ),
    'illuminate/database' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.1.46',
      ),
    ),
    'illuminate/encryption' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.1.46',
      ),
    ),
    'illuminate/events' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.1.46',
      ),
    ),
    'illuminate/exception' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.1.46',
      ),
    ),
    'illuminate/filesystem' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.1.46',
      ),
    ),
    'illuminate/hashing' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.1.46',
      ),
    ),
    'illuminate/http' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.1.46',
      ),
    ),
    'illuminate/log' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.1.46',
      ),
    ),
    'illuminate/mail' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.1.46',
      ),
    ),
    'illuminate/pagination' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.1.46',
      ),
    ),
    'illuminate/pipeline' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.1.46',
      ),
    ),
    'illuminate/queue' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.1.46',
      ),
    ),
    'illuminate/redis' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.1.46',
      ),
    ),
    'illuminate/routing' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.1.46',
      ),
    ),
    'illuminate/session' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.1.46',
      ),
    ),
    'illuminate/support' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.1.46',
      ),
    ),
    'illuminate/translation' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.1.46',
      ),
    ),
    'illuminate/validation' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.1.46',
      ),
    ),
    'illuminate/view' => 
    array (
      'replaced' => 
      array (
        0 => 'v5.1.46',
      ),
    ),
    'jakub-onderka/php-console-color' => 
    array (
      'pretty_version' => 'v0.2',
      'version' => '0.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd5deaecff52a0d61ccb613bb3804088da0307191',
    ),
    'jakub-onderka/php-console-highlighter' => 
    array (
      'pretty_version' => 'v0.3.2',
      'version' => '0.3.2.0',
      'aliases' => 
      array (
      ),
      'reference' => '7daa75df45242c8d5b75a22c00a201e7954e4fb5',
    ),
    'jeremeamia/superclosure' => 
    array (
      'pretty_version' => '2.4.0',
      'version' => '2.4.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '5707d5821b30b9a07acfb4d76949784aaa0e9ce9',
    ),
    'kodova/hamcrest-php' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'laravel/framework' => 
    array (
      'pretty_version' => 'v5.1.46',
      'version' => '5.1.46.0',
      'aliases' => 
      array (
      ),
      'reference' => '7f2f892e62163138121e8210b92b21394fda8d1c',
    ),
    'laravel/laravel' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
      ),
      'reference' => '934d9f52ab04435743892445f92afe6e048d3cc0',
    ),
    'laravelcollective/html' => 
    array (
      'pretty_version' => 'v5.1.11',
      'version' => '5.1.11.0',
      'aliases' => 
      array (
      ),
      'reference' => '99342cc22507cf8d7178bb390c215968183993bb',
    ),
    'league/flysystem' => 
    array (
      'pretty_version' => '1.0.49',
      'version' => '1.0.49.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a63cc83d8a931b271be45148fa39ba7156782ffd',
    ),
    'maennchen/zipstream-php' => 
    array (
      'pretty_version' => '2.1.0',
      'version' => '2.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c4c5803cc1f93df3d2448478ef79394a5981cc58',
    ),
    'markbaker/complex' => 
    array (
      'pretty_version' => '3.0.1',
      'version' => '3.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'ab8bc271e404909db09ff2d5ffa1e538085c0f22',
    ),
    'markbaker/matrix' => 
    array (
      'pretty_version' => '3.0.0',
      'version' => '3.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c66aefcafb4f6c269510e9ac46b82619a904c576',
    ),
    'mockery/mockery' => 
    array (
      'pretty_version' => '0.9.10',
      'version' => '0.9.10.0',
      'aliases' => 
      array (
      ),
      'reference' => '4876fc0c7d9e5da49712554a35c94d84ed1e9ee5',
    ),
    'monolog/monolog' => 
    array (
      'pretty_version' => '1.24.0',
      'version' => '1.24.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'bfc9ebb28f97e7a24c45bdc3f0ff482e47bb0266',
    ),
    'mtdowling/cron-expression' => 
    array (
      'pretty_version' => 'v1.2.1',
      'version' => '1.2.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '9504fa9ea681b586028adaaa0877db4aecf32bad',
    ),
    'myclabs/php-enum' => 
    array (
      'pretty_version' => '1.8.3',
      'version' => '1.8.3.0',
      'aliases' => 
      array (
      ),
      'reference' => 'b942d263c641ddb5190929ff840c68f78713e937',
    ),
    'nesbot/carbon' => 
    array (
      'pretty_version' => '1.36.2',
      'version' => '1.36.2.0',
      'aliases' => 
      array (
      ),
      'reference' => 'cd324b98bc30290f233dd0e75e6ce49f7ab2a6c9',
    ),
    'nikic/php-parser' => 
    array (
      'pretty_version' => 'v2.1.1',
      'version' => '2.1.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '4dd659edadffdc2143e4753df655d866dbfeedf0',
    ),
    'paragonie/random_compat' => 
    array (
      'pretty_version' => 'v1.4.3',
      'version' => '1.4.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '9b3899e3c3ddde89016f576edb8c489708ad64cd',
    ),
    'phpdocumentor/reflection-common' => 
    array (
      'pretty_version' => '1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '21bdeb5f65d7ebf9f43b1b25d404f87deab5bfb6',
    ),
    'phpdocumentor/reflection-docblock' => 
    array (
      'pretty_version' => '4.3.0',
      'version' => '4.3.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '94fd0001232e47129dd3504189fa1c7225010d08',
    ),
    'phpdocumentor/type-resolver' => 
    array (
      'pretty_version' => '0.4.0',
      'version' => '0.4.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '9c977708995954784726e25d0cd1dddf4e65b0f7',
    ),
    'phpoffice/phpspreadsheet' => 
    array (
      'pretty_version' => '1.23.0',
      'version' => '1.23.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '21e4cf62699eebf007db28775f7d1554e612ed9e',
    ),
    'phpspec/php-diff' => 
    array (
      'pretty_version' => 'v1.0.2',
      'version' => '1.0.2.0',
      'aliases' => 
      array (
      ),
      'reference' => '30e103d19519fe678ae64a60d77884ef3d71b28a',
    ),
    'phpspec/phpspec' => 
    array (
      'pretty_version' => '2.5.8',
      'version' => '2.5.8.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd8a153dcb52f929b448c0bf2cc19c7388951adb1',
    ),
    'phpspec/prophecy' => 
    array (
      'pretty_version' => '1.8.0',
      'version' => '1.8.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '4ba436b55987b4bf311cb7c6ba82aa528aac0a06',
    ),
    'phpunit/php-code-coverage' => 
    array (
      'pretty_version' => '2.2.4',
      'version' => '2.2.4.0',
      'aliases' => 
      array (
      ),
      'reference' => 'eabf68b476ac7d0f73793aada060f1c1a9bf8979',
    ),
    'phpunit/php-file-iterator' => 
    array (
      'pretty_version' => '1.4.5',
      'version' => '1.4.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '730b01bc3e867237eaac355e06a36b85dd93a8b4',
    ),
    'phpunit/php-text-template' => 
    array (
      'pretty_version' => '1.2.1',
      'version' => '1.2.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '31f8b717e51d9a2afca6c9f046f5d69fc27c8686',
    ),
    'phpunit/php-timer' => 
    array (
      'pretty_version' => '1.0.9',
      'version' => '1.0.9.0',
      'aliases' => 
      array (
      ),
      'reference' => '3dcf38ca72b158baf0bc245e9184d3fdffa9c46f',
    ),
    'phpunit/php-token-stream' => 
    array (
      'pretty_version' => '1.4.12',
      'version' => '1.4.12.0',
      'aliases' => 
      array (
      ),
      'reference' => '1ce90ba27c42e4e44e6d8458241466380b51fa16',
    ),
    'phpunit/phpunit' => 
    array (
      'pretty_version' => '4.8.36',
      'version' => '4.8.36.0',
      'aliases' => 
      array (
      ),
      'reference' => '46023de9a91eec7dfb06cc56cb4e260017298517',
    ),
    'phpunit/phpunit-mock-objects' => 
    array (
      'pretty_version' => '2.3.8',
      'version' => '2.3.8.0',
      'aliases' => 
      array (
      ),
      'reference' => 'ac8e7a3db35738d56ee9a76e78a4e03d97628983',
    ),
    'psr/http-client' => 
    array (
      'pretty_version' => '1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '2dfb5f6c5eff0e91e20e913f8c5452ed95b86621',
    ),
    'psr/http-factory' => 
    array (
      'pretty_version' => '1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '12ac7fcd07e5b077433f5f2bee95b3a771bf61be',
    ),
    'psr/http-message' => 
    array (
      'pretty_version' => '1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f6561bf28d520154e4b0ec72be95418abe6d9363',
    ),
    'psr/log' => 
    array (
      'pretty_version' => '1.1.0',
      'version' => '1.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '6c001f1daafa3a3ac1d8ff69ee4db8e799a654dd',
    ),
    'psr/log-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0.0',
      ),
    ),
    'psr/simple-cache' => 
    array (
      'pretty_version' => '1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '408d5eafb83c57f6365a3ca330ff23aa4a5fa39b',
    ),
    'psy/psysh' => 
    array (
      'pretty_version' => 'v0.7.2',
      'version' => '0.7.2.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e64e10b20f8d229cac76399e1f3edddb57a0f280',
    ),
    'sebastian/comparator' => 
    array (
      'pretty_version' => '1.2.4',
      'version' => '1.2.4.0',
      'aliases' => 
      array (
      ),
      'reference' => '2b7424b55f5047b47ac6e5ccb20b2aea4011d9be',
    ),
    'sebastian/diff' => 
    array (
      'pretty_version' => '1.4.3',
      'version' => '1.4.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '7f066a26a962dbe58ddea9f72a4e82874a3975a4',
    ),
    'sebastian/environment' => 
    array (
      'pretty_version' => '1.3.8',
      'version' => '1.3.8.0',
      'aliases' => 
      array (
      ),
      'reference' => 'be2c607e43ce4c89ecd60e75c6a85c126e754aea',
    ),
    'sebastian/exporter' => 
    array (
      'pretty_version' => '1.2.2',
      'version' => '1.2.2.0',
      'aliases' => 
      array (
      ),
      'reference' => '42c4c2eec485ee3e159ec9884f95b431287edde4',
    ),
    'sebastian/global-state' => 
    array (
      'pretty_version' => '1.1.1',
      'version' => '1.1.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'bc37d50fea7d017d3d340f230811c9f1d7280af4',
    ),
    'sebastian/recursion-context' => 
    array (
      'pretty_version' => '1.0.5',
      'version' => '1.0.5.0',
      'aliases' => 
      array (
      ),
      'reference' => 'b19cc3298482a335a95f3016d2f8a6950f0fbcd7',
    ),
    'sebastian/version' => 
    array (
      'pretty_version' => '1.0.6',
      'version' => '1.0.6.0',
      'aliases' => 
      array (
      ),
      'reference' => '58b3a85e7999757d6ad81c787a1fbf5ff6c628c6',
    ),
    'swiftmailer/swiftmailer' => 
    array (
      'pretty_version' => 'v5.4.12',
      'version' => '5.4.12.0',
      'aliases' => 
      array (
      ),
      'reference' => '181b89f18a90f8925ef805f950d47a7190e9b950',
    ),
    'symfony/console' => 
    array (
      'pretty_version' => 'v2.7.50',
      'version' => '2.7.50.0',
      'aliases' => 
      array (
      ),
      'reference' => '574cb4cfaa01ba115fc2fc0c2355b2c5472a4804',
    ),
    'symfony/css-selector' => 
    array (
      'pretty_version' => 'v2.8.49',
      'version' => '2.8.49.0',
      'aliases' => 
      array (
      ),
      'reference' => '7b1692e418d7ccac24c373528453bc90e42797de',
    ),
    'symfony/debug' => 
    array (
      'pretty_version' => 'v2.7.50',
      'version' => '2.7.50.0',
      'aliases' => 
      array (
      ),
      'reference' => '4a7330f29b3d215f8bacf076689f9d1c3d568681',
    ),
    'symfony/dom-crawler' => 
    array (
      'pretty_version' => 'v2.7.50',
      'version' => '2.7.50.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd905e1c5885735ee66af60c205429b9941f24752',
    ),
    'symfony/event-dispatcher' => 
    array (
      'pretty_version' => 'v2.8.49',
      'version' => '2.8.49.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a77e974a5fecb4398833b0709210e3d5e334ffb0',
    ),
    'symfony/finder' => 
    array (
      'pretty_version' => 'v2.7.50',
      'version' => '2.7.50.0',
      'aliases' => 
      array (
      ),
      'reference' => '34226a3aa279f1e356ad56181b91acfdc9a2525c',
    ),
    'symfony/http-foundation' => 
    array (
      'pretty_version' => 'v2.7.50',
      'version' => '2.7.50.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e8eb43e042f839b01afe6b5019e4c0c204634dfb',
    ),
    'symfony/http-kernel' => 
    array (
      'pretty_version' => 'v2.7.50',
      'version' => '2.7.50.0',
      'aliases' => 
      array (
      ),
      'reference' => '1b7e922e88baa26761721161c43971718981a10c',
    ),
    'symfony/polyfill-ctype' => 
    array (
      'pretty_version' => 'v1.10.0',
      'version' => '1.10.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e3d826245268269cd66f8326bd8bc066687b4a19',
    ),
    'symfony/polyfill-mbstring' => 
    array (
      'pretty_version' => 'v1.10.0',
      'version' => '1.10.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c79c051f5b3a46be09205c73b80b346e4153e494',
    ),
    'symfony/polyfill-php56' => 
    array (
      'pretty_version' => 'v1.10.0',
      'version' => '1.10.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'ff208829fe1aa48ab9af356992bb7199fed551af',
    ),
    'symfony/polyfill-util' => 
    array (
      'pretty_version' => 'v1.10.0',
      'version' => '1.10.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '3b58903eae668d348a7126f999b0da0f2f93611c',
    ),
    'symfony/process' => 
    array (
      'pretty_version' => 'v2.7.50',
      'version' => '2.7.50.0',
      'aliases' => 
      array (
      ),
      'reference' => 'eda637e05670e2afeec3842dcd646dce94262f6b',
    ),
    'symfony/routing' => 
    array (
      'pretty_version' => 'v2.7.50',
      'version' => '2.7.50.0',
      'aliases' => 
      array (
      ),
      'reference' => '33bd5882f201f9a3b7dd9640b95710b71304c4fb',
    ),
    'symfony/translation' => 
    array (
      'pretty_version' => 'v2.7.50',
      'version' => '2.7.50.0',
      'aliases' => 
      array (
      ),
      'reference' => '1959c78c5a32539ef221b3e18a961a96d949118f',
    ),
    'symfony/var-dumper' => 
    array (
      'pretty_version' => 'v2.7.50',
      'version' => '2.7.50.0',
      'aliases' => 
      array (
      ),
      'reference' => '6f9271e94369db05807b261fcfefe4cd1aafd390',
    ),
    'symfony/yaml' => 
    array (
      'pretty_version' => 'v3.3.18',
      'version' => '3.3.18.0',
      'aliases' => 
      array (
      ),
      'reference' => 'af615970e265543a26ee712c958404eb9b7ac93d',
    ),
    'vlucas/phpdotenv' => 
    array (
      'pretty_version' => 'v1.1.1',
      'version' => '1.1.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '0cac554ce06277e33ddf9f0b7ade4b8bbf2af3fa',
    ),
    'webmozart/assert' => 
    array (
      'pretty_version' => '1.4.0',
      'version' => '1.4.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '83e253c8e0be5b0257b881e1827274667c5c17a9',
    ),
    'xethron/laravel-4-generators' => 
    array (
      'pretty_version' => '3.1.1',
      'version' => '3.1.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '526f0a07d8ae44e365a20b1bf64c9956acd2a859',
    ),
    'xethron/migrations-generator' => 
    array (
      'pretty_version' => 'dev-l5',
      'version' => 'dev-l5',
      'aliases' => 
      array (
      ),
      'reference' => '8a9e187e6d851c59dbd8997a2ac63f0d25d2fe81',
    ),
  ),
);
private static $canGetVendors;
private static $installedByVendor = array();







public static function getInstalledPackages()
{
$packages = array();
foreach (self::getInstalled() as $installed) {
$packages[] = array_keys($installed['versions']);
}


if (1 === \count($packages)) {
return $packages[0];
}

return array_keys(array_flip(\call_user_func_array('array_merge', $packages)));
}









public static function isInstalled($packageName)
{
foreach (self::getInstalled() as $installed) {
if (isset($installed['versions'][$packageName])) {
return true;
}
}

return false;
}














public static function satisfies(VersionParser $parser, $packageName, $constraint)
{
$constraint = $parser->parseConstraints($constraint);
$provided = $parser->parseConstraints(self::getVersionRanges($packageName));

return $provided->matches($constraint);
}










public static function getVersionRanges($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

$ranges = array();
if (isset($installed['versions'][$packageName]['pretty_version'])) {
$ranges[] = $installed['versions'][$packageName]['pretty_version'];
}
if (array_key_exists('aliases', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['aliases']);
}
if (array_key_exists('replaced', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['replaced']);
}
if (array_key_exists('provided', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['provided']);
}

return implode(' || ', $ranges);
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getVersion($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['version'])) {
return null;
}

return $installed['versions'][$packageName]['version'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getPrettyVersion($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['pretty_version'])) {
return null;
}

return $installed['versions'][$packageName]['pretty_version'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getReference($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['reference'])) {
return null;
}

return $installed['versions'][$packageName]['reference'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getRootPackage()
{
$installed = self::getInstalled();

return $installed[0]['root'];
}







public static function getRawData()
{
return self::$installed;
}



















public static function reload($data)
{
self::$installed = $data;
self::$installedByVendor = array();
}




private static function getInstalled()
{
if (null === self::$canGetVendors) {
self::$canGetVendors = method_exists('Composer\Autoload\ClassLoader', 'getRegisteredLoaders');
}

$installed = array();

if (self::$canGetVendors) {
foreach (ClassLoader::getRegisteredLoaders() as $vendorDir => $loader) {
if (isset(self::$installedByVendor[$vendorDir])) {
$installed[] = self::$installedByVendor[$vendorDir];
} elseif (is_file($vendorDir.'/composer/installed.php')) {
$installed[] = self::$installedByVendor[$vendorDir] = require $vendorDir.'/composer/installed.php';
}
}
}

$installed[] = self::$installed;

return $installed;
}
}
