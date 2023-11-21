<?php

namespace LaraCore\Framework\Db\FluidOrm\QueryBuilder;

use LaraCore\Framework\Db\FluidOrm\QueryBuilder\Exceptions\QueryBuilderInvalidArgumentException;

class QueryBuilder implements QueryBuilderInterface
{
  protected array $key;
  protected string $sqlQuery = '';

  protected const SQL_DEFAULT = [
    'conditions' => [],
    'selectors' => [],
    'replace' => false,
    'distinct' => false,
    'from' => [],
    'where' => null,
    'and' => [],
    'or' => [],
    'orderby' => [],
    'fields' => [],
    'primary_key' => '',
    'table' => '',
    'type' => '',
    'raw' => '',

    'table_join' => '',
    'join_key' => '',
    'join' => []
  ];

  protected const SQL_TYPES = [
    'insert',
    'update',
    'delete',
    'select',
    'row',
    'search'
  ];

  /**
   * Main constructor class
   * 
   * @return void
   */
  public function __construct()
  {
  }

  /**
   * @method buildQuery
   * @param array $args
   */
  public function buildQuery(array $args): self
  {
    if (count($args) < 0) {
      throw new QueryBuilderInvalidArgumentException('Invalid arguments. The arguments must be an array');
    }
    $arg = array_merge(self::SQL_DEFAULT, $args);
    $this->key = $arg;
    return $this;
  }

  /**
   * @method isQueryTypeValid
   * 
   * @return bool
   */
  public function isQueryTypeValid(string $type): bool
  {
    if (in_array($type, self::SQL_TYPES))
      return true;
    return false;
  }

  /**
   * @inheritDoc
   * 
   * @return string
   */
  public function insertQuery(): string
  {
    if ($this->isQueryTypeValid('insert')) {
      if (is_array($this->key['fields']) && count($this->key['fields']) > 0) {
        $index = array_keys($this->key['fields']);
        $values = array(implode(',', $index), ':' . implode(', :', $index));
        $this->sqlQuery = sprintf(
          'INSERT INTO %s (%s) VALUES (%s)',
          $this->key['table'],
          $values[0],
          $values[1]
        );
        return $this->sqlQuery;
      }
    }
    return false;
  }

  /**
   * @inheritDoc
   * 
   * @return string
   */
  public function selectQuery(): string
  {
    if ($this->isQueryTypeValid('select')) {
      $selector = !empty($this->key['selectors']) ? implode(', ', $this->key['selectors']) : '*';
      $this->sqlQuery = sprintf(
        'SELECT %s FROM %s',
        $selector,
        $this->key['table']
      );

      $this->sqlQuery .= $this->hasConditions();
      return $this->sqlQuery;
    }
    return false;
  }

  /**
   * @inheritDoc
   * 
   * @return string
   */
  public function updateQuery(): string
  {
    if ($this->isQueryTypeValid('update')) {
      $fields = $this->key['fields'];
      if (is_array($fields) && count($fields) > 0) {
        // $index = array_keys($fields);
        $queryStr = '';
        foreach ($fields as $field) {
          if ($field !== $this->key['primary_key']) {
            $queryStr .= sprintf('%s = :%s, ', $field, $field);
          }
        }

        $queryStr = rtrim($queryStr, ', ');
        $this->sqlQuery = sprintf(
          'UPDATE %s SET %s WHERE %s = :%s LIMIT 1',
          $this->key['table'],
          $queryStr,
          $this->key['primary_key'],
          $this->key['primary_key']
        );

        if (isset($this->key['primary_key']) && $this->key['primary_key'] === '0') {
          unset($this->key['primary_key']);
          $this->sqlQuery = sprintf(
            'UPDATE %s SET %s',
            $this->key['table'],
            $queryStr
          );
        }
        return $this->sqlQuery;
      }
    }
    return false;
  }

  /**
   * @inheritDoc
   * 
   * @return string
   */
  public function deleteQuery(): string
  {
    if ($this->isQueryTypeValid('delete')) {
      $index = array_keys($this->key['conditions']);
      $this->sqlQuery = sprintf(
        'DELETE FROM %s WHERE %s = :%s LIMIT 1',
        $this->key['table'],
        $index[0],
        $index[0]
      );
      $balkDelete = array_values($this->key['fields']);
      if (count($balkDelete) > 0) {
        $this->sqlQuery = sprintf(
          'DELETE FROM %s WHERE %s IN (%s)',
          $this->key['table'],
          $this->key['primary_key'],
          implode(', ', $balkDelete)
        );
      }
      return $this->sqlQuery;
    }
    return false;
  }

  private function hasConditions(): string
  {
    if (isset($this->key['conditions']) && !empty($this->key['conditions'])) {
      if (is_array($this->key['conditions'])) {
        $sort = [];
        foreach (array_keys($this->key['conditions']) as $whereKey => $where) {
          if (isset($where) && !empty($where)) {
            $sort[] = sprintf('%s = :%s', $where, $where);
          }
        }
        if (count($this->key['conditions']) > 0) {
          $this->sqlQuery .= ' WHERE ' . implode(' AND ', $sort);
        }
      }
    } else {
      $this->sqlQuery = ' WHERE 1';
    }
    if (isset($this->key['orderby']) && !empty($this->key['orderby'])) {
      $this->sqlQuery .= ' ORDER BY ' . $this->key['orderby'];
    }

    if (isset($this->key['limit']) && $this->key['offset'] !== 0) {
      $this->sqlQuery .= ' LIMIT ' . $this->key['limit'] . ' OFFSET ' . $this->key['offset'];
    } else if (isset($this->key['limit']) && $this->key['offset'] === 0) {
      $this->sqlQuery .= ' LIMIT ' . $this->key['limit'];
    }
    return $this->sqlQuery;
  }

  /**
   * @inheritDoc
   * 
   * @return string
   */
  public function rowQuery(): string
  {
    if ($this->isQueryTypeValid('raw')) {
      $this->sqlQuery = $this->key['raw'];

      return $this->sqlQuery;
    }
    return false;
  }

  /**
   * @inheritDoc
   * 
   * @return string
   */
  public function searchQuery(): string
  {
    if ($this->isQueryTypeValid('search ')) {
      if (is_array($this->key['selectors']) && $this->key['selectors'] != '') {
        $this->sqlQuery = "SELECT * FROM {$this->key['table']} WHERE ";
        if ($this->has('selectors')) {
          $values = [];
          foreach ($this->key['selectors'] as $selector) {
            $values[] = $selector . " LIKE " . "{$selector}";
          }
          if (count($this->key['selectors']) >= 1) {
            $this->sqlQuery .= implode(" OR ", $values);
          }
        }
        //$this->sqlQuery .= $this->orderByQuery();
        //$this->sqlQuery .= $this->queryOffset();
      }
      return $this->sqlQuery;
    }
    return false;
  }
  private function has($key): bool
  {
    return isset($this->key[$key]) && !empty($this->key[$key]);
  }
}
