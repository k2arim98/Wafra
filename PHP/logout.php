<?php
session_start();
session_destroy();
header("Location: ../HTML/loginRegisterPage.html");
exit();
