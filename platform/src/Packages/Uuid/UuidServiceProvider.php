<?php
namespace Lumia\Packages\Uuid;

use Exception;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\Codec\OrderedTimeCodec;
use Illuminate\Database\Connection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Grammars\Grammar;
use Illuminate\Database\Query\Grammars\MySqlGrammar as IlluminateMySqlGrammar;
use Illuminate\Database\Query\Grammars\SQLiteGrammar as IlluminateSQLiteGrammar;

class UuidServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->optimizeUuids();
    }

    protected function optimizeUuids()
    {
        $factory = new UuidFactory();
        $factory->setCodec(new OrderedTimeCodec($factory->getUuidBuilder()));
        Uuid::setFactory($factory);
    }
}