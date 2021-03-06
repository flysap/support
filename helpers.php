<?php

namespace Flysap\Support;

use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;
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
 * Get file contents and convert if needed to array .
 *
 * @param $file
 * @param null $extension
 * @return array
 */
function get_file_contents($file, $extension = null) {
    if( ! file_exists($file)  )
        return [];

    if(! $extension) {
        $fileInfo  = pathinfo($file);
        $extension = $fileInfo['extension'];
    }

    $content = file_get_contents($file);

    if( $extension == 'yaml' )
        return Yaml::parse($content);
    elseif( $extension == 'json' )
        return json_decode($content, true);
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

    if( file_exists($fullPath) )
        remove_paths($fullPath);

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

    return download_file($fullPath, $file, 'zip');

}

/**
 * Download archive .
 *
 * @param $path
 * @param $file
 * @param $format
 * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
 */
function download_file($path, $file, $format) {
    return response()
        ->download($path, $file, [
            'Content-Type: application/' . $format,
            'Content-Disposition: attachment; filename='.$file,
        ]);
}



/**
 * Convert array to csv .
 *
 * @param $array
 * @param string $delimiter
 * @return string
 */
function convert_to_csv($array, $delimiter = ',') {
    $list = $headers = [];

    array_walk($array, function($value, $key) use($delimiter, & $list, & $headers) {
        if( ! is_numeric($key) && $key == 'headers' ) {
            $headers = implode(',', $value);
            return;
        }

        $line = '';
        $count = 0;
        foreach ($value as $k => $v) {
            $count++;
            if(! is_numeric($k) ) {
                if(! $headers)
                    $headers[] = $k;
            }

            if($count >= count($value))
                $delimiter = '';

           if( is_array($v) )
               $v = implode(':', $v);

            $line .= $v . $delimiter;
        }

        if( is_array($headers))
            $headers = implode(',', $headers);

        $list[] = $line;
    });

    array_unshift($list, $headers);

    return implode('\\n', $list);
}

/**
 * Export to csv data .
 *
 * @param $array
 * @param $path
 * @param string $delimiter
 * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
 */
function export_to_csv($array, $path, $delimiter = ',') {
    $list = convert_to_csv($array, $delimiter);

    dump_file($path, $list);

    $file = pathinfo($path);

    return download_file($path, $file['filename'], 'csv');
}



/**
 * Append to query .
 *
 * @param array $appends
 * @param null $fragment
 * @param null $_get
 * @return string
 */
function append_query_url(array $appends, $fragment = null, $_get = null) {
    $array = [];
    array_walk($appends, function($value, $key) use(& $array) {
        $key = str_replace('.', '_', $key);
        $array[$key] = $value;
    });

    $array = (
        array_merge(! is_null($_get) ? $_get : [], $array)
    );

    return http_build_query($array, null, '&') . $fragment;
}


/**
 * Get configuration section .
 *
 * @param null $section
 * @param array $configuration
 * @return mixed
 */
function get_conf_section($section = null, $configuration = array()) {
    return array_get($configuration, $section);
}

/**
 * Call Artisan command .
 *
 * @param $command
 * @param array $params
 * @param callable $onFinish
 * @return mixed
 */
function artisan($command, $params = array(), \Closure $onFinish = null) {
    $exitCode = Artisan::call($command, $params);

    if(! is_null($onFinish) )
        return $onFinish($exitCode);

    return $exitCode;
}

/**
 * Set config from yaml .
 *
 * @param $path
 * @param $key
 * @param null $mergePath
 */
function set_config_from_yaml($path, $key, $mergePath = null) {
    $array = Yaml::parse(file_get_contents(
        $path
    ));

    app('config')
        ->set($key, array_merge($array, config($key, [])));

    if(! is_null($mergePath))
        merge_yaml_config_from($mergePath, $key);
}


/**
 * Merge config from yaml .
 *
 * @param $path
 * @param $key
 */
function merge_yaml_config_from($path, $key) {
    if(! file_exists($path))
        return;

    $array = Yaml::parse(file_get_contents(
        $path
    ));

    app('config')
        ->set($key, array_merge(config($key), $array));
}



/**
 * Change array keys recursive .
 *
 * @param $arr
 * @return array
 */
function array_change_key_case_recursive($arr) {
    return array_map(function ($item) {
        if (is_array($item))
            $item = array_change_key_case_recursive($item);
        return $item;
    }, array_change_key_case($arr));
}

/**
 * Is allowed current user to ..
 *
 * @param array $roles
 * @param array $permissions
 * @return bool
 */
function isAllowed(array $roles = [], array $permissions = []) {
    /** Check for roles . */
    if( $roles ) {
        if( \Auth::check() && \Auth::user()->is($roles) )
            return true;

        return false;
    }

    /** Check for permissions . */
    if( $permissions ) {
        if( \Auth::check() && \Auth::user()->can($permissions) )
            return true;

        return false;
    }

    return true;
}