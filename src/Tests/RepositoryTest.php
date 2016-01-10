<?php /** File containing test RepositoryTest */

/*
 * This file is part of the TeX Tools for PHP component.
 *
 * (c) Konstantin S. M. Möllers <ksm.moellers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CornyPhoenix\Tex\Tests;

use CornyPhoenix\Tex\FileFormat;
use CornyPhoenix\Tex\Jobs\Job;
use CornyPhoenix\Tex\Repositories\ExistingRepository;
use CornyPhoenix\Tex\Repositories\TemporaryRepository;

/**
 * A test for Repository logic.
 *
 * @package CornyPhoenix\Tex\Tests
 * @date 04.09.14
 * @author moellers
 */
class RepositoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Tests the functionality of temporary repositories.
     *
     * @test
     */
    public function testTemporaryRepositories()
    {
        $repository = new TemporaryRepository();

        // Assure temporary directory exists after creation
        $dir = $repository->getDirectory();
        $this->assertTrue(is_dir($dir), 'Repo dir doesn\'t exist');

        // Test job operations
        $job = $repository->createJob('');
        $this->assertInstanceOf(Job::class, $job, 'Repo did\'nt create a valid job');
        $this->assertTrue($repository->containsFile($job->getInputBasename()), 'Repo doesn\'t contain job input file');
        $this->assertEquals($repository->getDirectory(), $job->getDirectory(), 'Repo and job dir aren\'t the same');

        // Assure temporary directory is gone after destruction
        $repository->__destruct();
        $this->assertFalse(is_dir($dir), 'Repo dir still exists after destruction');
    }

    /**
     * Tests the functionality of existing repositories.
     *
     * @test
     */
    public function testExistingRepositories()
    {
        $dir = realpath(__DIR__ . '/../../data');
        $repository = new ExistingRepository($dir);

        $this->assertTrue($repository->containsFile('test.tex'));
        $this->assertCount(1, $repository->getJobs());
        $this->assertInstanceOf(Job::class, $job = $repository->findJob('test'));

        $this->assertContains(FileFormat::TEX, $repository->findFormatsByJob($job));
        $repository->clean();
        $this->assertContains(FileFormat::TEX, $repository->findFormatsByJob($job));
    }
}
