<?php

/**
 * Copyright (C) 2011 Mindplex Media, LLC.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
 * file except in compliance with the License. You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed
 * under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR
 * CONDITIONS OF ANY KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations under the License.
 */

/**
 * GenericActiveRecord in abstract class that contains methods that support the 
 * ActiveRecord data access pattern.
 *
 * @package mindplex-commons-activerecord
 * @author Abel Perez
 */
abstract class GenericActiveRecord
{
    /** entity identity */
    private $id;

    /** entity attributes */
    private $fields = array();

    /** flag that markes this entity as destroyed. */
    private $destroyed = false;

    /** default sort key that maps to an attribute of this object */
    private $sortKey = 'id';
	
	/** the name of this active record. */
	private $class;

    /*
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
       Generic Entity Operations
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     */

    /**
     * Initializes this entity with the specified attributes.
     */
    public function initialize($attributes) {
        if (! is_array($attributes)) return;
        $this->fields = $attributes;
		$this->class = get_class($this);
    }

    /**
     * Get's the value of the specified attribute.
     */
    public function __get($attribute) {
        if ($attribute === 'id') {
            return $this->id;

        } else {
            return $this->fields[$attribute];
        }
    }

    /**
     * Set's the value of the specified attribute.
     */
    public function __set($attribute, $value) {
        if ($attribute === 'id') {
            $this->id = $value;

        } else if (array_key_exists($attribute, $this->fields)) {
            $this->fields[$attribute] = $value;
        }
    }

    /**
     * Dynamically invokes the method on this entity that matches the specified 
     * method and passes the given arguments as the parameters of the method call.
     */
    public function __call($method, $arguments) {

        if (empty($arguments)) {
            return array();
        }

        if ($this->startsWith($method, 'findBy')) {
            $target = substr($method, 6);
            $column = $arguments[0];

            if (count($arguments) > 1) {
                $first = $arguments[1];
            } else {
                $first = false;
            }
            return $this->findByAttributeName($target, $column, $first);
        }
        return array();
    }

    /**
     * Get's this entity's attributes.
     */
    public function getAttributes() {
        return $this->fields;
    }

    /**
     * Set's this entity's attributes.
     */
    public function setAttributes($fields) {
        if ($fields == null) return;
        $this->fields = $fields;
    }

    /**
     * Merge the specified attributes to the internal attributes of this active 
     * record.
     *
     * @attributes array attributes to merge into this active record.
     */
    public function mergeAttributes($attributes) {
        $this->fields = array_merge($this->fields, $attributes);
    }

    /**
     * Get's this entity's database connection.
     */
    public function connection() {
        return DataAccess::getInstance();
    }

    /**
     * Executes the specified query against the underlying data store.
     *
     * Note: this method does not throw a custom DataAccessException,  because 
     * of this, direct access from business logic code is not recommended.
     * The recommended pattern is to wrap calls to this method that catch any 
     * exceptions and throw a csutom DataAccessException.
     *
     * @returns associative array of field/value that makes to the table 
     * being queried.
     */
    public function query($query) {
        return $this->connection()->query($query);
    }

    /**
     * Map's the specified database result row to this entity's attributes.
     */
    public abstract function mapper($row);

    /*
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
       Select Operations
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     */

    /**
     * Finds an entity based on the specified identity id.
     */
    public function find($id) {
        try {
            $class = get_class($this);
            $result = $this->connection()->query(sprintf('select * from %s where id = %d', $class, $id));

            foreach ($result as $row) {
                return $this->mapper($row);
            }

        } catch (Exception $exception) {
            $message = sprintf('Failed to get %s by id: %d.', $class, $id);
            throw new DataAccessException($message, 100, $exception);
        }
        throw new DataAccessException(
                sprintf('Failed to find record with id: %s', $id), 100, $exception);
    }

