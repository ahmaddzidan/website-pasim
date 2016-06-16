<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Create pages
*/
class Migration_Create_pages extends CI_Migration
{

	function up()
	{
		echo "\nCreating pages table...";

	    // Drop table 'pages' if it exists
	    $this->dbforge->drop_table('tbl_pages', TRUE);

		$this->dbforge->add_field(array(
			'id' => array(
		        'type' => 'MEDIUMINT',
		        'constraint' => '8',
		        'unsigned' => TRUE,
		        'auto_increment' => TRUE
	      		),
			'parent_id' => array(
		        'type' => 'MEDIUMINT',
		        'constraint' => '8',
		        'unsigned' => TRUE
		        ),
			'title' => array(
				'type'       => 'VARCHAR',
				'constraint' => '100'
				),
			'slug' => array(
		        'type'       => 'VARCHAR',
		        'constraint' => 100
		        ),
			'order' => array(
				'type'       => 'INT',
				'constraint' => '11'
				),
			'body' => array(
				'type'       => 'TEXT',
				'constraint' => '500'
				),
			'template' => array(
		        'type'       => 'VARCHAR',
		        'constraint' => '100',
		        'null' => TRUE
		        ),
			'created' => array(
				'type'       => 'DATETIME',
				),
			'createdby' => array(
		        'type' => 'MEDIUMINT',
		        'constraint' => '8',
		        'unsigned' => TRUE
		        ),
			'modified' => array(
				'type'    => 'DATETIME',
				'null'    => TRUE
				),
			'modifiedby' => array(
		        'type' => 'MEDIUMINT',
		        'constraint' => '8',
		        'unsigned' => TRUE,
		        'null' => TRUE
		        ),
			'pubdate' => array(
				'type'    => 'DATE',
				'null'    => TRUE
				),
			'status' => array(
				'type'       => 'TINYINT'
				)
			)
		);

	$this->dbforge->add_key('id', TRUE);
	$this->dbforge->create_table('tbl_pages');

	 // Dumping data for table 'tbl_pages'
    $data = array(
      array(
        'id'        => '1',
        'parent_id'        => '0',
        'title'     => 'Home',
        'slug'   => '/',
        'body'   => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum',
        'order'    => '0',
        'template'    => 'home',
        'created'   => '2016-06-29 14:55:43',
        'createdby' => '1',
        'status'    => '1',
        'pubdate'   => '2016-04-14'
      )
    );
    $this->db->insert_batch('tbl_pages', $data);


    echo "\n pages table created";
    echo "\n";
	}

	public function down()
	{
		$this->dbforge->drop_table('tbl_pages');
	}

}