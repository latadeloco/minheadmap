<?php

namespace MinHeadmap\Report\Infrastructure\Delivery\Api;

use MinHeadmap\Report\Application\Query\FetchReportByDeviceQuery;
use MinHeadmap\Report\Application\Query\FetchReportByDeviceQueryHandler;
use MinHeadmap\Report\Application\Query\ReportDto;

class GetFetchReportByDeviceController {
    public function __construct(private FetchReportByDeviceQueryHandler $queryHandler)
    {
    }

    /** @return ReportDto[] */
    public function __invoke(string $device, int $page): array
    {
        return $this->queryHandler->handle(new FetchReportByDeviceQuery($device, $page));
    }
}