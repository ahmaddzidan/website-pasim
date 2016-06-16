 <?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_ci_sessions extends CI_Migration {

 // --------------------------------------------------------------------

 public function up()
 {
    echo "\nCreating ci_sessions table...";

    $this->dbforge->add_field(array(
     'session_id' => array(
      'type' => 'VARCHAR',
      'constraint' => 40,
      'default' => 0,
      'null' => FALSE
     ),
     'ip_address' => array(
      'type' => 'VARCHAR',
      'constraint' => 16,
      'default' => 0
     ),
     'user_agent' => array(
      'type' => 'VARCHAR',
      'constraint' => 120
     ),
     'last_activity' => array(
      'type' => 'INT',
      'constraint' => 10,
      'unsigned' => TRUE,
      'default' => 0
     ),
     'user_data' => array(
      'type' => 'text'
     ),
    ));

    $this->dbforge->add_key('session_id', TRUE); // primary key
    $this->dbforge->add_key('last_activity');
    $this->dbforge->create_table('ci_sessions');
    $this->db->query('ALTER TABLE `ci_sessions` ADD KEY `last_activity_idx` (`last_activity`)');
    echo "\nci_sessions table created";
    echo "\n";
   }

   // --------------------------------------------------------------------

   public function down()
   {
    $this->dbforge->drop_table('ci_sessions');
   }

   // --------------------------------------------------------------------
}