<?php

namespace App\Core;

use PDO;
use PDOException;
use Exception;

abstract class Model {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $hidden = [];
    protected $timestamps = true;
    protected $attributes = [];

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function all($columns = ['*']) {
        $columns = is_array($columns) ? implode(', ', $columns) : $columns;
        $stmt = $this->db->query("SELECT {$columns} FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data) {
        $this->attributes = $data;
        
        if ($this->timestamps) {
            $this->attributes['created_at'] = date('Y-m-d H:i:s');
            $this->attributes['updated_at'] = date('Y-m-d H:i:s');
        }

        $columns = implode(', ', array_keys($this->attributes));
        $placeholders = ':' . implode(', :', array_keys($this->attributes));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($this->attributes);
            return $this->find($this->db->lastInsertId());
        } catch (PDOException $e) {
            throw new Exception("Error creating record: " . $e->getMessage());
        }
    }

    public function update($id, array $data) {
        $this->attributes = $data;
        
        if ($this->timestamps) {
            $this->attributes['updated_at'] = date('Y-m-d H:i:s');
        }

        $setClause = [];
        foreach (array_keys($this->attributes) as $key) {
            $setClause[] = "{$key} = :{$key}";
        }
        $setClause = implode(', ', $setClause);

        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = :id";
        $this->attributes['id'] = $id;

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($this->attributes);
            return $this->find($id);
        } catch (PDOException $e) {
            throw new Exception("Error updating record: " . $e->getMessage());
        }
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error deleting record: " . $e->getMessage());
        }
    }

    public function where($column, $operator, $value = null) {
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }

        $sql = "SELECT * FROM {$this->table} WHERE {$column} {$operator} :value";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':value', $value);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function firstWhere($column, $operator, $value = null) {
        $results = $this->where($column, $operator, $value);
        return !empty($results) ? $results[0] : null;
    }

    public function paginate($perPage = 15, $page = 1) {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM {$this->table} LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count() {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM {$this->table}");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];
    }

    public function with($relations) {
        // Implement eager loading
        return $this;
    }
}
