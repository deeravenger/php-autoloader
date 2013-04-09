<?php
/**
 * Universal php auto loader
 *
 * @link      http://github.com/dmkuznetsov/php-autoloader
 * @copyright Copyright (c) 2012-2013 Dmitry Kuznetsov <kuznetsov2d@gmail.com>
 * @license   http://raw.github.com/dmkuznetsov/php-autoloader/master/LICENSE.txt New BSD License
 */
namespace Dm\Utils;
use Dm\Utils\Autoload\LogInterface as LogInterface;

if (!defined('T_ML_COMMENT')) {
    define('T_ML_COMMENT', T_COMMENT);
} else {
    define('T_DOC_COMMENT', T_ML_COMMENT);
}

class Autoload
{
    /**
     * @var string
     */
    protected $_dir;
    /**
     * @var string
     */
    protected $_filePath;
    /**
     * @var bool
     */
    protected $_relativePaths = true;
    /**
     * @var string
     */
    protected $_suffix;
    /**
     * @var LogInterface
     */
    protected $_log;

    /**
     * @var array
     */
    private $_files = array();
    /**
     * @var int
     */
    private $_filesCount = 0;
    /**
     * @var array
     */
    private $_classMap = array();
    /**
     * @var int
     */
    private $_classMapCount = 0;

    const AUTOLOAD_NAME = '__dm_autoload';

    /**
     * @param string $file file with autoloader
     * @param string $dir where search classes
     * @param string $suffix Suffix for name of autoload function
     * @param string $relative relative paths
     * @param LogInterface $log
     */
    public function __construct($file, $dir, $suffix, $relative, LogInterface $log)
    {
        $this->_filePath = $file;
        $this->_dir = rtrim($dir, '/');
        $this->_log = $log;
        $this->_relativePaths = $relative;
        $this->_suffix = ltrim((string)$suffix, '_');
    }

    public function run()
    {
        $this->_log->log("Start searching php files...");
        $this->_searchFiles();
        $this->_log->log("Found %d php-files", $this->_filesCount);

        $this->_log->log("Start analyzing files for classes...");
        $this->_buildClassMap();
        $this->_log->log("Found %d classes", $this->_classMapCount);
    }

    /**
     * @return array
     */
    public function getMap()
    {
        return $this->_classMap;
    }

    /**
     * @param string $file
     */
    public function save($file = '')
    {
        if (empty($file)) {
            $file = $this->_filePath;
        }
        $this->_log->log("Start writing class map to file...");
        if ($this->_relativePaths) {
            $this->_log->log("Use relative paths");
        }
        $success = $this->_writeToFile($file);
        if ($success) {
            $this->_log->log("Success! Please, check file %s", $file);
        } else {
            $this->_log->log("Error! Can't write to file %s", $file);
        }
    }

    protected function _searchFiles()
    {
        $this->_log->startProgress();
        $this->_files = $this->_getFileList($this->_dir . DIRECTORY_SEPARATOR . '*.php');
        $this->_log->stopProgress();

        $this->_filesCount = count($this->_files);
    }

    protected function _buildClassMap()
    {
        $this->_log->startProgress($this->_filesCount);
        for ($i = 0; $i < $this->_filesCount; $i++) {
            $list = $this->_getClasses($this->_files[$i]);
            $path = $this->_files[$i];
            if ($this->_relativePaths) {
                $path = $this->_getRelativePath($this->_filePath, $this->_files[$i]);
            }
            foreach ($list as $className) {
                $this->_classMap[$className] = $path;
            }
            $this->_classMapCount += count($list);
            $this->_log->updateProgress($i);
        }
        $this->_log->stopProgress();
        ksort($this->_classMap);
    }

    /**
     * @param $pattern
     * @param int $flags
     * @return array
     */
    protected function _getFileList($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);
        foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, $this->_getFileList($dir . DIRECTORY_SEPARATOR . basename($pattern), $flags));
            $this->_log->updateProgress();
        }
        return $files;
    }

    /**
     * @param $fileName
     * @return array
     */
    protected function _getClasses($fileName)
    {
        $result = array();
        $content = file_get_contents($fileName);
        $tokens = token_get_all($content);
        $waitingClassName = false;
        $waitingNamespace = false;
        $waitingNamespaceSeparator = false;
        $namespace = array();
        for ($i = 0, $c = count($tokens); $i < $c; $i++) {
            if (is_array($tokens[$i])) {
                list($id, $value) = $tokens[$i];
                switch ($id) {
                    case T_NAMESPACE:
                        $waitingNamespace = true;
                        $waitingNamespaceSeparator = false;
                        $namespace = array();
                        break;
                    case T_CLASS:
                    case T_INTERFACE:
                        $waitingClassName = true;
                        break;
                    case T_STRING:
                        if ($waitingNamespace) {
                            $namespace[] = $value;
                            $waitingNamespace = false;
                            $waitingNamespaceSeparator = true;
                        } elseif ($waitingClassName) {
                            if (!empty($namespace)) {
                                $value = sprintf('%s\\%s', implode('\\', $namespace), $value);
                            }
                            $result[] = $value;
                            $waitingClassName = false;
                        }
                        break;
                    case T_NS_SEPARATOR:
                        if ($waitingNamespaceSeparator && !$waitingNamespace && !empty($namespace)) {
                            $waitingNamespace = true;
                            $waitingNamespaceSeparator = false;
                        }
                        break;
                }
            } else {
                if (($waitingNamespace || $waitingNamespaceSeparator) && ($tokens[$i] == '{' || $tokens[$i] == ';')) {
                    $waitingNamespace = false;
                    $waitingNamespaceSeparator = false;
                }
            }
        }
        return $result;
    }

    /**
     * @param string $file
     * @return bool
     */
    protected function _writeToFile($file)
    {
        $content = file_get_contents(dirname(__FILE__) . '/../autoload.php');
        $content = str_replace(self::AUTOLOAD_NAME, self::AUTOLOAD_NAME . '_' . $this->_suffix, $content);
        $content = str_replace('@date', '@date ' . date('Y-m-d H:i'), $content);
        $content = str_replace('array()', var_export($this->_classMap, true), $content);
        $bytes = file_put_contents($file, $content);
        return $bytes ? true : false;
    }

    /**
     * @param string $autoloaderPath path for generated autoloader
     * @param string $file path to analyzed file
     * @return string
     */
    protected function _getRelativePath($autoloaderPath, $file)
    {
        $file1PathParts = explode(DIRECTORY_SEPARATOR, $autoloaderPath);
        $file2PathParts = explode(DIRECTORY_SEPARATOR, $file);
        $countSameParts = 0;
        for ($i = 0, $c = count($file1PathParts); $i < $c; $i++) {
            if (isset($file1PathParts[$i], $file2PathParts[$i])) {
                if ($file1PathParts[$i] == $file2PathParts[$i]) {
                    $countSameParts++;
                }
            }
        }
        $file1PathParts = array_slice($file1PathParts, $countSameParts);
        $file2PathParts = array_slice($file2PathParts, $countSameParts);
        $result = array();
        if (count($file1PathParts) > 1) {
            for ($i = 1, $c = count($file1PathParts); $i < $c; $i++) {
                $result[] = '..';
            }
        }
        for ($i = 0, $c = count($file2PathParts); $i < $c; $i++) {
            $result[] = $file2PathParts[$i];
        }
        return implode(DIRECTORY_SEPARATOR, $result);
    }
}
