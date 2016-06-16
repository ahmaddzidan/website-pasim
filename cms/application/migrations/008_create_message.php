<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Create Message
*/
class Migration_Create_message extends CI_Migration
{

	function up()
	{
		echo "\nCreating Message table...";

	    // Drop table 'Message' if it exists
	    $this->dbforge->drop_table('tbl_message', TRUE);

		$this->dbforge->add_field(array(
			'id' => array(
		        'type' => 'MEDIUMINT',
		        'constraint' => '8',
		        'unsigned' => TRUE,
		        'auto_increment' => TRUE
	      		),
			'subject' => array(
				'type'       => 'VARCHAR',
				'constraint' => '100'
				),
			'name' => array(
				'type'       => 'VARCHAR',
				'constraint' => '100'
				),
			'website' => array(
				'type'       => 'VARCHAR',
				'constraint' => '100',
				'null' => TRUE
				),
			'email' => array(
				'type'       => 'VARCHAR',
				'constraint' => '200',
				),
			'company' => array(
				'type'       => 'VARCHAR',
				'constraint' => '100',
				'null' => TRUE
				),
			'mobile' => array(
				'type'       => 'VARCHAR',
				'constraint' => '25',
				),
			'body' => array(
				'type' => 'TEXT'
				),
			'type' => array(
				'type'       => 'VARCHAR',
				'constraint' => '5',
				),
			'read' => array(
		        'type' => 'TINYINT',
		        'constraint' => '2'
		        ),
			'reply' => array(
		        'type'       => 'TINYINT',
		        'constraint' => '2'
		        ),
			'replyid' => array(
		        'type'       => 'MEDIUMINT',
		        'constraint' => '8',
		        'null' => TRUE,
		        'unsigned' => TRUE
		        ),
			'ip' => array(
				'type'       => 'VARCHAR',
				'constraint' => '50',
				),
			'ua' => array(
				'type'       => 'TEXT'
				),
			'created' => array(
				'type'       => 'DATETIME',
				),
			'createdby' => array(
		        'type' => 'TINYINT',
		        'constraint' => '3',
		        'unsigned' => TRUE
		        )
			)
		);

	$this->dbforge->add_key('id', TRUE);
	$this->dbforge->create_table('tbl_message');

	// Dumping data for table 'Message'
    $data = array(
      array(
        'id'        => '1',
        'subject'     => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit',
        'name'      => 'Ahmad Sanusi',
        'website'     => 'ahmad.ourdesk.info',
        'email'     => 'info@ahmad.ourdesk.info',
        'mobile'     => '+628988446869',
        'company'     => 'Kawatama',
        'type' => 'in',
        'body'      => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Odit adipisci corporis, veritatis tenetur magnam saepe consequuntur beatae ipsam aut doloribus reiciendis a nulla quasi esse non dolore, quaerat, rerum sit!
       Quae blanditiis, doloribus numquam. Quos repellendus vel rerum quidem sed culpa magni repellat cum, eveniet enim architecto! Perferendis optio minus nihil repudiandae ipsum qui, odio consequuntur vel nisi accusamus exercitationem.',
        'created'   => '2016-06-29 14:55:43',
        'createdby' => '1',
        'read'    => '0',
        'reply'   => '0'
      )
    );
    $this->db->insert_batch('tbl_message', $data);


    echo "\n Message table created";
    echo "\n";
	}

	public function down()
	{
		$this->dbforge->drop_table('tbl_message');
	}

}