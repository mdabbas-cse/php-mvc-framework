<?php

namespace LaraCore\Framework\Db\Relations;

use LaraCore\Framework\Db\Model;

/**
 * Abstract base for all relationship types.
 *
 * Pattern: Template Method — subclasses implement getResults()
 * to define how the related records are fetched.
 */
abstract class Relation
{
    protected Model $parent;

    /** Fully-qualified class name of the related model. */
    protected string $related;

    /** The FK column on the "child" side. */
    protected string $foreignKey;

    /** The PK / local key on the "parent" side. */
    protected string $localKey;

    public function __construct(Model $parent, string $related, string $foreignKey, string $localKey)
    {
        $this->parent     = $parent;
        $this->related    = $related;
        $this->foreignKey = $foreignKey;
        $this->localKey   = $localKey;
    }

    /**
     * Execute the relationship query and return the result.
     *
     * @return Model|Collection|null
     */
    abstract public function getResults();
}
