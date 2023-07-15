<?php

namespace models;

use db\DB;

class User
{

    private $tablename = 'users';
    private $conn;
    function __construct(){
        $db = DB::getInstance();
        $this->conn = $db::getConnection();
    }

    public function getAll()
    {
        $result = $this->conn->query("SELECT * FROM {$this->tablename}");
        $all = [];
        while ($row = $result->fetch()) {
            $all[] = $row;
        }
        return $all;
    }

    public function find($id)
    {
        $result = $this->conn->prepare("SELECT * FROM {$this->tablename} WHERE {$this->tablename}.id = ?");
        $result->execute([167]);
        return $result->fetchAll($this->conn::FETCH_ASSOC);
    }

    public function findStatistics($id, $startDate, $endDate)
    {
        $result = $this->conn->prepare("SELECT *
            FROM users u
            LEFT JOIN statistics s ON u.id = s.staff
            LEFT JOIN rooms r ON s.room = r.id OR (s.work = 0 AND s.room IS NULL)
            LEFT JOIN prices p  ON (r.type = p.room_type AND s.work = p.work) OR (s.work = 0 AND p.id IS NULL)
            LEFT JOIN works w ON s.work = w.id OR (s.work = 0 AND w.id IS NULL)  
            WHERE u.id = ? 
            AND s.start > ?
            AND s.start < ?
            AND s.end > ?
            AND s.end < ?
");
        $result->execute([$id, $startDate, $endDate, $startDate, $endDate]);
        return $result->fetchAll($this->conn::FETCH_ASSOC);
    }

    public function findStatisticsByDay($id, $startDate, $endDate)
    {
        $result = $this->conn->prepare("SELECT *, b.name as build_name, r.type as room_type, w.name as work_type
            FROM users u
            LEFT JOIN statistics s ON u.id = s.staff
            LEFT JOIN rooms r ON s.room = r.id
            LEFT JOIN builds b ON r.build = b.id
            LEFT JOIN works w ON s.work = w.id
            LEFT JOIN prices p ON r.type = p.room_type
            WHERE u.id = ? 
            AND s.start > ?
            AND s.start < ?
            AND s.end > ?
            AND s.end < ?
            AND s.room != 0
");
        $result->execute([$id, $startDate, $endDate, $startDate, $endDate]);
        return $result->fetchAll($this->conn::FETCH_ASSOC);
    }

}