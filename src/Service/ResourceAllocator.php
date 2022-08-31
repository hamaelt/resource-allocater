<?php
declare(strict_types=1);

namespace App\Service;

class ResourceAllocator
{
    private int $hostCount = 1;

    private array $currentServerCapacity = [];

    private array $allocations = [];

    public function __construct()
    {
    }

    public function allocate(array $applicationData, array $serverData): array
    {
        $this->currentServerCapacity = $serverData;
        foreach ($applicationData as $appName => $singleAppData) {

            if ($this->checkServerHasCapacity($singleAppData)) {

                $this->allocations["host_number_" . $this->hostCount] = [
                    $appName => $singleAppData
                ];

                $this->updateServerCapacity($singleAppData);
            } else {
                throw new \Exception("Server has no capacity left for application : " . key($singleAppData));
            }
            $this->hostCount++;
        }
        return $this->allocations;
    }

    private function checkServerHasCapacity($singleAppData): bool
    {
        return $singleAppData['cpu_core'] < $this->currentServerCapacity['cpu_core'] &&
            $singleAppData['memory_gb'] < $this->currentServerCapacity['memory_gb'] &&
            $singleAppData['network_bandwidth_mbps'] < $this->currentServerCapacity['network_bandwidth_mbps'];
    }

    private function updateServerCapacity($singleAppData): void
    {
        $this->currentServerCapacity['cpu_core'] -= $singleAppData['cpu_core'];
        $this->currentServerCapacity['memory_gb'] -= $singleAppData['memory_gb'];
        $this->currentServerCapacity['network_bandwidth_mbps'] -= $singleAppData['network_bandwidth_mbps'];

    }

}
