<?php
/**
 * Class to cURl things
 */

class WP_CURL
{
  private $error;
  private $success;
  private $http_code;
  public  $response;

  public function call( $url = false, $method = 'GET', $data = array(), $header = array() )
  {
    if ( ! function_exists( 'curl_init' ) )
    {
      die( 'cURL is not installed. Install and try again.' );
    }

    if ( ! $url )
    {
      die( 'You need to specify a URL for the server to cURL.' );
    }

    $ch = curl_init();
    curl_setopt_array(
      $ch,
      array(
        CURLOPT_URL            => $url,
        CURLOPT_CUSTOMREQUEST  => $method,
        CURLOPT_HEADER         => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_POSTFIELDS     => $data,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_HTTPHEADER     => $header,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false
      )
    );

    $this->success     = curl_exec( $ch );
    $this->http_code   = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
    $this->header_size = curl_getinfo( $ch, CURLINFO_HEADER_SIZE );
    $this->error       = curl_error( $ch );

    curl_close( $ch );

    $this->parse();

    $this->format_return();

    return $this;
  }


  private function parse()
  {
    $this->strip_headers();

    if ( strpos( $this->success, '<?xml' ) === 0 )
    {
      $this->success = simplexml_load_string( $this->success );
    }
    elseif ( strpos( $this->success, '{' ) === 0 || strpos( $this->success, '[' ) === 0 )
    {
      $this->success = json_decode( $this->success );
    }

    return $this;
  }


  private function strip_headers()
  {
    $this->success = substr( $this->success, $this->header_size );
  }


  private function format_return()
  {
    if ( $this->error ) {
      $this->response = array(
        'status' => 'error',
        'data'   => $this->error
      );
    }

    if ( $this->success ) {
      $this->response = array(
        'status' => 'success',
        'data'   => $this->success
      );
    }

    $this->response['http_code'] = $this->http_code;

    return $this;
  }
}