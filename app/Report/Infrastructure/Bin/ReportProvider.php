<?php

namespace MinHeadmap\Report\Infrastructure\Bin;


use MinHeadmap\Report\Application\Query\FetchReportByDeviceQueryHandler;
use MinHeadmap\Report\Infrastructure\Delivery\Api\GetFetchReportByDeviceController;
use MinHeadmap\Report\Infrastructure\Persistence\WP\WPReportRepository;

class ReportProvider {
    public function __construct()
    {
    }

    public function bind(): GetFetchReportByDeviceController
    {
        return new GetFetchReportByDeviceController(new FetchReportByDeviceQueryHandler(new WPReportRepository()));
    }
}