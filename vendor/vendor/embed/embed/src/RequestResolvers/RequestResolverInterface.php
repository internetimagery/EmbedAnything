<?php
/**
 * Default class to resolve urls
 */
namespace Embed\RequestResolvers;

interface RequestResolverInterface
{
    /**
     * Constructor. Sets the url
     *
     * @param string $url The url value
     */
    public function __construct($url);

    /**
     * Sets the configuration
     *
     * @param array $config
     */
    public function setConfig(array $config);

    /**
     * Set a new url (and clear data of previous url)
     */
    public function setUrl($url);

    /**
     * Get the http code of the url, for example: 200
     *
     * @return int The http code
     */
    public function getHttpCode();

    /**
     * Get the content-type of the url, for example: text/html
     *
     * @return string The content-type header or null
     */
    public function getMimeType();

    /**
     * Get the content of the url
     *
     * @return string The content or false
     */
    public function getContent();

    /**
     * Return the final url (after all possible redirects)
     *
     * @return string The final url
     */
    public function getUrl();

    /**
     * Return the http request info (for debug purposes)
     *
     * @return array
     */
    public function getRequestInfo();
}
