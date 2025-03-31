<?php

namespace MinHeadmap\Report\Domain;

class StandardDevice implements Device
{
    private function __construct(private string $deviceName) {

    }
    public static function fromString(string $device): Device
    {
        return new self($device);
    }
    public function deviceName(): string
    {
        return $this->deviceName;
    }
}