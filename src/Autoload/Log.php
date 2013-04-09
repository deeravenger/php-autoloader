<?php
/**
 * Universal php auto loader
 *
 * @link      http://github.com/dmkuznetsov/php-autoloader
 * @copyright Copyright (c) 2012-2013 Dmitry Kuznetsov <kuznetsov2d@gmail.com>
 * @license   http://raw.github.com/dmkuznetsov/php-autoloader/master/LICENSE.txt New BSD License
 */
namespace Dm\Utils\Autoload;
use Dm\Utils\Autoload\LogInterface as LogInterface;

class Log implements LogInterface
{
    /**
     * @var bool
     */
    protected $_verboseMode;
    /**
     * @var array
     */
    private $_data = array();
    /**
     * @var bool
     */
    private $_progressStatus = false;
    /**
     * @var
     */
    private $_progressCount;
    /**
     * @var int
     */
    private $_progressState = 0;

    const MIN_FOR_RULE = 25;

    /**
     * @param bool $verbose
     */
    public function __construct($verbose = true)
    {
        $this->_verboseMode = $verbose;
    }

    /**
     * @return string|void
     */
    public function log()
    {
        $date = date('H:i:s');
        $message = $date . ' ' . call_user_func_array('sprintf', func_get_args());
        $this->_data[] = $message;
        if ($this->_verboseMode) {
            echo "\n" . $message;
        }
    }

    /**
     * Return all logged data
     * @return array
     */
    public function getLog()
    {
        return $this->_data;
    }

    /**
     * @param null $count
     * @return mixed|void
     */
    public function startProgress($count = null)
    {
        if (!is_null($count) && $count >= self::MIN_FOR_RULE) {
            $this->_showRule();
        }
        $this->_progressStatus = true;
        $this->_progressCount = $count;
        $this->_progressState = 0;
    }

    /**
     * @param int $number
     * @return mixed|void
     */
    public function updateProgress($number = 0)
    {
        if (is_null($this->_progressCount)) {
            $this->_updateUnlimited();
        } else {
            $this->_updateLimited($number);
        }
    }

    /**
     * @return void
     */
    public function stopProgress()
    {
        $this->_progressStatus = false;
        $this->_progressState = 0;
    }

    private function _updateUnlimited()
    {
        list($micro,) = explode(' ', microtime());
        $micro *= 100;
        if (!$this->_progressState || $micro > $this->_progressState + 5) {
            $this->_progressState = $micro;
            echo ".";
        }
    }

    private function _updateLimited($number)
    {
        if ($this->_progressCount >= self::MIN_FOR_RULE) {
            $percent = (int)($number * 100 / $this->_progressCount);
            if ($percent % 2 && $percent >= $this->_progressState + 2) {
                $this->_progressState = $percent;
                echo "=";
            }
        }
    }

    private function _showRule()
    {
        $length = 50;
        $hint = "100%";
        $content = "\n[";
        for ($i = 0, $c = $length - strlen($hint); $i < $c; $i++) {
            if ($i == $c / 2 - strlen($hint) / 2) {
                $content .= $hint;
            } else {
                $content .= "-";
            }
        }
        $content .= "]\n ";
        echo $content;
    }
}