<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Types extends Model
{
    protected $table = 'mstype';
    protected $fillable = [
        "parentid",
        "typecd",
        "typenm",
        "typeseq",
        "description"
    ];

    public $timestamps = false;

    public $defaultSelects = array(
        "typecd",
        "typenm",
        "typeseq",
        "description",
    );

    static public function foreignSelect($query, $selects = null)
    {
        $types = new Types();
        return $types->withJoin(is_null($selects) ? $types->defaultSelects : $selects, $query);
    }

    static public function select($query, $selects = null)
    {
        $types = new Types();
        return $types->withoutJoin(is_null($selects) ? $types->defaultSelects : $selects, $query);
    }

    private function _withJoin($query, $selects = array())
    {
        return $this->_withoutJoin($query->with([
            'parent' => function($query) {
                Types::select($query);
            }
        ]), $selects);
    }

    private function _withoutJoin($query, $selects = array())
    {
        return $query->select('id', 'parentid')->addSelect($selects);
    }

    public function withJoin($selects = array(), $query = null)
    {
        return $this->_withJoin(is_null($query) ? $this : $query, is_array($selects) ? $selects : func_get_args());
    }

    public function withoutJoin($selects = array(), $query = null)
    {
        return $this->_withoutJoin(is_null($query) ? $this : $query, is_array($selects) ? $selects : func_get_args());
    }

    public function parent()
    {
        return $this->hasOne(Types::class, 'id', 'parentid');
    }
}
