CREATE TABLE IF NOT EXISTS `s281_photos` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) default '',
  `filename` varchar(255) default '',
  `description` text NOT NULL,
  `when` int(11) NOT NULL default '0',
  `comments_count` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `s281_photos` (`title`, `filename`, `description`, `when`) VALUES 
('Item #1', 'photo1.jpg', 'Description of Item #1', UNIX_TIMESTAMP()),
('Item #2', 'photo2.jpg', 'Description of Item #2', UNIX_TIMESTAMP()+1),
('Item #3', 'photo3.jpg', 'Description of Item #3', UNIX_TIMESTAMP()+2),
('Item #4', 'photo4.jpg', 'Description of Item #4', UNIX_TIMESTAMP()+3),
('Item #5', 'photo5.jpg', 'Description of Item #5', UNIX_TIMESTAMP()+4),
('Item #6', 'photo6.jpg', 'Description of Item #6', UNIX_TIMESTAMP()+5),
('Item #7', 'photo7.jpg', 'Description of Item #7', UNIX_TIMESTAMP()+6),
('Item #8', 'photo8.jpg', 'Description of Item #8', UNIX_TIMESTAMP()+7),
('Item #9', 'photo9.jpg', 'Description of Item #9', UNIX_TIMESTAMP()+8),
('Item #10', 'photo10.jpg', 'Description of Item #10', UNIX_TIMESTAMP()+9);

CREATE TABLE IF NOT EXISTS `s281_items_cmts` (
  `c_id` int(11) NOT NULL AUTO_INCREMENT ,
  `c_item_id` int(12) NOT NULL default '0',
  `c_ip` varchar(20) default NULL,
  `c_name` varchar(64) default '',
  `c_text` text NOT NULL ,
  `c_when` int(11) NOT NULL default '0',
  PRIMARY KEY (`c_id`),
  KEY `c_item_id` (`c_item_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;