    /**
     * Finds all the entities that match this entity's type. 
     */
    public function findAll() {
        try {
            $entities = array();
            $class = get_class($this);
            $result = $this->connection()->query(sprintf('select * from %s', $class));

            foreach ($result as $row) {
                $entity = $this->mapper($row);
                $entities[] = $entity;
            }
            return $entities;

        } catch (Exception $exception) {
            throw new DataAccessException('Failed to get entities.', 101, $exception);
        }
    }

    /**
     * Get's a list of entities that have been created between the specified 
     * date range.
     */
    public function dateRange($start, $end, $attribute = 'created') {
        try {
            $entities = array();
            $class = get_class($this);
            $result = $this->connection()->query(
                    sprintf("select * from %s where %s between '%s 00:00:00' and '%s 23:59:59'",
                            $class, $attribute, $start, $end));

            foreach ($result as $row) {
                $entity = $this->mapper($row);
                $entities[] = $entity;
            }
            return $entities;

        } catch (Exception $exception) {
            $message = sprintf('Failed to get entities by specified date range start: %s and end: %s.',
                    $start, $end);
            throw new DataAccessException($message, 103, $exception);
        }
    }

    /*
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
       Entity Association Operations
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     */

    /**
     * Get's the One-To-One association between this entity and the specified
     * entity and identity id.
     */
    public function hasOne($class, $id) {
        $parent = get_class($this);
        $child = new $class();
        return $child->findByAttributeName($parent.'id', $id, true);
    }

    /**
     * Get's the One-To-Many association between this entity and the specified
     * entity and identity id.
     */
    public function hasMany($class, $id) {
        $parent = get_class($this);
        $child = new $class();
        return $child->findByAttributeName($parent.'id', $id);
    }

    /**
     * Get's the Belongs-To association between this entity and the specified
     * entity and identity id.
     */
    public function belongsTo($class, $id) {
        $parent = new $class();
        return $parent->findByAttributeName('id', $id, true);
    }

    /**
     * Get's the Many-To-Many association between this entity and the specified
     * entity and identity id.
     */
    public function hasAndBelongsToMany($class, $id) {
        try {
            $entities = array();

            $target = get_class($this);
            $join = array($class, $target);
            sort($join);

            $result = $this->connection()->query(
                    sprintf('select a.* from %s a inner join %s%s b on a.id = b.%sid where b.%sid = %d',
                            $class, $join[0], $join[1], $class, $target, $id));

            $model = new $class();
            foreach ($result as $row) {
                $entity = $model->mapper($row);
                $entities[] = $entity;
            }
            return $entities;

        } catch (Exception $exception) {
            throw new DataAccessException('Failed to get entities.', 105, $exception);
        }
    }

    /**
     * Not fully implemented (use is not recommended).
     */
    public function tree($class, $id) {
        $parent = get_class($this);
        $child = new $class();
        return $child->findByAttributeName($parent.'id', $id);
    }

    /**
     * True if this entity does not contain the  specified entity or if it 
     * is contained but null or empty.
     */
    public function isBlank($attribute) {
        if (! array_key_exists($attribute, $this->fields)) return true;

        $value = $this->fields[$attribute];
        return ($value === NULL OR $value === '') ? TRUE : FALSE;
    }

    /**
     * Finds the records that match the specified attribute and match value. 
     */
    public function findByAttributeName($attribute, $match, $first = false) {
        try {
            $entities = array();
            $class = get_class($this);
            $fragment = (is_numeric($match)) ? $match : "'".$match."'";
            $result = $this->connection()->query(sprintf(
                    'select * from %s where %s = %s', $class, $attribute, $fragment));

            foreach ($result as $row) {
                $entity = $this->mapper($row);

                if ($first) {
                    return $entity;
                }
                $entities[] = $entity;
            }
            return $entities;

        } catch (Exception $exception) {
            $message = sprintf('Failed to find %s from attribute: %s by match: %s.', $class, $attribute, $match);
            throw new DataAccessException($message, 104, $exception);
        }
    }

