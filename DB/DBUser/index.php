<?php
require_once ( dirname( __FILE__ ) . '/DBUser.php' );

Logger::Log( 
            'begin DBUser',
            LogLevel::DEBUG
            );
new DBUser();

Logger::Log( 
            'end DBUser',
            LogLevel::DEBUG
            );
?>