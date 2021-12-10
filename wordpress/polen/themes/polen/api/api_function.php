<?php
/**
 * Tratar response da API
 *
 * @param mixed $data
 * @param int $status_code
 * @param array $headers
 * @return WP_REST_Response
 */
function api_response($data = null, int $status_code = 200, array $headers = []): WP_REST_Response
{
  return new WP_REST_Response(['data' => $data], $status_code, $headers);
}
