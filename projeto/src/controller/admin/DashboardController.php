<?php
require_once __DIR__ . '/../../model/admin/DashboardModel.php';

class DashboardController {
    private $model;

    public function __construct() {
        $this->model = new DashboardModel();
    }

    public function index() {
        // Sempre inicializa arrays vazios caso o model retorne null
        $stats = $this->model->getStats() ?? [];
        $activities = $this->model->getRecentActivities() ?? [];
        $topBooks = $this->model->getTopBooks() ?? [];

        return [
            'stats' => $stats,
            'activities' => $activities,
            'topBooks' => $topBooks
        ];
    }
}
