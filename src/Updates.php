<?php

/**
 * This file add support to updates
 */

namespace SocialShareForDevs;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7;
use WCPU\Helpers\Url;

require_once(ABSPATH . 'wp-admin/includes/plugin.php');

class Updates
{
    public $endpoint;
    public $transientName;
    public $transientExpiration;
    public $currentPluginData = [];
    public $pluginSlug;

    public function __construct($mainFile)
    {
        if(defined('WC_PLUGIN_FACTORY_DEVELOPMENT') && !empty(WC_PLUGIN_FACTORY_DEVELOPMENT)){
            $this->currentPluginData = get_plugin_data($mainFile);
            $this->pluginSlug = dirname(plugin_basename($mainFile));
            $this->transientName = $this->pluginSlug;
            $this->transientExpiration = 5;
            $this->cache_allowed = false;
            $this->endpoint = 'https://wcanvas.com/wp-json/wcpu-api/verify-plugin';
    
            add_filter('plugins_api', array($this, 'info'), 20, 3);
            add_filter('site_transient_update_plugins', array($this, 'update'));
            add_action('upgrader_process_complete', array($this, 'purge'), 10, 2);
        }
    }

    /**
     * Plugin Update Modal
     */
    function info($res, $action, $args)
    {
        //var_dump($this->currentPluginData);
        // do nothing if you're not getting plugin information right now
        if ('plugin_information' !== $action) {
            return $res;
        }

        // do nothing if it is not our plugin
        if ($this->pluginSlug !== $args->slug) {
            return $res;
        }

        // get updates
        $remote = $this->fetch_remote_data();

        if (!$remote) {
            return $res;
        }
        //var_dump($remote);
        $res = new \stdClass();
        $res->name = $this->currentPluginData['Name'];
        $res->author = $this->currentPluginData['Author'];
        $res->author_profile = $this->currentPluginData['AuthorURI'];
        $res->slug = $remote->slug;
        $res->version = $remote->new_version;
        $res->sections = array(
            'description' => $this->currentPluginData['Description'],
            'installation' => 'asi lo instalas',
            'changelog' => '# hola tets'
        );
        
        if (!empty($remote->banners)) {
            $res->banners = array(
                'low' => $remote->banners['low'],
                'high' => $remote->banners['high']
            );
        }

        return $res;
    }

    /**
     * Check if it's a new version available
     * 
     * @return bool/object
     */
    function fetch_remote_data()
    {
        $remote = get_transient($this->transientName);

        if (false === $remote || !$this->cache_allowed) {

            try {
                $client = new Client(['verify' => false]);
                $response = $client->request(
                    'GET',
                    $this->endpoint,
                    array(
                        'headers' => [
                            'Authorization' => "123"
                        ],
                        'query' => array(
                            'plugin' => $this->pluginSlug,
                            'latest' => defined('WC_PLUGIN_FACTORY_DEVELOPMENT') && !empty(WC_PLUGIN_FACTORY_DEVELOPMENT) ? WC_PLUGIN_FACTORY_DEVELOPMENT : false
                        )
                    )
                );
                $remoteData = json_decode((string) $response->getBody(), true);
                return (object) [
                    'id'            => "{$this->pluginSlug}/{$this->pluginSlug}.php",
                    'slug'          => $this->pluginSlug,
                    'plugin'        => "{$this->pluginSlug}/{$this->pluginSlug}.php",
                    'new_version'   => $remoteData['version'],  // <-- Important!
                    'url'           => $this->currentPluginData['AuthorURI'],
                    'package'       => $remoteData['package'],  // <-- Important!
                    'icons'         => [],
                    'banners'       => [],
                    'tested'        => '',
                    'requires_php'  => '',
                    'compatibility' => new \stdClass(),
                ];
            } catch (ClientException $e) {
                return false;
            }
        }

        return false;
    }

    /**
     * Check if the transient exists to run an update
     * 
     * @param bool/object
     * @return bool/object
     */
    public function update($transient)
    {
        if (empty($transient->checked)) {
            return $transient;
        }

        $checkPluginTransient = get_transient($this->transientName);
        $pluginData = $checkPluginTransient ?: $this->fetch_remote_data();
        $currentPluginData = $this->currentPluginData;

        if (!$checkPluginTransient) {
            set_transient(
                $this->transientName,
                $pluginData,
                $this->transientExpiration
            );
        }

        if (version_compare($pluginData->new_version, $currentPluginData["Version"], ">")) {
            $transient->response["{$this->pluginSlug}/{$this->pluginSlug}.php"] = $pluginData;
        } else {
            $transient->no_update["{$this->pluginSlug}/{$this->pluginSlug}.php"] = $pluginData;
        }

        return $transient;
    }

    /**
     * Clean transient
     * 
     * @param array
     * @param array
     */
    public function purge($upgrader, $options)
    {

        if (
            $this->cache_allowed
            && 'update' === $options['action']
            && 'plugin' === $options['type']
        ) {
            // just clean the cache when new plugin version is installed
            delete_transient($this->transientName);
        }
    }
}
