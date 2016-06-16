<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_site_settings extends CI_Migration {


 // --------------------------------------------------------------------

 public function up()
 {
    echo "\nCreating setting table...";

    // Drop table 'setting' if it exists
	$this->dbforge->drop_table('tbl_setting', TRUE);

    $this->dbforge->add_field(array(
     'id' => array(
      'type' => 'MEDIUMINT',
      'constraint' => '8',
      'null' => FALSE,
      'unsigned' => TRUE,
      'auto_increment' => TRUE
     ),
     'title' => array(
      'type' => 'VARCHAR',
      'constraint' => '100',
      'null' => TRUE,
     ),
     'domain' => array(
      'type' => 'VARCHAR',
      'constraint' => '100',
      'null' => TRUE,
     ),
     'description' => array(
      'type' => 'TEXT',
      'constraint' => '500',
      'null' => TRUE,
     ),
     'owner' => array(
      'type' => 'VARCHAR',
      'constraint' => '50',
      'null' => TRUE,
     ),
     'support' => array(
      'type' => 'VARCHAR',
      'constraint' => '50',
      'null' => TRUE,
     ),
     'support_email' => array(
      'type' => 'VARCHAR',
      'constraint' => '50',
      'null' => TRUE,
     ),
     'issmtp' => array(
      'type' => 'INT',
      'null' => TRUE,
     ),
     'smtphost' => array(
      'type' => 'VARCHAR',
      'constraint' => '150',
      'null' => TRUE,
     ),
     'smtpuser' => array(
      'type' => 'VARCHAR',
      'constraint' => '50',
      'null' => TRUE,
     ),
     'smtppass' => array(
      'type' => 'VARCHAR',
      'constraint' => '50',
      'null' => TRUE,
     ),
     'smtpport' => array(
      'type' => 'VARCHAR',
      'constraint' => '50',
      'null' => TRUE,
     ),
     'location' => array(
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
     'timezone' => array(
      'type' => 'VARCHAR',
      'constraint' => '52',
      'null' => TRUE,
     ),
     'language' => array(
      'type' => 'VARCHAR',
      'constraint' => '25',
      'null' => TRUE,
     ),
     'dateformat' => array(
      'type' => 'VARCHAR',
      'constraint' => '25',
      'null' => TRUE,
     ),
     'metakeyword' => array(
      'type' => 'TEXT',
      'constraint' => '500',
      'null' => TRUE,
     ),
     'headerlogo' => array(
      'type' => 'VARCHAR',
      'constraint' => '250',
      'null' => TRUE,
     ),
     'footerlogo' => array(
      'type' => 'VARCHAR',
      'constraint' => '250',
      'null' => TRUE,
     ),
     'facebook' => array(
      'type' => 'VARCHAR',
      'constraint' => '250',
      'null' => TRUE,
     ),
      'twitter' => array(
      'type' => 'VARCHAR',
      'constraint' => '250',
      'null' => TRUE,
     ),
      'address' => array(
      'type' => 'TEXT',
      'constraint' => '500',
      'null' => TRUE,
     ),
      'telephone' => array(
      'type' => 'VARCHAR',
      'constraint' => '50',
      'null' => TRUE,
     ),
      'mobilecontact' => array(
      'type' => 'VARCHAR',
      'constraint' => '20',
      'null' => TRUE,
     ),
      'status' => array(
      'type' => 'SMALLINT',
      'constraint' => '2'
     ),
      'unavailablemessage' => array(
      'type' => 'TEXT',
      'constraint' => '500'
     )
    ));

    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table('tbl_setting');

    $data = array(
    	'id'                 => '1',
    	'title'              => 'Universitas Nasional Pasim',
    	'domain'             => 'http://pasim.ac.id',
    	'description'        => 'YAPASIM berdiri pada tanggal 11 Juni 1996 dengan akta notaris No 41 didepan notaris Dr Wiratni Ahmadi SH. Pendirian YAPASIM ini ditujukan untuk menjadi lembaga pengelola pendidikan tinggi. Oleh karena itu, setelah YAPASIM berdiri tidak berapa lama sesudah itu pendiri mengajukan ijin kepada Direktur Jenderal Pendidikan Tinggi Departemen Pendidikan dan Kebudayaan untuk mendirikan Sekolah Tinggi Ilmu Ekonomi yang diberi nama STIE PASIM.',
    	'owner'              => 'Ahmad Sanusi',
    	'support'            => 'Universitas Nasional Pasim',
    	'support_email'      => 'info@pasim.ac.id',
    	'issmtp'             => '1',
    	'smtphost'           => 'ssl://googlemail.com',
    	'smtpuser'           => 'asanus007@gmail.com',
    	'smtppass'           => 'fcpnlbocscjekqhr',
    	'smtpport'           => '465',
    	'location'           => 'Bandung',
    	'radius'             => '200',
    	'latitude'           => '-6.8946862',
    	'longitude'          => '107.57166489999997',
    	'timezone'           => 'Asia/Jakarta',
    	'language'           => 'en',
    	'dateformat'         => 'D, j F Y g:i A',
    	'metakeyword'        => 'Universitas Bandung, Universitas Nasional Pasim Bandung, unas pasim',
    	'headerlogo'         => 'logo.png',
    	'footerlogo'         => 'logo-putih.png',
    	'facebook'           => 'https://www.facebook.com/ahmad.dzidan',
    	'twitter'            => 'https://twitter.com/ahmaddzidan',
    	'address'            => 'Jl. Dakota No.8a Sukaraja Bandung',
    	'telephone'          => '02665285',
    	'mobilecontact'      => '085750994538',
	    'status'             => '1',
	    'unavailablemessage' => 'Sorry, this website is currently unavailable.'
    );

    $this->db->insert('tbl_setting', $data);

    echo "\n Setting table created";
    echo "\n";
   }

   // --------------------------------------------------------------------

   public function down()
   {
    $this->dbforge->drop_table('tbl_setting');
   }

   // --------------------------------------------------------------------
}
