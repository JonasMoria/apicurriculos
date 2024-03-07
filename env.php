<?php

// show errors
const ENABLE_SHOW_ERRORS = true;
putenv('DISPLAY_ERRORS_DETAILS=' . ENABLE_SHOW_ERRORS);

// Conect to database
const HOST = 'localhost';
const NAME = 'apicurriculums';
const USER = 'root';
const PASS = '';
const PORT = '3306';

putenv('DB_HOST=' . HOST);
putenv('DB_NAME=' . NAME);
putenv('DB_USER=' . USER);
putenv('DB_PASS=' . PASS);
putenv('DB_PORT=' . PORT);

putenv('JWT_KEY=' . '16d30fa8ea8f78555e30572d5a268f3e');
