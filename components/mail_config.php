<?php
/* -----------------------------------------------------------
   EstateFlow - email (SMTP) settings
   -----------------------------------------------------------
   These settings let the website send real emails (for example
   password-reset links) through a mailbox created in cPanel.

   HOW TO SET UP (one time, in cPanel):
   1. cPanel -> Email Accounts -> Create
      - Email:    no-reply@estateflow.it.com
      - Password: choose a strong password
   2. Put that same password below in EF_SMTP_PASS
      (edit the file DIRECTLY on the server, in cPanel File
      Manager - NEVER upload or push the real password to
      GitHub or include it in a zip).
   ----------------------------------------------------------- */

define('EF_SMTP_HOST', 'premium77.web-hosting.com'); // Namecheap server
define('EF_SMTP_PORT', 465);                       // 465 = SSL
define('EF_SMTP_USER', 'no-reply@estateflow.it.com');
define('EF_SMTP_PASS', 'PUT-THE-MAILBOX-PASSWORD-HERE');
define('EF_MAIL_FROM_NAME', 'EstateFlow');
