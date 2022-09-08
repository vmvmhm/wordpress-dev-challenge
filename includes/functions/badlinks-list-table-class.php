<?php 

if (!class_exists('WP_List_Table')) {
      require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}
// Extending class
class BadLinks_List_Table extends WP_List_Table
{
    // Here we will add our code
 private $table_data;
    // Define table columns

protected function get_sortable_columns()
{
      $sortable_columns = array(
            'link'  => array('link', false),
            'status' => array('status', false),
            'post_link'   => array('post_link', true)
      );
      return $sortable_columns;
}

    function usort_reorder($a, $b)
    {
        // If no sort, default to user_login
        $orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'link';
        // If no order, default to asc
        $order = (!empty($_GET['order'])) ? $_GET['order'] : 'asc';
        // Determine sort order
        $result = strcmp($a[$orderby], $b[$orderby]);
        // Send final sort direction to usort
        return ($order === 'asc') ? $result : -$result;
    }



    function get_columns()
    {
        $columns = array(
                'link'          => "Link",
                'status'         => "Status",
                'post_link'   => "Origin",
        );
        return $columns;
    }

    function prepare_items()
    {
        //data
        $this->table_data = $this->get_table_data();

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $primary  = 'link';
        $this->_column_headers = array($columns, $hidden, $sortable, $primary);

        usort($this->table_data, array(&$this, 'usort_reorder'));
        
        $this->items = $this->table_data;
    }

    function column_default($item, $column_name)
    {
          switch ($column_name) {
                case 'link':
                case 'status':
                case 'post_link':
                default:
                    return $item[$column_name];
          }
    }

    private function get_table_data() {
        return getAllBadLinksWithPostId();
    }

}