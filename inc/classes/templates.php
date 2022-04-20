<?php

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class CB_Email_Templates extends WP_List_Table
{
    /**
     * Prepare data
     *
     */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();
        usort($data, array(&$this, 'sort_data'));
        global $total_records;
        $perPage = 10;

        $this->set_pagination_args(array(
            'total_items' => $total_records,
            'per_page' => $perPage
        ));

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return array
     */
    public function get_columns()
    {
        $columns = array(
            'title' => __('Template Name'),
            'createdon' => __('Created On'),
            'modifiedon' => __('Modified On'),
            'actions' => __('Action'),
        );
        return $columns;
    }

    /**
     * Define the sortable columns
     *
     * @return array
     */
    public function get_sortable_columns()
    {
        return array('title' => array('title', true));
    }

    /**
     * Get single template details
     *
     * @param integer $templateId
     * @return void
     */
    public static function getSingleEmailTemplateDetails($templateId){
        global $wpdb;
        $table_name = tblTemplates;
        $sql = "SELECT * from {$table_name} WHERE id=$templateId";
        $result = $wpdb->get_row($sql);
        return $result;
    }

    /**
     * Get the table data
     *
     * @return array
     */
    private function table_data()
    {
        global $wpdb, $total_records;
        $data = array();
        $table_name = tblTemplates;
        $paged = isset($_GET['paged']) ? $_GET['paged'] : 1;
        $start = ($paged - 1) * 10;

        $total_records = $wpdb->get_var("SELECT COUNT(*) FROM {$table_name}");
        $sql = "SELECT * from {$table_name}  LIMIT $start, 10";

        $result = $wpdb->get_results($sql);
        $c = 1;
        foreach ($result as $key => $value) {
            $data[] = array(
                'id' => $value->id, 
                'title' => $value->title,
                'createdon' => $value->createdon,
                'modifiedon' => $value->modifiedon,
            );
            $c++;
        }
        return $data;
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  array $item        Data
     * @param  string $column_name - Current column name
     *
     * @return mixed
     */
    public function column_default($item, $column_name) {
        switch ($column_name) {
            
            case 'title':
            case 'createdon':
            case 'modifiedon':
                return $item[ $column_name ];
            case 'actions':
                add_thickbox();
                $editUrl = admin_url()."admin.php?page=cb-emails&edit=".$item['id'];
                $emailTestUrl = "#TB_inline?height=300&amp;width=400&amp;inlineId=emailtest";
                $row = sprintf("<a href='%s' title='Edit template' class='edit-email dashicons dashicons-edit'></a> <a href='%s' title='Send test email' class='test-email dashicons dashicons-email-alt'></a>",$editUrl,$emailTestUrl);
                return $row;
            default:
                return print_r($item, true);
        }
    }

    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @param array $a stores order by field name
     * @param array $b stores order by field name
     * @return mixed
     */
    private function sort_data($a, $b) {
        // Set defaults
        $orderby = 'title';
        $order = 'asc';

        // If orderby is set, use this as the sort column
        if (!empty($_GET['orderby'])) {
            $orderby = $_GET['orderby'];
        }

        // If order is set use this as the order
        if (!empty($_GET['order'])) {
            $order = $_GET['order'];
        }


        $result = strcmp($a[$orderby], $b[$orderby]);

        if ($order === 'asc') {
            return $result;
        }

        return -$result;
    }

    /**
     * Replaces single quotes from string
     *
     * @param mixed $sql
     */
    function closure($sql) {
        return str_replace("'mt1.meta_value'", "mt1.meta_value", $sql);
    }

   
}

?>