    /**
     * Finds the records that match the specified attributes and match values.
     *
     * @param $attributes array 
     */
    public function findByAttributeNames($attributes, $first = false) {
        try {
            $entities = array();
            $class = get_class($this);

            if (!is_array($attributes)) {
                return $entities;
            }

            if (count($attributes) == 0) {
                return $entities;
            }

            $fragment = '';
            foreach ($attributes as $key => $value) {
                $targetValue = (is_numeric($value)) ? $value : "'".$value."'";
                $fragment .= sprintf(' %s = %s AND', $key, $targetValue);
            }
            $fragment = $this->trimLastCharacter($fragment, -3);
            $result = $this->connection()->query(sprintf('select * from %s where %s ', $class, $fragment));

            foreach ($result as $row) {
                $entity = $this->mapper($row);

                if ($first) {
                    return $entity;
                }
                $entities[] = $entity;
            }
            return $entities;

        } catch (Exception $exception) {
            $message = sprintf('Failed to find $s from attribute.', $class);
            throw new DataAccessException($message, 104, $exception);
        }
    }

    /**
     * Toggle's the value of the specified attribute.
     *
     * @param attribute the attributes that will have it's value toggled.
     */
    public function toggle($attribute) {
        if (array_key_exists($attribute, $this->fields)) {
            $item = $this->fields[$attribute];
            if (isset($item)) {
                $this->fields[$attribute] = ($item == TRUE) ? 0 : 1;
            }
        }
    }

    /**
     * Reloads this Patient from the database based on the current id.
     */
    public function reload() {
        try {
            $class = get_class($this);
            $result = $this->connection()->query(
                    sprintf('select * from %s where id = %d', $class, $this->id));

            foreach ($result as $row) {
                // TODO: complete this method.
                // $this->mapper($row);
            }

        } catch (Exception $exception) {
            $message = sprintf('Failed to reload: %s by id: %d.', 'Patient', $this->id);
            throw new DataAccessException($message, 106, $exception);
        }
    }

    /**
     * Asigns the specified column/value to a fragment of a SQL UPDATE 
     * statement. 
     *
     * @param string $column the name of the column to update.
     * @param string|integer $value the value of the column to update.
     *
     * @return string 
     */
    private function assignUpdateSemantics($column, $value) {
        return is_string($value) ? $column.'="'.$value.'",' : $column.'='.$value.',';	
    }

    /**
     * Asigns the specified value to a fragment of a SQL INSERT statement. 
     *
     * @param string|integer $value the value of the column to update.
     *
     * @return string 
     */
    private function assignInsertSemantics($value) {
        return is_string($value) ? '"'.$value.'",' : $value.',';
    }

    /**
     * Saves this entity if its a brand new instance that does not exist 
     * in the database; otherwise updates this entity in the database by 
     * flushing all its attributes directly to the database.
     */
    public function saveOrUpdate() {
        try {

            // if this entity has been destroyed then we cannot 
            // assert the validaty of this instance identity
            // so we simply ignore any persistence operations.

            if ($this->isDestroyed() == true) return;

            // avoid getting fields array directly, in case a 
            // sub-class of this object overrides the default
            // behavior for listing this objects fields.

            $fields = $this->getAttributes();

            if ($this->id) {

                // since this is an update operation we
                // update the modification timestamps.

                if (array_key_exists('modified', $fields)) {
                    $this->modified = $this->getDate();
                }
                if (array_key_exists('modifiedid', $fields)) {
                    $this->modifiedid = time();	
                }

                $sql = 'UPDATE '.$this->class.' SET ';

                // attributes to update
                foreach ($this->fields as $k => $v) {
                    $sql .= $this->assignUpdateSemantics($k, $v);
                }

                // constraint update to this instance identity.
                $sql = rtrim($sql, ',').' WHERE id = '.$this->id;
                DataAccess::getInstance()->query($sql);

            } else {

                // since this is an update operation we
                // update the modification timestamps.
				
                if (array_key_exists('created', $fields) && ($fields['created'] == '')) {
                    $this->created = $this->getDate();	
                }
                if (array_key_exists('createdid', $fields) && ($fields['createdid'] == '')) {
                    $this->createdid = time();	
                }

                $sql = 'INSERT INTO '.$this->class.' (';
                $values = '';

                // values to insert
                foreach ($this->fields as $k => $v) {
                    $sql .= $k.',';	
                    $values .= $this->assignInsertSemantics($v);	
                }

                $sql = rtrim($sql, ',').') VALUES ('.rtrim($values, ',').')';

                DataAccess::getInstance()->query($sql);
                $this->id = DataAccess::getInstance()->lastInsertId();
            }

        } catch (Exception $exception) {
            throw new Exception('Failed to complete save or update.');
        }
    }

