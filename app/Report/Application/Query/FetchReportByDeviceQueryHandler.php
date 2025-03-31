<?php

namespace MinHeadmap\Report\Application\Query;

use MinHeadmap\Report\Domain\ReportRepository;
use MinHeadmap\Report\Domain\StandardDevice;

class FetchReportByDeviceQueryHandler {
    public function __construct(
        private ReportRepository $repository
    )
    {
    }

    /**
     * @return ReportDto[]
     */
    public function handle(FetchReportByDeviceQuery $query): array
    {
        $device = StandardDevice::fromString($query->device);
        return $this->repository->fetchByDevice($device, $query->page);
    }
}