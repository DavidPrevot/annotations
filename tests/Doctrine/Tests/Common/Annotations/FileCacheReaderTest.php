<?php

namespace Doctrine\Tests\Common\Annotations;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\FileCacheReader;

class FileCacheReaderTest extends AbstractReaderTest
{
    private $cacheDir;

    protected function getReader()
    {
        $this->cacheDir = sys_get_temp_dir() . '/annotations_' . uniqid('', true);
        @mkdir($this->cacheDir);
        return new FileCacheReader(new AnnotationReader(), $this->cacheDir);
    }

    public function tearDown(): void
    {
        foreach (glob($this->cacheDir.'/*.php') AS $file) {
            unlink($file);
        }
        rmdir($this->cacheDir);
    }

    /**
     * @group DCOM-81
     */
    public function testAttemptToCreateAnnotationCacheDir()
    {
        $this->cacheDir = sys_get_temp_dir() . '/not_existed_dir_' . uniqid('', true);

        self::assertDirectoryNotExists($this->cacheDir);

        new FileCacheReader(new AnnotationReader(), $this->cacheDir);

        self::assertDirectoryExists($this->cacheDir);
    }
}
