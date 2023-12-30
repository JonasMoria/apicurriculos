<?php

// show errors
const ENABLE_SHOW_ERRORS = true;
putenv('DISPLAY_ERRORS_DETAILS=' . ENABLE_SHOW_ERRORS);

// Conect to database
const HOST = 'localhost';
const NAME = 'apicurriculos';
const USER = 'root';
const PASS = '';
const PORT = '3306';

putenv('DB_HOST=' . HOST);
putenv('DB_NAME=' . NAME);
putenv('DB_USER=' . USER);
putenv('DB_PASS=' . PASS);
putenv('DB_PORT=' . PORT);

