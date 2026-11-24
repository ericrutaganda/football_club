<?php
declare(strict_types=1);
class MatchModel {
    private PDO $db;
    public function __construct(){ $this->db=Database::connection(); }
    public function all(): array { return $this->db->query('SELECT * FROM matches ORDER BY match_date DESC')->fetchAll(); }
    public function find(int $id): ?array { $s=$this->db->prepare('SELECT * FROM matches WHERE id=?');$s->execute([$id]);return $s->fetch() ?: null; }
    public function save(array $d, ?int $id=null): void {
        if($id){$s=$this->db->prepare('UPDATE matches SET opponent=?,match_date=?,venue=?,competition=?,our_score=?,opponent_score=?,status=? WHERE id=?');$s->execute([$d['opponent'],$d['match_date'],$d['venue'],$d['competition'],$d['our_score'],$d['opponent_score'],$d['status'],$id]);}
        else{$s=$this->db->prepare('INSERT INTO matches(opponent,match_date,venue,competition,our_score,opponent_score,status) VALUES(?,?,?,?,?,?,?)');$s->execute([$d['opponent'],$d['match_date'],$d['venue'],$d['competition'],$d['our_score'],$d['opponent_score'],$d['status']]);}
    }
    public function delete(int $id): void{$s=$this->db->prepare('DELETE FROM matches WHERE id=?');$s->execute([$id]);}
}
