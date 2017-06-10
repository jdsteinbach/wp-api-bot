<?php
/**
 * API Bot Class - contains global functions
 * Extend this for each specific API needed
 */

class API_Bot
{
  /**
   * $name
   */
  public $name;
  public $ajax_params = array();
  public $response;
  public $debug = true;

  public function __construct( $presets )
  {
    $this->set( $presets );

    $this->name        = $this->name ? $this->name : 'api_bot';

    add_action( 'wp_ajax_' . $this->name, array( $this, 'ajax' ) );
    add_action( 'wp_ajax_nopriv_' . $this->name, array( $this, 'ajax' ) );
  }


  public function set( $things, $value = '' )
  {
    if ( empty( $things ) )
    {
      return $this;
    }

    $things = is_array( $things )
            ? $things
            : array( $things => $value );

    foreach ( $things as $k => $v )
    {
      if ( $k == 'url' )
      {
        $v = rtrim( $v, '/' );
      }

      if ( property_exists( $this, $k ) )
      {
        $this->$k = $v;
      }
    }

    return $this;
  }


  public function call()
  {
    return $this;
  }


  protected function is_associative( $arr )
  {
    if ( array() === $arr )
    {
      return false;
    }

    return array_keys( $arr ) !== range( 0, count( $arr ) - 1 );
  }


  public function ajax()
  {
    $this->set( $this->filter_ajax( $_POST ) )->call()->return_ajax();
  }


  public function filter_ajax( $arr )
  {
    return array_filter(
      $arr,
      function( $key )
      {
        return in_array( $key, $this->ajax_params );
      },
      ARRAY_FILTER_USE_KEY
    );
  }


  public function set_response()
  {
    $payload = array(
      'code'    => $this->response['http_code'],
      'success' => ( $this->response['status'] == 'success' ),
      'data'    => $this->response['data'],
    );

    if ( $this->debug )
    {
      $payload['debug'] = (object) array_merge( (array) $this, array( '_post' => $_POST ) );
    }

    return $payload;
  }


  protected function return_ajax()
  {
    echo json_encode( $this->set_response() );
    die;

    if ( $this->response['status'] == 'error' ) {
      wp_send_json_error( $this->set_response(), 400 );
    } else {
      wp_send_json_success( $this->set_response(), 200 );
    }
  }
}