<?php
/**
 * API Bot Class - contains global functions
 * Extend this for each specific API needed
 */

class External_API_Bot extends API_Bot
{
  public $curl;
  public $url;
  public $endpoint;
  public $method      = 'GET';
  public $headers     = array();
  public $data        = array();
  public $params      = array();
  public $route       = array();

  public function __construct( $presets )
  {
    $this->set( $presets );

    $this->name        = $this->name ? $this->name : 'api_bot';

    $this->curl        = new WP_CURL();

    $this->ajax_params = array( 'data', 'route', 'params', 'method' );

    add_action( 'wp_ajax_' . $this->name, array( $this, 'ajax' ) );
    add_action( 'wp_ajax_nopriv_' . $this->name, array( $this, 'ajax' ) );
  }


  public function call()
  {
    $this->build_endpoint()->build_headers();

    $this->response = $this->curl->call(
                        $this->endpoint,
                        $this->method,
                        $this->data,
                        $this->headers
                      )->response;

    return $this;
  }


  private function build_endpoint()
  {
    $this->endpoint = $this->url;

    $this->combine( 'route' )->combine( 'params' );

    return $this;
  }


  private function combine( $which = 'route' )
  {
    if ( $this->$which )
    {
      $separators = $this->set_separators( $which );

      $combined   = array();

      foreach ( $this->$which as $k => $v )
      {
        if ( $this->is_associative( $this->$which ) )
        {
          $combined[] = ! empty( $v )
                      ? $k . $separators['inside'] . urlencode( $v )
                      : $k;
        }
        else
        {
          if ( ! empty( $v ) )
          {
            $combined[] = urlencode( $v );
          }
        }
      }

      $this->endpoint .= $separators['before'] . implode( $separators['between'], $combined );

      if ( $which == 'route' ) $this->endpoint .= $separators['between'];
    }

    return $this;
  }


  private function set_separators( $type )
  {
    $separator_values = $type == 'route'
                      ? array( '/', '/', '/' )
                      : array( '?', '&', '=' );

    return array_combine(
      array( 'before', 'between', 'inside' ),
      $separator_values
    );
  }


  private function build_headers()
  {
    if ( $this->is_associative( $this->headers ) )
    {
      $temp_headers = array();

      foreach ( $this->headers as $k => $v ) {
        $temp_headers[] = $k . ': ' . $v;
      }

      $this->headers = $temp_headers;
    }

    return $this;
  }
}