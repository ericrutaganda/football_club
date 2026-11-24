<?php
declare(strict_types=1);
class Player {
    private PDO $db;
    public function __construct(){ $this->db = Database::connection(); }
    public function all(): array { return $this->db->query('SELECT * FROM players ORDER BY full_name')->fetchAll(); }
    public function find(int $id): ?array { $s=$this->db->prepare('SELECT * FROM players WHERE id=?');$s->execute([$id]);return $s->fetch() ?: null; }
    public function save(array $d, ?int $id=null): void {
        if ($id) { $s=$this->db->prepare('UPDATE players SET full_name=?,position=?,jersey_no=?,dob=?,phone=?,status=? WHERE id=?');$s->execute([$d['full_name'],$d['position'],$d['jersey_no'],$d['dob'] ?: null,$d['phone'],$d['status'],$id]);}
        else { $s=$this->db->prepare('INSERT INTO players(full_name,position,jersey_no,dob,phone,status) VALUES(?,?,?,?,?,?)');$s->execute([$d['full_name'],$d['position'],$d['jersey_no'],$d['dob'] ?: null,$d['phone'],$d['status']]);}
    }
    public function delete(int $id): void { $s=$this->db->prepare('DELETE FROM players WHERE id=?');$s->execute([$id]); }
}
