<?php

namespace MinHeadmap\Report\Application\Query;

class FetchReportByDeviceQuery {
    public function __construct(
        public string $device,
        public int $page,
    )
    {
    }
}