    /**
     * Deletes this entity.
     */
    public function delete() {
        try {
            $class = get_class($this);
            $this->connection()->query(sprintf('delete from %s where id = %d', $class, $this->id));
            $this->id = null;

        } catch (Exception $exception) {
            $message = sprintf('Failed to delete %s by id: %d.', $class, $this->id);
            throw new DataAccessException($message, 106, $exception);
        }
    }

    /**
     * Delete the entity associated with the specified identity id.
     */
    public function deleteById($id) {
        try {
            $class = get_class($this);
            $this->connection()->query(sprintf('delete from %s where id = %d', $class, $id));

        } catch (Exception $exception) {
            $message = sprintf('Failed to delete %s by id: %d.', $class, $id);
            throw new DataAccessException($message, 106, $exception);
        }
    }

    /**
     * Deletes all entities based on the specified Entity ids.
     */
    public function deleteAll($ids) {
        try {
            if (!is_numeric($ids) && !is_array($ids) && empty($ids)) {
                return array();
            }

            if (empty($ids)) {
                return array();
            }

            $items = '(';
            if (is_array($ids)) {
                foreach ($ids as $item) {
                    $items .= $item . ',';
                }
                $items = $this->trimLastCharacter($items);
            } else {
                $items .= $ids;
            }
            $items .= ')';

            $entities = array();
            $result = $this->connection()->query(
                    sprintf('delete from %s where id in $s', $class, $items));

        } catch (Exception $exception) {
            throw new DataAccessException('Failed to delete entities.', 106, $exception);
        }
    }

    /*
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
       Aggregate Operations
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     */

    /**
     * Executes the specified aggregate function query.
     */
    public function aggregate($query) {
        $result = $this->connection()->query($query);

        foreach ($result as $row) {
            return $row['result'];
        }
        return 0;
    }

    /**
     * Get's sum of the specified attribute.
     */
    public function sum($attribute) {
        try {
            $class = get_class($this);
            return $this->aggregate(sprintf('select sum(%s) as result from %s', $attribute, $class));

        } catch (Exception $exception) {
            $message = sprintf('Failed to get sum of %s.', $attribute);
            throw new DataAccessException($message, 107, $exception);
        }
    }

    /**
     * Get's minimum value of the specified attribute.
     */
    public function min($attribute) {
        try {
            $class = get_class($this);
            return $this->aggregate(sprintf('select min(%s) as result from %s', $attribute, $class));

        } catch (Exception $exception) {
            $message = sprintf('Failed to get min of %s.', $attribute);
            throw new DataAccessException($message, 107, $exception);
        }
    }

    /**
     * Get's maximum value of the specified attribute.
     */
    public function max($attribute) {
        try {
            $class = get_class($this);
            return $this->aggregate(sprintf('select max(%s) as result from %s', $attribute, $class));

        } catch (Exception $exception) {
            $message = sprintf('Failed to get max of %s.', $attribute);
            throw new DataAccessException($message, 107, $exception);
        }
    }

    /**
     * Get's count of entities in the database.
     */
    public function count() {
        try {
            $class = get_class($this);
            return $this->aggregate(sprintf('select count(*) as result from %s', $class));

        } catch (Exception $exception) {
            $message = sprintf('Failed to get %s count.', $class);
            throw new DataAccessException($message, 107, $exception);
        }
    }

