<?php
/**
 * Tratar response da API
 *
 * @param array $data
 * @param int $statusCode
 * @param array $headers
 * @return WP_REST_Response
 */
function api_response(array $data, int $statusCode = 200, array $headers = []): WP_REST_Response
{
  return new WP_REST_Response(['data' => $data], $statusCode, $headers);
}
