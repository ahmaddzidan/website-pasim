<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Create Gallery
*/
class Migration_Create_gallery extends CI_Migration
{

	function up()
	{
		echo "\nCreating gallery table...";

		// Drop table 'Gallery' if it exists
	    $this->dbforge->drop_table('tbl_gallery_category', TRUE);

	    // Table structure for table 'Gallery'
	    $this->dbforge->add_field(array(
	      'id' => array(
	        'type' => 'MEDIUMINT',
	        'constraint' => '8',
	        'unsigned' => TRUE,
	        'auto_increment' => TRUE
	      ),
	      'name' => array(
	        'type' => 'VARCHAR',
	        'constraint' => '20',
	      ),
	      'description' => array(
	        'type' => 'VARCHAR',
	        'constraint' => '100',
	      ),
	      'slug' => array(
	        'type' => 'VARCHAR',
	        'constraint' => '20',
	      ),
	      'created' => array(
	        'type'       => 'DATETIME',
	      ),
	      'createdby' => array(
	        'type' => 'MEDIUMINT',
	        'constraint' => '8',
	        'unsigned' => TRUE,
	        'null' => TRUE
	      ),
	      'modified' => array(
	        'type'       => 'DATETIME',
	        'null' => TRUE
	      ),
	      'modifiedby' => array(
	       'type' => 'MEDIUMINT',
	        'constraint' => '8',
	        'unsigned' => TRUE,
	        'null' => TRUE
	      )
    ));

    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table('tbl_gallery_category');

    // Dumping data for table 'Gallery'
    $data = array(
      array(
        'id' => '1',
        'name' => 'Local',
        'description' => 'Local Album',
        'slug' => 'local-album',
        'created' => '2016-06-29 14:55:43',
        'createdby' => '1'
      ),
      array(
        'id' => '2',
        'name' => 'International',
        'description' => 'International Album',
        'slug' => 'International-album',
        'created' => '2016-06-29 15:00:00',
        'createdby' => '1'
      )
    );
    $this->db->insert_batch('tbl_gallery_category', $data);

	    // Drop table 'Gallery' if it exists
	    $this->dbforge->drop_table('tbl_gallery', TRUE);

		$this->dbforge->add_field(array(
			'id' => array(
		        'type' => 'MEDIUMINT',
		        'constraint' => '8',
		        'unsigned' => TRUE,
		        'auto_increment' => TRUE
	      		),
			'catid' => array(
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
			'caption' => array(
				'type'       => 'VARCHAR',
				'constraint' => '100'
				),
			'images' => array(
		        'type'       => 'VARCHAR',
		        'constraint' => '255'
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
	$this->dbforge->create_table('tbl_gallery');

	 // Dumping data for table 'tbl_gallery'
    $data = array(
      array(
        'id'        => '1',
        'catid'        => '1',
        'title'     => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit',
        'slug'   => 'lorem-ipsum-dolor-sit-amet-consectetur-adipisicing-elit',
        'caption'   => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum',
        'images'    => 'placeholder.jpg',
        'created'   => '2016-06-29 14:55:43',
        'createdby' => '1',
        'status'    => '1',
        'pubdate'   => '2016-04-14'
      )
    );
    $this->db->insert_batch('tbl_gallery', $data);


    echo "\n gallery table created";
    echo "\n";
	}

	public function down()
	{
		$this->dbforge->drop_table('tbl_gallery');
	}

}