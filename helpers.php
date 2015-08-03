<?php

namespace Flysap\Support;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use ZipArchive;

/**
 * Check if file has extension .
 *
 * @param $file
 * @param $extension
 * @return bool
 */
function has_extension($file, $extension) {
    return $extension == get_file_extension($file);
}

/**
 * Get file extension .
 *
 * @param $file
 * @return mixed
 */
function get_file_extension($file) {
    $file = pathinfo($file);

    return $file['extension'];
}

/**
 * Check if specific path is empty
 *
 * @param $path
 * @return bool
 */
function is_folder_empty($path) {
    list($filesystem, $finder) = [new Filesystem(), new Finder()];

    if( ! $filesystem->exists($path) )
        return true;

    $finder->files()->in($path);

    $fileExists = false;

    foreach ($finder as $file)
        $fileExists = true;

    return $fileExists;
}

/**
 * Check if path exists .
 *
 * @param $path
 * @return mixed
 */
function is_path_exists($path) {
    list($filesystem) = [new Filesystem()];

    return $filesystem
        ->exists($path);
}

/**
 * Remove path ..
 *
 * @param array $paths
 * @return bool
 */
function remove_paths($paths = array()) {
    list($filesystem) = [new Filesystem()];

    if(! is_array($paths))
        $paths = (array)$paths;

    array_walk($paths, function($path) use($filesystem) {
        if( ! $filesystem->exists($path) )
            return false;

        $filesystem
            ->remove($path);
    });
}

/**
 * Make path .
 *
 * @param array $paths
 */
function mk_path($paths = array()) {
    list($filesystem) = [new Filesystem()];

    if(! is_array($paths))
        $paths = (array)$paths;

    array_walk($paths, function($path) use($filesystem) {
        if(  $filesystem->exists($path) )
            return false;

        $filesystem
            ->mkdir($path);
    });
}

/**
 * Dump contents to file .
 *
 * @param $path
 * @param $content
 * @return mixed
 */
function dump_file($path, $content) {
    list($filesystem) = [new Filesystem()];

    return $filesystem
        ->dumpFile($path, $content);
}

/**
 * Create archive
 *
 * @param $archivePath
 * @param null $storePath
 * @return bool
 * @internal param $path
 */
function create_archive($archivePath, $storePath  = null) {
    list($finder) = [new Finder];

    $path = str_replace('\\', '/', realpath($archivePath));

    if(! is_path_exists( $path ))
        return false;

    if(! $storePath)
        $storePath = storage_path();

    $file     = pathinfo($path);
    $fullPath = $storePath . DIRECTORY_SEPARATOR . $file['basename'] . '.zip';

    $zip = new ZipArchive();
    $zip->open($fullPath, ZipArchive::CREATE);

    $files = $finder->in($path)->files();

    foreach ($files as $file)
        $zip->addFile(
            $file->getRealpath(),
            $file->getRelativePathname()
        );

    $zip->close();

    return $fullPath;
}

/**
 * Download archive .
 *
 * @param $path
 * @param $file
 * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
 */
function download_archive($path, $file) {
    $fullPath = create_archive($path);

    return response()
        ->download($fullPath, $file . '.zip', [
            'Content-Type: application/zip',
            'Content-Disposition: attachment; filename='.$file,
        ]);
}