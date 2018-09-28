<?php
/**
 * App initiated
 */
$checkAdminLogin='no';
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
        <link rel="shortcut icon" href="<?php echo ASSET_PATH.'images/'; ?>favicon.png">
		<title><?php echo SITE_NAME; ?> | Content Management System</title>
		<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_PATH.'style/'; ?>login.css" />
        <link rel="stylesheet" href="<?php echo ADMIN_PATH.'style/'; ?>font-awesome.css" />
    	<link href='http://fonts.googleapis.com/css?family=PT+Sans+Caption:400,700' rel='stylesheet' type='text/css'>
	</head>
	<body>
		<div id="wrap">
			<table cellspacing="0" cellpadding="0" class="table">
				<tr>
					<td class="text-center" style="vertical-align:middle;">
						<a href="<?php echo ADMIN_PATH; ?>"><img src="<?php echo ADMIN_PATH.'images/logo/'.TABLE_PREFIX.'dark_logo.png'; ?>" alt="Artistic Academy" /></a>
						<h1>404: Page Not Found</h1>
					</td>
                </tr>
            </table>
		</div>
	</body>
</html>