<?php

namespace Flysap\Support;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

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

    foreach ($finder as $file) {
        $fileExists = true;
    }

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