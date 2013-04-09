<?php
/**
 * Universal php auto loader
 *
 * @link      http://github.com/dmkuznetsov/php-autoloader
 * @copyright Copyright (c) 2012-2013 Dmitry Kuznetsov <kuznetsov2d@gmail.com>
 * @license   http://raw.github.com/dmkuznetsov/php-autoloader/master/LICENSE.txt New BSD License
 */
namespace Dm\Utils\Autoload;

interface LogInterface
{
    public function __construct($verbose = true);

    /**
     * (PHP 4, PHP 5)<br/>
     * Return a formatted string
     * @link http://php.net/manual/en/function.sprintf.php
     * @param string $format <p>
     * The format string is composed of zero or more directives:
     * ordinary characters (excluding %) that are
     * copied directly to the result, and conversion
     * specifications, each of which results in fetching its
     * own parameter. This applies to both sprintf
     * and printf.
     * </p>
     * <p>
     * Each conversion specification consists of a percent sign
     * (%), followed by one or more of these
     * elements, in order:
     * An optional sign specifier that forces a sign
     * (- or +) to be used on a number. By default, only the - sign is used
     * on a number if it's negative. This specifier forces positive numbers
     * to have the + sign attached as well, and was added in PHP 4.3.0.
     * @param mixed $args [optional] <p>
     * </p>
     * @param mixed $_ [optional]
     * @return string a string produced according to the formatting string
     * format.
     */
    public function log();

    /**
     * Start drawing progress
     * @param null $count limited or unlimted
     * @return mixed
     */
    public function startProgress($count = null);

    /**
     * Update progress
     * @param int $number
     * @return mixed
     */
    public function updateProgress($number = 0);

    /**
     * Stop drawing progress
     * @return mixed
     */
    public function stopProgress();
}