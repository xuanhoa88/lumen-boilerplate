<?php
namespace Lumia\Uuid;

use Illuminate\Support\Fluent;
use Illuminate\Database\Schema\Grammars\MySqlGrammar as IlluminateMySqlGrammar;

class MySqlGrammar extends IlluminateMySqlGrammar
{

    protected function typeUuid(Fluent $column)
    {
        return 'char(36)';
    }
}