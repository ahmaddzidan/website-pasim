<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Create Slider
*/
class Migration_Create_slider extends CI_Migration
{

	function up()
	{
		echo "\nCreating Slider table...";

	    // Drop table 'slider' if it exists
	    $this->dbforge->drop_table('tbl_slider', TRUE);

		$this->dbforge->add_field(array(
			'id' => array(
		        'type' => 'MEDIUMINT',
		        'constraint' => '8',
		        'unsigned' => TRUE,
		        'auto_increment' => TRUE
	      		),
			'title' => array(
				'type'       => 'VARCHAR',
				'constraint' => '100'
				),
			'caption' => array(
				'type'       => 'VARCHAR',
				'constraint' => '100'
				),
			'url' => array(
				'type'       => 'TEXT'
				),
			'images' => array(
		        'type'       => 'VARCHAR',
		        'constraint' => '255'
		        ),
			'bgcolor' => array(
		        'type'       => 'VARCHAR',
		        'constraint' => '25',
		        'null' => TRUE
		        ),
			'color' => array(
		        'type'       => 'VARCHAR',
		        'constraint' => '25',
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
	$this->dbforge->create_table('tbl_slider');

	 // Dumping data for table 'slider'
    $data = array(
      array(
        'id'        => '1',
        'title'     => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit',
        'caption'   => 'lorem-ipsum-dolor-sit-amet-consectetur-adipisicing-elit',
        'url'       => 'pasim.ac.id',
        'images'    => 'placeholder.jpg',
        'bgcolor'   => 'Lorem, Ipsum, dolor',
        'color'     => 'Lorem Ipsum',
        'created'   => '2016-06-29 14:55:43',
        'createdby' => '1',
        'status'    => '1',
        'pubdate'   => '2016-04-14'
      )
    );
    $this->db->insert_batch('tbl_slider', $data);


    echo "\n Slider table created";
    echo "\n";
	}

	public function down()
	{
		$this->dbforge->drop_table('tbl_slider');
	}

}