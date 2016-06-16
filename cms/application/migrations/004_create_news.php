<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_news extends CI_Migration {


 // --------------------------------------------------------------------
   function up()
    {

    echo "\nCreating news table...";

    // Drop table 'news' if it exists
    $this->dbforge->drop_table('tbl_news_category', TRUE);

    // Table structure for table 'news'
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
    $this->dbforge->create_table('tbl_news_category');

    // Dumping data for table 'news'
    $data = array(
      array(
        'id' => '1',
        'name' => 'Berita',
        'description' => 'Kategori berita',
        'slug' => 'berita',
        'created' => '2016-06-29 14:55:43',
        'createdby' => '1'
      ),
      array(
        'id' => '2',
        'name' => 'Informasi',
        'description' => 'Kategori Informasi',
        'slug' => 'informasi',
        'created' => '2016-06-29 15:00:00',
        'createdby' => '1'
      )
    );
    $this->db->insert_batch('tbl_news_category', $data);

   $this->dbforge->drop_table('tbl_news', TRUE);

    $this->dbforge->add_field(array(
      'id' => array(
        'type'           => 'INT',
        'constraint'     => 11,
        'unsigned'       => TRUE,
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
      'status' => array(
        'type'    => 'TINYINT'
        ),
      'pubdate' => array(
        'type' => 'DATE'
        ),
      'views' => array(
        'type'           => 'INT',
        'constraint'     => 11,
        'default' => '0',
        'unsigned' => TRUE
        ),
      'comment' => array(
        'type' => 'TINYINT',
        'null'    => TRUE,
        'default' => '1'
        )
      )
    );
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table('tbl_news');

    // Dumping data for table 'news'
    $data = array(
      array(
        'id'        => '1',
        'catid'     => '1',
        'title'     => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit',
        'slug'      => 'lorem-ipsum-dolor-sit-amet-consectetur-adipisicing-elit',
        'intro'     => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Veniam rerum magni nisi qui iure voluptatem provident accusamus deleniti velit facere, inventore minus molestiae ut est, asperiores voluptates repellendus quos, atque.',
        'body'      => '<div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Odit adipisci corporis, veritatis tenetur magnam saepe consequuntur beatae ipsam aut doloribus reiciendis a nulla quasi esse non dolore, quaerat, rerum sit!</div>
        <div>Quae blanditiis, doloribus numquam. Quos repellendus vel rerum quidem sed culpa magni repellat cum, eveniet enim architecto! Perferendis optio minus nihil repudiandae ipsum qui, odio consequuntur vel nisi accusamus exercitationem.</div>',
        'category'  => 'Local',
        'images'    => 'headline-berita.jpg',
        'created'   => '2016-06-29 14:55:43',
        'createdby' => '1',
        'status'    => '1',
        'pubdate'   => '2016-04-14'
      )
    );
    $this->db->insert_batch('tbl_news', $data);


    echo "\n News table created";
    echo "\n";
  }

   // --------------------------------------------------------------------

   public function down()
   {
    $this->dbforge->drop_table('tbl_news');
   }

   // --------------------------------------------------------------------
}
