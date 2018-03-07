<?php
namespace Lumia\Uuid;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;

trait HasUuid
{

    protected static function bootHasUuid()
    {
        static::creating(function (Model $model) {
            if ($model->{$model->getKeyName()}) {
                return;
            }
            
            $model->{$model->getKeyName()} = Uuid::uuid4();
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function getKeyName()
    {
        return 'uuid';
    }

    public function resolveRouteBinding($value)
    {
        return $this->where([
            $this->getRouteKeyName() => $value
        ])
            ->first();
    }
}