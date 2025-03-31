<?php

namespace MinHeadmap\Report\Infrastructure\Persistence\WP;

use MinHeadmap\Report\Domain\Device;
use MinHeadmap\Report\Domain\ReportRepository;
use UUID;

class WPReportRepository implements ReportRepository
{
    private $wpdb;
    private $table;
    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table = $this->wpdb->prefix . 'minheadmap_data';
    }


    public function fetchByDevice(Device $device, int $page): array
    {
        $total = $this->total($device);
        $offset = ($page - 1) * 10;
        $sql = <<<EOL
                SELECT * FROM {$this->table} 
                WHERE JSON_EXTRACT(data, '$.device') 
                    COLLATE utf8mb4_general_ci 
                    LIKE '%{$device->deviceName()}%' 
                LIMIT 10
                OFFSET {$offset}
        EOL;
        $results = $this->wpdb->get_results($sql);
        $formattedResults = [];
        foreach ($results as $row) {
            $dataArraydabled = json_decode($row->data, true);
            $data = [
                'sessionId' => UUID::fromBinary($row->session_id),
                'deviceName' => $dataArraydabled['device'],
                'event' => $dataArraydabled['event'],
                'timestamp' => $row->created_at,
                'screenWidth' => $dataArraydabled['screenX'],
                'screenHeight' => $dataArraydabled['screenY'],
                'coordX' => $dataArraydabled['coordX'],
                'coordY' => $dataArraydabled['coordY'],
            ];
            $formattedResults[] = $data;
        }

        return [
            'registers' => $formattedResults,
            'total' => $total
        ];
    }

    private function total(Device $device): int
    {
        $sql = "SELECT COUNT(*) as count 
                FROM {$this->table} 
                WHERE JSON_EXTRACT(data, '$.device') 
                          COLLATE utf8mb4_general_ci 
                          LIKE '%{$device->deviceName()}%'";
        $results = $this->wpdb->get_results($sql);
        return (int) $results[0]->count;
    }
}