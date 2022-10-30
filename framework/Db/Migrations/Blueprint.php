<?php

/**
 * class for sql blueprint
 */

namespace MVC\Framework\Db\Migrations;

class Blueprint
{

  /**
   * @var array
   */
  protected $columns = [];

  /**
   * @var array
   */
  protected $indexes = [];

  /**
   * @var array
   */
  protected $increments = [];

  /**
   * @var array
   */
  protected $timestamps = [];

  /**
   * @var array
   */
  protected $uniques = [];

  /**
   * @var array
   */
  protected $foreignKeys = [];

  /**
   * @var array
   */
  protected $primaryKeys = [];

  /**
   * @var array
   */
  protected $dropColumns = [];

  /**
   * @var array
   */
  protected $dropIndexes = [];

  /**
   * @var array
   */
  protected $dropForeignKeys = [];

  /**
   * @var array
   */
  protected $dropPrimaryKeys = [];

  /**
   * @var array
   */
  protected $dropUniques = [];

  /**
   * @var array
   */
  protected $dropTimestamps = [];

  /**
   * @var array
   */
  protected $dropIncrements = [];

  /**
   * @var array
   */
  protected $renameColumns = [];

  /**
   * @var array
   */
  protected $renameIndexes = [];

  /**
   * @var array
   */
  protected $renameForeignKeys = [];

  /**
   * @var array
   */
  protected $renamePrimaryKeys = [];

  /**
   * @var array
   */
  protected $renameUniques = [];

  /**
   * @var array
   */
  protected $renameTimestamps = [];

  /**
   * @var array
   */
  protected $renameIncrements = [];

  /**
   * @var array
   */
  protected $changeColumns = [];

  /**
   * @var array
   */
  protected $changeIndexes = [];

  /**
   * @var array
   */
  protected $changeForeignKeys = [];

  /**
   * @var array
   */
  protected $changePrimaryKeys = [];

  /**
   * @var array
   */
  protected $changeUniques = [];

  /**
   * @var array
   */
  protected $changeTimestamps = [];

  /**
   * @var array
   */
  protected $changeIncrements = [];

  /**
   * @var array
   */
  protected $afterColumns = [];

  /**
   * @var array
   */
  protected $afterIndexes = [];

  /**
   * @var array
   */
  protected $afterForeignKeys = [];

  /**
   * @var array
   */
  protected $afterPrimaryKeys = [];

  /**
   * @var array
   */
  protected $afterUniques = [];

  /**
   * @var array
   */
  protected $afterTimestamps = [];

  /**
   * @var array
   */
  protected $afterIncrements = [];

  /**
   * @var array
   */

  protected $firstColumns = [];

  /**
   * @var array
   */
  protected $firstIndexes = [];

  /**
   * @var array
   */
  protected $firstForeignKeys = [];

  /**
   * @var array
   */
  protected $firstPrimaryKeys = [];

  /**
   * @var array
   */
  protected $firstUniques = [];

  /**
   * @var array
   */
  protected $firstTimestamps = [];

  /**
   * @var array
   */
  protected $firstIncrements = [];

  /**
   * @var array
   */
  protected $commentColumns = [];

  /**
   * @var array
   */
  protected $commentIndexes = [];

  /**
   * @var array
   */
  protected $commentForeignKeys = [];

  /**
   * @var array
   */
  protected $commentPrimaryKeys = [];

  /**
   * @var array
   */
  protected $commentUniques = [];

  /**
   * @var array
   */
  protected $commentTimestamps = [];

  /**
   * @var array
   */
  protected $commentIncrements = [];

  /**
   * @var array
   */
  protected $defaultColumns = [];

  /**
   * @var array
   */
  protected $defaultIndexes = [];

  /**
   * @var array
   */
  protected $defaultForeignKeys = [];

  /**
   * @var array
   */
  protected $defaultPrimaryKeys = [];

  /**
   * @var array
   */
  protected $defaultUniques = [];

  /**
   * @var array
   */
  protected $defaultTimestamps = [];

  /**
   * @var array
   */
  protected $defaultIncrements = [];

  /**
   * @var array
   */
  protected $nullableColumns = [];

  /**
   * @var array
   */
  protected $nullableIndexes = [];

  /**
   * @var array
   */
  protected $nullableForeignKeys = [];

  /**
   * @var array
   */
  protected $nullablePrimaryKeys = [];

  /**
   * @var array
   */
  protected $nullableUniques = [];

  /**
   * @var array
   */
  protected $nullableTimestamps = [];

  /**
   * @var array
   */
  protected $nullableIncrements = [];

  /**
   * @var array
   */
  protected $unsignedColumns = [];

  /**
   * @var array
   */
  protected $unsignedIndexes = [];

  /**
   * @var array
   */
  protected $unsignedForeignKeys = [];

  /**
   * @var array
   */
  protected $unsignedPrimaryKeys = [];

  /**
   * @var array
   */
  protected $unsignedUniques = [];

  /**
   * @var array
   */
  protected $unsignedTimestamps = [];

  /**
   * @var array
   */
  protected $unsignedIncrements = [];

  /**
   * @var array
   */
  protected $charsetColumns = [];

  /**
   * @var array
   */
  protected $charsetIndexes = [];


  /**
   * @var array
   */
  protected $charsetForeignKeys = [];

  /**
   * @var array
   */
  protected $charsetPrimaryKeys = [];

  /**
   * @var array
   */
  protected $charsetUniques = [];

  /**
   * @var array
   */
  protected $charsetTimestamps = [];

  /**
   * @var array
   */
  protected $charsetIncrements = [];

  /**
   * @var array
   */
  protected $collationColumns = [];

  /**
   * @var array
   */
  protected $collationIndexes = [];

  /**
   * @var array
   */
  protected $collationForeignKeys = [];

  /**
   * @var array
   */
  protected $collationPrimaryKeys = [];

  /**
   * @var array
   */
  protected $collationUniques = [];

  /**
   * @var array
   */
  protected $collationTimestamps = [];

  /**
   * @var array
   */
  protected $collationIncrements = [];

  /**
   * @var array
   */
  protected $storedColumns = [];

  /**
   * @var array
   */

  protected $storedIndexes = [];

  /**
   * @var array
   */
  protected $storedForeignKeys = [];

  /**
   * @var array
   */
  protected $storedPrimaryKeys = [];

  /**
   * @var array
   */
  protected $storedUniques = [];

  /**
   * @var array
   */
  protected $storedTimestamps = [];

  /**
   * @var array
   */
  protected $storedIncrements = [];

  /**
   * @var array
   */
  protected $virtualColumns = [];

  /**
   * @var array
   */
  protected $virtualIndexes = [];

  /**
   * @var array
   */
  protected $virtualForeignKeys = [];

  /**
   * @var array
   */
  protected $virtualPrimaryKeys = [];

  /**
   * @var array
   */
  protected $virtualUniques = [];

  /**
   * @var array
   */
  protected $virtualTimestamps = [];

  /**
   * @var array
   */
  protected $virtualIncrements = [];

  /**
   * @var array
   */
  protected $generatedColumns = [];

  /**
   * @var array
   */
  protected $generatedIndexes = [];

  /**
   * @var array
   */
  protected $generatedForeignKeys = [];

  /**
   * @var array
   */
  protected $generatedPrimaryKeys = [];
}
