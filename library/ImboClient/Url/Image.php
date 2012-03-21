<?php
/**
 * ImboClient
 *
 * Copyright (c) 2011-2012, Christer Edvartsen <cogo@starzinger.net>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to
 * deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * * The above copyright notice and this permission notice shall be included in
 *   all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 *
 * @package Url
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011-2012, Christer Edvartsen <cogo@starzinger.net>
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/imbo/imboclient-php
 */

namespace ImboClient\Url;

/**
 * Image URL
 *
 * @package Url
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011-2012, Christer Edvartsen <cogo@starzinger.net>
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/imbo/imboclient-php
 */
class Image extends Url implements ImageInterface {
    /**
     * Image identifier
     *
     * @var string
     */
    private $imageIdentifier;

    /**
     * Query data
     *
     * @var array
     */
    private $data;

    /**
     * Class constructor
     *
     * @see ImboClient\Url\Url::__construct()
     * @param string $imageIdentifier The image identifier to use in the URL
     */
    public function __construct($baseUrl, $publicKey, $privateKey, $imageIdentifier) {
        parent::__construct($baseUrl, $publicKey, $privateKey);

        $this->imageIdentifier = $imageIdentifier;
    }

    /**
     * @see ImboClient\Url\Url::getRawUrl()
     */
    protected function getRawUrl() {
        $queryString = $this->getQueryString();

        return sprintf(
            '%s/users/%s/images/%s%s',
            $this->baseUrl,
            $this->publicKey,
            $this->imageIdentifier,
            ($queryString ? '?' . $queryString : '')
        );
    }

    /**
     * @see ImboClient\Url\ImageInterface::border()
     */
    public function border($color = '000000', $width = 1, $height = 1) {
        return $this->append(sprintf('border:color=%s,width=%d,height=%d', $color, $width, $height));
    }

    /**
     * @see ImboClient\Url\ImageInterface::compress()
     */
    public function compress($quality = 75) {
        return $this->append('compress:quality=' . (int) $quality);
    }

    /**
     * @see ImboClient\Url\ImageInterface::convert()
     */
    public function convert($type) {
        $this->imageIdentifier = substr($this->imageIdentifier, 0, 32) . '.' . $type;

        return $this;
    }

    /**
     * @see ImboClient\Url\ImageInterface::gif()
     */
    public function gif() {
        return $this->convert('gif');
    }

    /**
     * @see ImboClient\Url\ImageInterface::jpg()
     */
    public function jpg() {
        return $this->convert('jpg');
    }

    /**
     * @see ImboClient\Url\ImageInterface::png()
     */
    public function png() {
        return $this->convert('png');
    }

    /**
     * @see ImboClient\Url\ImageInterface::crop()
     */
    public function crop($x, $y, $width, $height) {
        return $this->append(sprintf('crop:x=%d,y=%d,width=%d,height=%d', $x, $y, $width, $height));
    }

    /**
     * @see ImboClient\Url\ImageInterface::flipHorizontally()
     */
    public function flipHorizontally() {
        return $this->append('flipHorizontally');
    }

    /**
     * @see ImboClient\Url\ImageInterface::flipVertically()
     */
    public function flipVertically() {
        return $this->append('flipVertically');
    }

    /**
     * @see ImboClient\Url\ImageInterface::resize()
     */
    public function resize($width = null, $height = null) {
        $params = array();

        if ($width) {
            $params[] = 'width=' . (int) $width;
        }

        if ($height) {
            $params[] = 'height=' . (int) $height;
        }

        return $this->append('resize:' . implode(',', $params));
    }

    /**
     * @see ImboClient\Url\ImageInterface::maxSize()
     */
    public function maxSize($maxWidth = null, $maxHeight = null) {
        $params = array();

        if ($maxWidth) {
            $params[] = 'width=' . (int) $maxWidth;
        }

        if ($maxHeight) {
            $params[] = 'height=' . (int) $maxHeight;
        }

        return $this->append('maxSize:' . implode(',', $params));
    }

    /**
     * @see ImboClient\Url\ImageInterface::rotate()
     */
    public function rotate($angle, $bg = '000000') {
        return $this->append(sprintf('rotate:angle=%d,bg=%s', $angle, $bg));
    }

    /**
     * @see ImboClient\Url\ImageInterface::thumbnail()
     */
    public function thumbnail($width = 50, $height = 50, $fit = 'outbound') {
        return $this->append(sprintf('thumbnail:width=%d,height=%s,fit=%s', $width, $height, $fit));
    }

    /**
     * @see ImboClient\Url\ImageInterface::canvas()
     */
    public function canvas($width, $height, $mode = null, $x = null, $y = null, $bg = null) {
        $params = array(
            'width=' . (int) $width,
            'height=' . (int) $height,
        );

        if ($mode) {
            $params[] = 'mode=' . $mode;
        }

        if ($x) {
            $params[] = 'x=' . (int) $x;
        }

        if ($y) {
            $params[] = 'y=' . (int) $y;
        }

        if ($bg) {
            $params[] = 'bg=' . $bg;
        }

        return $this->append('canvas:' . implode(',', $params));
    }

    /**
     * @see ImboClient\Url\ImageInterface::reset()
     */
    public function reset() {
        $this->data = array();
        $this->imageIdentifier = substr($this->imageIdentifier, 0, 32);

        return $this;
    }

    /**
     * Append a string to the query
     *
     * @param string $part The string to append
     * @return ImboClient\Url\ImageInterface
     */
    private function append($part) {
        $this->data[] = $part;

        return $this;
    }

    /**
     * Return the query string
     *
     * @return string
     */
    private function getQueryString() {
        if (empty($this->data)) {
            return '';
        }

        $queryString = array_reduce($this->data, function($query, $element) {
            return $query . 't[]=' . $element . '&';
        }, '');

        $queryString = rtrim($queryString, '&');

        return $queryString;
    }
}