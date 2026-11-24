<?php
declare(strict_types=1);
class Training {
    private PDO $db;
    public function __construct(){ $this->db=Database::connection(); }
    public function all(): array{return $this->db->query('SELECT * FROM trainings ORDER BY training_date DESC')->fetchAll();}
    public function find(int $id): ?array{$s=$this->db->prepare('SELECT * FROM trainings WHERE id=?');$s->execute([$id]);return $s->fetch() ?: null;}
    public function save(array $d, ?int $id=null): void{
        if($id){$s=$this->db->prepare('UPDATE trainings SET title=?,training_date=?,location=?,coach=?,notes=? WHERE id=?');$s->execute([$d['title'],$d['training_date'],$d['location'],$d['coach'],$d['notes'],$id]);}
        else{$s=$this->db->prepare('INSERT INTO trainings(title,training_date,location,coach,notes) VALUES(?,?,?,?,?)');$s->execute([$d['title'],$d['training_date'],$d['location'],$d['coach'],$d['notes']]);}
    }
    public function delete(int $id): void{$s=$this->db->prepare('DELETE FROM trainings WHERE id=?');$s->execute([$id]);}
}
