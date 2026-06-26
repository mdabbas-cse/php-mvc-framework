<?php

namespace LaraCore\Framework\Db\Relations;

use LaraCore\Framework\Db\Collection;
use LaraCore\Framework\Db\Connection;
use LaraCore\Framework\Db\Model;
use PDO;

/**
 * Many-to-many via an intermediate pivot table.
 *
 * Example:
 *   class User extends Model {
 *       public function roles(): BelongsToMany {
 *           return $this->belongsToMany(Role::class);
 *           // pivot table: role_users  (alphabetical order)
 *           // pivot.user_id  -> users.id
 *           // pivot.role_id  -> roles.id
 *       }
 *   }
 *   $user->roles->pluck('name');
 *
 *   // Explicit pivot:
 *   $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
 */
class BelongsToMany extends Relation
{
    /** Pivot table name. */
    protected string $pivotTable;

    /** FK on pivot pointing to the related model. */
    protected string $relatedKey;

    public function __construct(
        Model $parent,
        string $related,
        string $pivotTable,
        string $foreignKey,
        string $relatedKey
    ) {
        $this->parent     = $parent;
        $this->related    = $related;
        $this->pivotTable = $pivotTable;
        $this->foreignKey = $foreignKey;
        $this->relatedKey = $relatedKey;
        $this->localKey   = $parent::getPrimaryKeyName();
    }

    /**
     * Fetch related records via the pivot table as a Collection.
     *
     * @return Collection
     */
    public function getResults(): Collection
    {
        $relatedInstance = new $this->related();
        $relatedTable    = $relatedInstance::getTable();
        $relatedPk       = $relatedInstance::getPrimaryKeyName();
        $parentId        = $this->parent->{$this->localKey};

        $sql = sprintf(
            'SELECT `%s`.* FROM `%s`'
            . ' INNER JOIN `%s` ON `%s`.`%s` = `%s`.`%s`'
            . ' WHERE `%s`.`%s` = :pivot_id',
            $relatedTable,
            $relatedTable,
            $this->pivotTable,
            $this->pivotTable,
            $this->relatedKey,
            $relatedTable,
            $relatedPk,
            $this->pivotTable,
            $this->foreignKey
        );

        $pdo  = Connection::getInstance();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':pivot_id', $parentId);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $models = array_map(
            fn($row) => $relatedInstance->newFromBuilder($row),
            $rows
        );
        return new Collection($models);
    }

    // -------------------------------------------------------------------------
    // Pivot table helpers
    // -------------------------------------------------------------------------

    /**
     * Attach related model(s) via the pivot table.
     *
     * @param int|array $ids        Related model IDs to attach
     * @param array     $extra      Extra columns to insert into pivot
     */
    public function attach($ids, array $extra = []): void
    {
        $ids = (array) $ids;
        $pdo = Connection::getInstance();
        $parentId = $this->parent->{$this->localKey};

        foreach ($ids as $id) {
            $pivotData = array_merge(
                [$this->foreignKey => $parentId, $this->relatedKey => $id],
                $extra
            );
            $cols   = implode(', ', array_map(fn($c) => "`{$c}`", array_keys($pivotData)));
            $params = implode(', ', array_map(fn($c) => ":{$c}", array_keys($pivotData)));
            $sql    = "INSERT INTO `{$this->pivotTable}` ({$cols}) VALUES ({$params})";
            $stmt   = $pdo->prepare($sql);
            foreach ($pivotData as $col => $val) {
                $stmt->bindValue(':' . $col, $val);
            }
            $stmt->execute();
        }
    }

    /**
     * Detach related model(s) from the pivot table.
     *
     * @param int|array|null $ids   If null, detach all.
     */
    public function detach($ids = null): void
    {
        $pdo      = Connection::getInstance();
        $parentId = $this->parent->{$this->localKey};

        if ($ids === null) {
            $sql  = "DELETE FROM `{$this->pivotTable}` WHERE `{$this->foreignKey}` = :parent_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':parent_id', $parentId);
            $stmt->execute();
            return;
        }

        foreach ((array) $ids as $id) {
            $sql  = "DELETE FROM `{$this->pivotTable}` WHERE `{$this->foreignKey}` = :parent_id AND `{$this->relatedKey}` = :related_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':parent_id', $parentId);
            $stmt->bindValue(':related_id', $id);
            $stmt->execute();
        }
    }

    /**
     * Sync: ensure only the given IDs are attached (attach new, detach missing).
     *
     * @param array $ids
     */
    public function sync(array $ids): void
    {
        $this->detach();
        $this->attach($ids);
    }
}
