<?php

namespace App\Tests\Service;

use App\Service\ResourceAllocator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ResourceAllocatorTest extends KernelTestCase
{
    public function test_applications_are_allocated_to_virtual_hosts()
    {
        self::bootKernel();

        $applicationData = $this->getApplicationData();
        $serverData = $this->getServerData();

        $rescourceAllocator = new ResourceAllocator();
        $allocation = $rescourceAllocator->allocate($applicationData, $serverData);

        self::assertEquals($this->getExpectedAllocation(), $allocation);

    }

    public function getApplicationData()
    {
        return [
            "app_1" => [
                "cpu_core" => "2",
                "memory_gb" => "10",
                "network_bandwidth_mbps" => "10",
            ],
            "app_2" => [
                "cpu_core" => "5",
                "memory_gb" => "20",
                "network_bandwidth_mbps" => "30",
            ],
            "app_3" => [
                "cpu_core" => "3",
                "memory_gb" => "40",
                "network_bandwidth_mbps" => "60",
            ],
        ];


    }

    public function getServerData(): array
    {
        return [
            "cpu_core" => 50,
            "memory_gb" => 500,
            "network_bandwidth_mbps" => 400,
        ];
    }

    public function getExpectedAllocation()
    {
        return [
            "host_number_1" => [
                "app_1" => [
                    "cpu_core" => "2",
                    "memory_gb" => "10",
                    "network_bandwidth_mbps" => "10",
                ]
            ],
            "host_number_2" => [
                "app_2" => [
                    "cpu_core" => "5",
                    "memory_gb" => "20",
                    "network_bandwidth_mbps" => "30",
                ]
            ],
            "host_number_3" => [
                "app_3" => [
                    "cpu_core" => "3",
                    "memory_gb" => "40",
                    "network_bandwidth_mbps" => "60",
                ]
            ]
        ];
    }
}