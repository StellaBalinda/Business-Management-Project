<?php

namespace Slim\Middleware;

 /**
  * Content Types
  *
  * This is middleware for a Slim application that intercepts
  * the HTTP request body and parses it into the appropriate
  * PHP data structure if possible; else it returns the HTTP
  * request body unchanged. This is particularly useful
  * for preparing the HTTP request body for an XML or JSON API.
  *
  * @package    Slim
  * @author     Josh Lockhart
  * @since      1.6.0
  */
class ContentTypes extends \Slim\Middleware
{
    /**
     * @var array
     */
    protected $contentTypes;

    /**
     * Constructor
     * @param array $settings
     */
    public function __construct($settings = array())
    {
        $defaults = array(
            'application/json' => array($this, 'parseJson'),
            'application/xml' => array($this, 'parseXml'),
            'text/xml' => array($this, 'parseXml'),
            'text/csv' => array($this, 'parseCsv')
        );
        $this->contentTypes = array_merge($defaults, $settings);
    }

    /**
     * Call
     */
    public function call()
    {
        $mediaType = $this->app->request()->getMediaType();
        if ($mediaType) {
            $env = $this->app->environment();
            $env['slim.input_original'] = $env['slim.input'];
            $env['slim.input'] = $this->parse($env['slim.input'], $mediaType);
        }
        $this->next->call();
    }

    /**
     * Parse input
     *
     * This method will attempt to parse the request body
     * based on its content type if available.
     *
     * @param  string $input
     * @param  string $contentType
     * @return mixed
     */
    protected function parse ($input, $contentType)
    {
        if (isset($this->contentTypes[$contentType]) && is_callable($this->contentTypes[$contentType])) {
            $result = call_user_func($this->contentTypes[$contentType], $input);
            if ($result) {
                return $result;
            }
        }

        return $input;
    }

    /**
     * Parse JSON
     *
     * This method converts the raw JSON input
     * into an associative array.
     *
     * @param  string       $input
     * @return array|string
     */
    protected function parseJson($input)
    {
        if (function_exists('json_decode')) {
            $result = json_decode($input, true);
            if ($result) {
                return $result;
            }
        }
    }

    /**
     * Parse XML
     *
     * This method creates a SimpleXMLElement
     * based upon the XML input. If the SimpleXML
     * extension is not available, the raw input
     * will be returned unchanged.
     *
     * @param  string                  $input
     * @return \SimpleXMLElement|string
     */
    protected function parseXml($input)
    {
        if (class_exists('SimpleXMLElement')) {
            try {
                $backup = libxml_disable_entity_loader(true);
                $result = new \SimpleXMLElement($input);
                libxml_disable_entity_loader($backup);
                return $result;
            } catch (\Exception $e) {
                // Do nothing
            }
        }

        return $input;
    }

    /**
     * Parse CSV
     *
     * This method parses CSV content into a numeric array
     * containing an array of data for each CSV line.
     *
     * @param  string $input
     * @return array
     */
    protected function parseCsv($input)
    {
        $temp = fopen('php://memory', 'rw');
        fwrite($temp, $input);
        fseek($temp, 0);
        $res = array();
        while (($data = fgetcsv($temp)) !== false) {
            $res[] = $data;
        }
        fclose($temp);

        return $res;
    }
}
