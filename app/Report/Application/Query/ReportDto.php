<?php

namespace MinHeadmap\Report\Application\Query;

use MinHeadmap\Report\Domain\Report;

class ReportDto
{
    private function __construct(private string $sessionId)
    {
    }

    public static function fromReport(Report $report): self
    {
        return new self('asdf');
    }
}