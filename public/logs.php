<?php
require_once(__DIR__.'/config.php');

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
} 


if (in_array($_SERVER['PHP_AUTH_USER'],$auth ) && $_SERVER['PHP_AUTH_PW'] === $auth[$_SERVER['PHP_AUTH_USER']]):
?>
<html>
<head>
<script src="//code.jquery.com/jquery-3.3.1.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<link rel = "stylesheet"
   type = "text/css" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
</head>
<body>
<table id="logs">
<thead>
    <tr>
    <th>Date</th>
    <th>User-Agent</th>
    <th>IP</th>
    <th>Detect agent</th>
    <th>Detect ip</th>
    </tr>
</thead>
<tbody>
<?php
$handle = fopen($log_dir.'/out.log', "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
       $data = explode('---',$line);
       echo '<tr>';
       echo '<td>'. date('Y-m-d H:i:s',$data[0]).'</td>'; //Date
       echo '<td>'. $data[1].'</td>'; //U-A
       echo '<td>'. $data[2].'</td>'; //IP
       echo '<td>'.($data[3]& 1 ? "Agent": "No").'</td>';
       echo '<td>'.($data[3]& 2 ? "Net": "No").'</td>';
       echo '</tr>';
    }

    fclose($handle);
} else {
    // error opening the file.
} 
?>
</tbody>
</table>
<script>
jQuery(document).ready(function() {
    $('#logs').DataTable();
} )
</script>

</body>
</html>
<?php endif;?>
