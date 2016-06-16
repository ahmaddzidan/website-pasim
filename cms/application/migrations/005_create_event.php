<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Create Pages
*/
class Migration_Create_event extends CI_Migration
{

	function up()
	{
		echo "\nCreating Event table...";

	    // Drop table 'event' if it exists
	    $this->dbforge->drop_table('tbl_event', TRUE);

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
			'slug' => array(
				'type'       => 'VARCHAR',
				'constraint' => '100'
				),
			'intro' => array(
				'type'       => 'TEXT'
				),
			'body' => array(
				'type' => 'TEXT'
				),
			'tags' => array(
		        'type' => 'TEXT'
		        ),
			'images' => array(
		        'type'       => 'VARCHAR',
		        'constraint' => '255'
		        ),
			'commite' => array(
				'type'       => 'VARCHAR',
				'constraint' => '200'
				),
			'location' => array(
				'type'       => 'VARCHAR',
				'constraint' => '200'
				),
			'map_location' => array(
		      	'type' => 'TEXT',
		      	'null' => TRUE,
		     ),
		    'radius' => array(
		      	'type' => 'MEDIUMINT',
		      	'null' => TRUE,
		      	'unsigned' => TRUE,
		      	'constraint' => '5'
		     ),
		    'latitude' => array(
		      	'type' => 'VARCHAR',
		      	'constraint' => '255',
		      	'null' => TRUE,
		     ),
		    'longitude' => array(
		      	'type' => 'VARCHAR',
		      	'constraint' => '255',
		      	'null' => TRUE,
		     ),
			'contact' => array(
				'type'       => 'VARCHAR',
				'constraint' => '100'
				),
			'website' => array(
				'type'       => 'VARCHAR',
				'constraint' => '100'
				),
			'start_date' => array(
				'type'       => 'DATETIME',
				),
			'end_date' => array(
				'type'       => 'DATETIME',
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
	$this->dbforge->create_table('tbl_event');

	 // Dumping data for table 'event'
    $data = array(
      array(
        'id'    => '1',
        'title' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit',
        'slug'  => 'lorem-ipsum-dolor-sit-amet-consectetur-adipisicing-elit',
        'intro' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Veniam rerum magni nisi qui iure voluptatem provident accusamus deleniti velit facere, inventore minus molestiae ut est, asperiores voluptates repellendus quos, atque.',
        'body'  => '<div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Odit adipisci corporis, veritatis tenetur magnam saepe consequuntur beatae ipsam aut doloribus reiciendis a nulla quasi esse non dolore, quaerat, rerum sit!</div>
        <div>Quae blanditiis, doloribus numquam. Quos repellendus vel rerum quidem sed culpa magni repellat cum, eveniet enim architecto! Perferendis optio minus nihil repudiandae ipsum qui, odio consequuntur vel nisi accusamus exercitationem.</div>',
        'tags'         => 'Lorem, Ipsum, dolor',
        'images'       => 'placeholder.jpg',
        'commite'      => 'Lorem Ipsum',
        'location'     => 'Consectetur',
        'map_location' => 'Gg. Dakota, Sukaraja, Cicendo, Kota Bandung, Jawa Barat, Indonesia',
        'latitude'     => '-6.8946862',
        'longitude'    => '107.57166489999997',
        'radius'       => '150',
        'contact'      => 'pasim.ac.id',
        'website'      => 'pasim.ac.id',
        'start_date'   => '2016-06-29 14:55:43',
        'end_date'     => '2016-06-29 14:55:43',
        'created'      => '2016-06-29 14:55:43',
        'createdby'    => '1',
        'status'       => '1',
        'pubdate'      => '2016-04-14'
      )
    );
    $this->db->insert_batch('tbl_event', $data);


    echo "\n Event table created";
    echo "\n";
	}

	public function down()
	{
		$this->dbforge->drop_table('tbl_event');
	}

}