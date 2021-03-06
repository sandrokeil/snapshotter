<?php
/*
 * This file is part of prooph/snapshotter.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Date: 11/03/15 - 06:18 PM
 */

namespace ProophTest\Snapshotter\Container;

use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase as TestCase;
use Prooph\EventStore\Aggregate\AggregateRepository;
use Prooph\EventStore\Snapshot\SnapshotStore;
use Prooph\Snapshotter\Container\SnapshotterFactory;
use Prooph\Snapshotter\Snapshotter;

/**
 * Class SnapshotterFactoryTest
 * @package ProophTest\Snapshotter\Container
 */
final class SnapshotterFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_snapshot_plugin()
    {
        $snapshotStore = $this->prophesize(SnapshotStore::class);
        $aggregateRepository = $this->prophesize(AggregateRepository::class);
        $container = $this->prophesize(ContainerInterface::class);

        $container->get('config')->willReturn([
            'prooph' => [
                'snapshotter' => [
                    'aggregate_repositories' => [
                        'foo' => AggregateRepository::class,
                    ]
                ]
            ]
        ]);
        $container->get(SnapshotStore::class)->willReturn($snapshotStore->reveal());
        $container->get(AggregateRepository::class)->willReturn($aggregateRepository->reveal());

        $factory = new SnapshotterFactory();
        $this->assertInstanceOf(Snapshotter::class, $factory($container->reveal()));
    }
}
