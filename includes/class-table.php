<?php
class WP_Custom_Table
{
  protected $table;
  protected $db;

  function __construct( $table )
  {
    global $wpdb;

    $this->db    = $wpdb;
    $this->table = $this->db->prefix.$table;

    $this->create_table();
  }

  public function get( $column = '*', $where = true )
  {
    $query = 'SELECT ' . $column . ' FROM ' . $this->table . ' WHERE ' . $where;
    $results = $this->db->get_results( $query );

    return count( $results ) > 0
           ? $results
           : false;
  }


  public function post( $row )
  {
    return $this->db->insert( $this->table, $row );
  }


  public function delete( $row, $where = false )
  {
    return $this->db->delete( $this->table, $where );
  }


  public function put( $row, $where )
  {
    return $this->db->update( $this->table, $row, $where );
  }


  private function table_exists()
  {
    return ( $this->db->get_var( "SHOW TABLES LIKE '$this->table'" ) == $this->table );
  }


  public function create_table()
  {
    if ( ! $this->table_exists() )
    {
      $this->table_query();
    }

    return $this;
  }


  public function table_query()
  {
    return $this;
  }
}
