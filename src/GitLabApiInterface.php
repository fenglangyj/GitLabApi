<?php
namespace fenglangyj\GitLabApi;

/**
 * Client interface for sending HTTP requests.
 */
interface GitLabApiInterface
{
    /**
     * @deprecated Will be removed in Guzzle 7.0.0
     */
    const VERSION = '1.0.0';

    /**
     * Get a client configuration option.
     *
     * These options include default request options of the client, a "handler"
     * (if utilized by the concrete client), and a "base_uri" if utilized by
     * the concrete client.
     *
     * @param string|null $option The config option to retrieve.
     *
     * @return mixed
     */
    public function getConfig($option = null);
}
