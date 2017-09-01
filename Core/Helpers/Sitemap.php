<?php
/**
 * Created by PhpStorm.
 * User: Eytan
 * Date: 31/08/2017
 * Time: 23:35
 */

namespace Core\Helpers;


class Sitemap {

    private $xml = '';
    private $base_url = '';

    public function __construct($base_url)
    {
        $this->xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $this->xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $this->base_url = $base_url;
    }

    public function addRoute($url,$index){
        $this->xml .= '<url>';
        $this->xml .= "<loc>$this->base_url$url</loc>";
        $this->xml .= "<priority>$index</priority>";
        $this->xml .= '</url>';
    }

    public function get() {
        $this->xml .= '</urlset>';

        return $this->xml;
    }

}