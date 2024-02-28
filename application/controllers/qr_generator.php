<?php
require_once(APPPATH.'third_party/phpqrcode/qrlib.php');

QRcode::png($_GET['code']);