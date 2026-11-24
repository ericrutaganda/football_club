<?php
declare(strict_types=1);
class ApiController extends Controller {
    public function summary(): void {
        require_login(); $db=Database::connection();
        $this->json([
            'players'=>(int)$db->query('SELECT COUNT(*) FROM players')->fetchColumn(),
            'matches'=>(int)$db->query('SELECT COUNT(*) FROM matches')->fetchColumn(),
            'trainings'=>(int)$db->query('SELECT COUNT(*) FROM trainings')->fetchColumn()
        ]);
    }
}