    /**
     * Destroys this entity and freezes its persistence state. Attributes 
     * will continue to be available once this Patient has been destroyed 
     * but you will not be able to persist any changes made to it's attributes. 
     */
    public function destroy() {
        try {
            $class = get_class($this);
            $this->connection()->query(sprintf('delete from %s where id = %d', $class, $this->id));
            $this->destroyed == true;

        } catch (Exception $exception) {
            $message = sprintf('Failed to destroy %s by id: %d.', $class, $this->id);
            throw new DataAccessException($message, 106, $exception);
        }
    }

    /**
     * indicates wether this Entity has been destroyed or not.
     */
    public function isDestroyed() {
        return $this->destroyed;
    }

    /**
     * Creates a new copy instance of this Patient that is not associate with 
     * any record in the database.  
     */
    public function createClone() {
        $class = get_class($this);
        $entity = new $class();
        $entity->__initAttributes($this->fields);
        return $entity;
    }

    /**
     * Initializes this Patient attributes from the specified attribute array. 
     */
    private function __initAttributes($attributes) {
        if (is_array($attributes)) {
            $this->fields = $attributes;
        }
    }

    /*
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
       Metadata Operations
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     */

    /**
     * Checks if this Entity has the specified property.
     */
    public function hasAttribute($attribute) {
        return array_key_exists($attribute, $this->fields);
    }

    /**
     * Gets the attribute names for this Entity. 
     */
    public function getAttributeNames() {
        $result = array();
        $result[] = 'id';
        foreach($this->fields as $key => $value) {
            $result[] = $key;
        }
        return $result;
    }

    /**
     * Trims the last character from the specified target. 
     */
    private function trimLastCharacter($target, $index = -1) {
        if (isset($target)) {
            return substr_replace($target ,"", $index);

        } else {
            return $target;
        }
    }

    /*
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
       Predicate Operations
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     */

    /**
     * Checks if this Entity exists in the database based on the specified id.
     */
    public function exists($id) {
        try {
            $result = $this->connection()->query(
                    sprintf("select count(*) as 'Count' from %s where Id = %d", $class, $id));

            foreach ($result as $row) {
                $count = $row['Count'];
                if ($count > 0) {
                    return TRUE;
                }
                break;
            }
            return FALSE;

        } catch (Exception $exception) {
            $message = sprintf('Failed to check if %s exists by Id: %d.', $class, $id);
            throw new DataAccessException($message, 108, $exception);
        }
    }

    /**
     * Checks if the specified attribute is set.
     */
    public function isAttributeSet($attribute) {
        if ($attribute == 'id') {
            return ($this->id == null) ? FALSE : TRUE;
        }
        if (array_key_exists($attribute, $this->fields)) {
            $value = $this->fields[$attribute];
            return ($value == null || $value == ''  || empty($value)) ? FALSE : TRUE;
        }
        return FALSE;
    }

    /**
     * Finds all the records that match the specified set of id's.
     */
    public function findIn($ids) {
        try {
            $class = get_class($this);
            if (!is_numeric($ids) && !is_array($ids) && empty($ids)) {
                return array();
            }

            if (empty($ids)) {
                return array();
            }

            $items = '(';
            if (is_array($ids)) {
                foreach ($ids as $item) {
                    $items .= $item . ',';
                }
                $items = $this->trimLastCharacter($items);
            } else {
                $items .= $ids;
            }
            $items .= ')';

            $entities = array();
            $result = $this->connection()->query(sprintf(
                    'select * from %s where id in $s', $class, $items));

            foreach ($result as $row) {
                $entity = $this->mapper($row);
                $entities[] = $entity;
            }

            return $entities;

        } catch (Exception $exception) {
            throw new DataAccessException('Failed to get entities.', 109, $exception);
        }
    }

