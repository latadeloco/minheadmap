<?php

namespace MinHeadmap\Report\Domain;

interface ReportRepository {
    public function fetchByDevice(Device $device, int $page): array;
}