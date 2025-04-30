<?php
session_start();
session_destroy();
header("Location: /Wafra/HTML/loginRegisterPage.html");
exit();