    /**
     * Get's the count of rows found in the previous executed sql statement.  
     * This function is useful for pagination relatedoperations where the 
     * previous executed sql statement contained a limit clause.
     *
     * Note: this is a MySQL specific operation and depends on the previous 
     * execution of a sql statement that contains the SQL_CALC_FOUND_ROWS 
     * keyword in the select statemet.
     *
     * For example, using the query function of this active record, the previous 
     * query to the invocation of this function should be something like this:
     *
     * $dao->query(select SQL_CALC_FOUND_ROWS id, name from SomeTable);
     *
     * @return the count of rows found in the previous executed sql statement.
     */
    public function found() {
        $result = $this->query('select FOUND_ROWS() as Found');
        foreach ($result as $row) {
            return $row['Found'];
        }
        return 0;
    }

    /*
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
       Query Operator and Builder Operations
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     */

    /**
     * Builds a sql select all expression based on this entity type e.g., 
     * select * from Entity.
     *
     * @param $filter list of columns to exclude from the select statement.
     *
     * @return a sql select * expression based on this entity.
     */
    public function selectAllExpression($filter = array()) {
        if (empty($filter)) {
            $filter = $this->getAttributeNames();
        }
        $class = get_class($this);
        $select = 'SELECT SQL_CALC_FOUND_ROWS ';
        foreach ($filter as $attribute) {
            $select .= $attribute.', ';
        }
        $select = substr_replace($select, "", -2);
        $select .= ' FROM %s ';
        return sprintf($select, $class);
    }

    /**
     * Searches for the specified keyword in the given attributes of this 
     * entity based on the specified logic gate (AND/OR).
     *
     * @param $keyword the search term to look for
     * @param $attributes the attributes to search in
     * @param $gate the logic gate to use in the LIKE clauses.
     *
     * @return the search results of the given keyword and attributes.
     */
    public function like($keyword, $attributes = array(), $gate = 'AND') {
        try {
            $like = ' WHERE '.$this->likeExpression($keyword, $attributes, $gate);

            $entities = array();
            $class = get_class($this);
            $query = sprintf('select * from %s', $class).$like;
            $result = $this->connection()->query($query);

            foreach ($result as $row) {
                $entity = $this->mapper($row);
                $entities[] = $entity;
            }
            return $entities;

        } catch (Exception $exception) {
            throw new DataAccessException('Failed to search this entity.', 101, $exception);
        }
    }

    /**
     * Builds a sql LIKE statement based on the specified keyword as the search 
     * criteria against the given attributes of this entity and the specified logic 
     * gate i.e., AND/OR.  
     *
     * For example, given the keyword "Car" and the attributes "name", "description", 
     * the resulting sql LIKE statememt will be: name LIKE '%Car%' OR description 
     * LIKE '%Car%'.
     *
     * @param $keyword the search term to look for.
     * @param $attributes the attributes to search in.
     * @param $gate the logic gate to use in the LIKE clauses.
     *
     * @return sql LIKE statement based on the given keyword, attributes, and gate.
     */
    public function likeExpression($keyword, $attributes = array(), $gate = 'AND') {
        try {
            $like = " ";

            if (! empty($attributes)) {
                foreach ($attributes as $attribute) {
                    $like .= $attribute." LIKE '%".$keyword."%' ".$gate." ";
                }
                $like = substr_replace($like, "", -4);
            }
            return $like." ";

        } catch (Exception $exception) {
            throw new DataAccessException('Failed to build sql lIKE clause.', 101, $exception);
        }
    }

    /**
     * Builds a sql "Order By" expression based on the specified attributes and 
     * sort direction.
     *
     * This operation has two distinct behaviors based on the specified compound 
     * flag.  If ordering is not compound then the specified attributes will be 
     * ordered by the specified direction; otherwise, the direction for each
     * attribute will be sorted in the direction specified in each attribute's 
     * key/value pair.
     *
     * @param $attributes the column/sort direction pairs.
     * @param $direction the sort direction when ordering is not compound.
     * @param $compound flag that hints ordering should be compound of all 
     * specified attribute/sore-direction pairs.
     *
     * @return sql Order By statement.
     */
    public function orderByExpression($attributes = array(), $direction = 'ASC', $compound = FALSE) {
        try {
            if (empty($attributes)) return '';

            $order = ' ORDER BY ';

            if (! $compound) {
                foreach ($attributes as $attribute) {
                    $order .= $attribute.', ';
                }

                $order = substr_replace($order, '', -2);
                $order .= ' DESC';

            } else {
                foreach ($attributes as $key => $value) {
                    $order .= $key.' '.$value.', ';
                }

                $order = substr_replace($order, '', -2);
            }

            return $order;

        } catch (Exception $exception) {
            throw new DataAccessException('Failed to build "order by" statement.', 101, $exception);
        }
    }

