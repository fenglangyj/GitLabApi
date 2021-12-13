<?php
namespace fenglangyj\GitLabApi;

/**
 * @param $value
 * @param int $options
 * @param int $depth
 * @return false|string
 * @throws \Exception
 */
function json_encode($value, $options = 0, $depth = 512)
{
    $json = \json_encode($value, $options, $depth);
    if (JSON_ERROR_NONE !== json_last_error()) {
        throw new \Exception(
            'json_encode error: ' . json_last_error_msg()
        );
    }
    return $json;
}
