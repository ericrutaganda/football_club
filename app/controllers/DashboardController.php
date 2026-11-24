<?php
declare(strict_types=1);
class DashboardController extends Controller {
    public function index(): void {
        require_login(); $db=Database::connection();
        $stats=[
            'players'=>(int)$db->query('SELECT COUNT(*) FROM players')->fetchColumn(),
            'active'=>(int)$db->query("SELECT COUNT(*) FROM players WHERE status='active'")->fetchColumn(),
            'matches'=>(int)$db->query("SELECT COUNT(*) FROM matches WHERE status='scheduled'")->fetchColumn(),
            'trainings'=>(int)$db->query('SELECT COUNT(*) FROM trainings')->fetchColumn()
        ];
        $upcoming=$db->query("SELECT * FROM matches WHERE match_date >= NOW() AND status='scheduled' ORDER BY match_date LIMIT 5")->fetchAll();
        $this->view('dashboard/index',['title'=>'Dashboard','stats'=>$stats,'upcoming'=>$upcoming]);
    }
}