    /**
     * Builds a sql limit clause based on the specified start and limit values.
     *
     * The default limit range is 0 - 10.
     *
     * @param $start the start position of the limit range.
     * @param $limit the limit count of the limit range.
     *
     * @return sql LIMIT expression based on the specified start and limit range.
     */
    public function limitExpression($start = 0, $limit = 10) {
        return " LIMIT ".$start.", ".$limit;
    }

    /**
     * Get's a filtered version of this entity's attributes based on the specified 
     * attributes to exclude.
     *
     * @param $filter the list of attributes to filter out.
     *
     * @return a filtered version of this entity's attributes based on the 
     * specified attributes to exclude.
     */
    public function filterAttributes($filter = array()) {
        $attributes = $this->getAttributeNames();
        return array_values(array_diff($attributes, $filter));
    }

    /*
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
       Sorting Operations
      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     */

    /**
     * Set's the sort key.
     *
     * @param string $sortKey sort key.
     */
    public function setSortKey($sortKey = 'id') {
        $this->sortKey = $sortKey;
    }

    /**
     * Compare method called when comparing keys in a sort operation.
     *
     * @param string $left key to compare to the specified right key.
     * @param string $right key to compare to the specified left key.
     */
    public function compare($left, $right) {
        $k = $this->sortKey;
        if ($left->$k == $right->$k) {
            return 0 ;
        }
        return ($left->$k < $right->$k) ? -1 : 1;
    }

    /**
     * Compare method called when comparing keys in a sort operation.
     *
     * @param string $left key to compare to the specified right key.
     * @param string $right key to compare to the specified left key.
     */
    public function reverse($left, $right) {
        $k = $this->sortKey;
        if ($left->$k == $right->$k) {
            return 0 ;
        }
        return ($left->$k > $right->$k) ? -1 : 1;
    }

    /**
     * Sort's the specified entity array with the specified sort key.
     *
     * @param array $entities the entity array to sort.
     * @param string $sortKey the sort key to use while sorting the 
     * given entity array.
     */
    public function sort(&$entities, $key) {
        $this->setSortKey($key);
        usort($entities, array($this, 'compare'));
    }

    /**
     * Sort's the specified entity array with the specified sort key 
     * in reverse order.
     *
     * @param array $entities the entity array to sort.
     * @param string $sortKey the sort key to use while  sorting the 
     * given entity array.
     */
    public function reverseSort(&$entities, $key) {
        $this->setSortKey($key);
        usort($entities, array($this, 'reverse'));
    }

    /**
     * Get's the current date in 12 hour format. TODO: this should be a 
     * protected method.
     *
     * @return date	the current date in 12 hour format.
     */
    public function getDate() {
        return date('Y-m-d h:i:s');
    }

    /**
     * The xml representation of this entity.
     */
    public function xml($declaration = true) {
        $class = get_class($this);

        if ($declaration) {
            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        }
        $xml .= '<'.$class.'>';

        foreach ($this->getAttributeNames() as $attribute) {
            $xml .= '<'.$attribute.'>'.$this->$attribute.'</'.$attribute.'>';
        }

        $xml .= '</'.$class.'>';
        return $xml;
    }

    /**
     * The string representation of this entity.
     */
    public function toString() {
        $result = '';

        foreach ($this->getAttributeNames() as $attribute) {
            $result .= $attribute.' = '.$this->$attribute.', ';
        }
        return $result;
    }

    /**
     * Checks if the specified item starts with the given string.
     */
    private function startsWith($haystack, $needle){
        return strpos($haystack, $needle) === 0;
    }
}

?>