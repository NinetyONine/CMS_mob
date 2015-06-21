<?php

/**
 * @file
 * Module initialization class.
 */

class RzboardInit {
  /**
   * Module details and info.
   */
  public static $aRzInfo = array(
    'module' => "rzboard",
    'title' => "Boards",
    'desc' => "Boards without media server",
    'version' => "1.0.0",
    'author' => "rayzzz.com",
    'email' => "rayzexpert@gmail.com",
    'url' => "http://rayzzz.com/redirect.php?action=about&widget=board&target=rz",
    'min_width' => "800",
    'width' => "100%",
    'height' => "600",
  );
  /**
   * Module installation tables details.
   */
  public static $aDBTables = array(
    'rzboard_boards' => array(
      'fields' => array(
        'ID' => array(
          'type' => 'int',
          'not null' => TRUE,
          'auto_increment' => TRUE,
          'length' => 11,
        ),
        'Name' => array(
          'type' => 'varchar',
          'not null' => TRUE,
          'default' => '',
          'length' => 255,
        ),
        'Password' => array(
          'type' => 'varchar',
          'not null' => TRUE,
          'default' => '',
          'length' => 255,
        ),
        'OwnerID' => array(
          'type' => 'varchar',
          'not null' => TRUE,
          'default' => '',
          'length' => 20,
        ),
        'Time' => array(
          'type' => 'int',
          'not null' => TRUE,
          'default' => 0,
          'length' => 11,
        ),
        'Status' => array(
          'type' => 'varchar',
          'not null' => TRUE,
          'default' => 'normal',
          'length' => 7,
        ),
      ),
      'primary key' => array('ID'),
    ),

    'rzboard_users' => array(
      'fields' => array(
        'ID' => array(
          'type' => 'int',
          'not null' => TRUE,
          'auto_increment' => TRUE,
          'length' => 11,
        ),
        'Board' => array(
          'type' => 'int',
          'not null' => TRUE,
          'default' => 0,
          'length' => 11,
        ),
        'User' => array(
          'type' => 'varchar',
          'not null' => TRUE,
          'default' => '',
          'length' => 20,
        ),
        'Time' => array(
          'type' => 'int',
          'not null' => TRUE,
          'default' => 0,
          'length' => 11,
        ),
        'Status' => array(
          'type' => 'varchar',
          'not null' => TRUE,
          'default' => 'normal',
          'length' => 6,
        ),
      ),
      'primary key' => array('ID'),
    ),

    'rzboard_current_users' => array(
      'fields' => array(
        'ID' => array(
          'type' => 'varchar',
          'not null' => TRUE,
          'length' => 20,
        ),
        'Nick' => array(
          'type' => 'varchar',
          'not null' => TRUE,
          'default' => '',
          'length' => 36,
        ),
        'Sex' => array(
          'type' => 'varchar',
          'not null' => TRUE,
          'default' => 'M',
          'length' => 1,
        ),
        'Age' => array(
          'type' => 'int',
          'not null' => TRUE,
          'default' => 0,
          'length' => 11,
        ),
        'Photo' => array(
          'type' => 'varchar',
          'not null' => TRUE,
          'default' => '',
          'length' => 255,
        ),
        'Profile' => array(
          'type' => 'varchar',
          'not null' => TRUE,
          'default' => '',
          'length' => 255,
        ),
        'Description' => array(
          'type' => 'text',
          'not null' => TRUE,
        ),
        'Time' => array(
          'type' => 'int',
          'not null' => TRUE,
          'default' => 0,
          'length' => 11,
        ),
        'Status' => array(
          'type' => 'varchar',
          'not null' => TRUE,
          'default' => 'new',
          'length' => 6,
        ),
      ),
      'primary key' => array('ID'),
    ),
  );
}
