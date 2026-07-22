<?php

declare(strict_types=1);

namespace Serixa\Zip;

use Serixa\Zip\Exceptions\ZipException;
use ZipArchive;

final class ZipArchiveBuilder
{
    /** @var array<string, string> path => contents */
    private array $files = [];

    public static function make(): self
    {
        return new self();
    }

    public function addFromString(string $path, string $contents): self
    {
        $this->files[ltrim(str_replace('\\', '/', $path), '/')] = $contents;

        return $this;
    }

    public function save(string $destination): void
    {
        if (! class_exists(ZipArchive::class)) {
            throw ZipException::extensionMissing();
        }
        $zip = new ZipArchive();
        if ($zip->open($destination, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw ZipException::openFailed($destination);
        }
        foreach ($this->files as $path => $contents) {
            $zip->addFromString($path, $contents);
        }
        $zip->close();
    }

    /**
     * @return array<string, string>
     */
    public function extractToArray(string $archivePath): array
    {
        if (! class_exists(ZipArchive::class)) {
            throw ZipException::extensionMissing();
        }
        $zip = new ZipArchive();
        if ($zip->open($archivePath) !== true) {
            throw ZipException::openFailed($archivePath);
        }
        $out = [];
        for ($i = 0; $i < $zip->numFiles; ++$i) {
            $name = $zip->getNameIndex($i);
            if ($name === false || str_ends_with($name, '/')) {
                continue;
            }
            $data = $zip->getFromIndex($i);
            if ($data !== false) {
                $out[$name] = $data;
            }
        }
        $zip->close();

        return $out;
    }
}
