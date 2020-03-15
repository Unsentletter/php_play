<?php
session_start(); 
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
  </head>
  <body>
  <?php
    if (isset($_SESSION['message']) && $_SESSION['message'])
    {
      printf('<b>%s</b>', $_SESSION['message']);
      unset($_SESSION['message']);
    }
  ?>

  <form action="upload.php" method="POST" enctype="multipart/form-data">
    Upload shit:
    <input type="file" name="uploadedFile">
    <input type="submit" value="Upload File" name="uploadBtn">
  </form>

    <?php
    function console_log($output, $with_script_tags = true) {
      $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . 
  ');';
      if ($with_script_tags) {
          $js_code = '<script>' . $js_code . '</script>';
      }
      echo $js_code;
  };

  class EditData {
    public static function remove_utf8_bom($text)
    {
        $bom = pack('H*','EFBBBF');
        $text = preg_replace("/^$bom/", '', $text);
        return $text;
    }

    public static function form_table($header_row_column, $header_array, $data)
    {
      $row = 0;
      echo '<tbody border="1">';
      echo '<table ><tr>';
      while ($header_row_column < count($header_array)) {
        echo '<th>'.$header_array[$header_row_column].'</th>';
        $header_row_column++;
      }
      echo '</tr>';
      while($row < count($data)) {
        echo '<tr>';
        for ($i=0; $i < count($data[$row]); $i++) {
          $header_name = $header_array[$i];
          echo '<td>'.$data[$row][$header_name].'</td>';
        }
        echo '</tr>';
        $row++;
      }
      echo '</table>';
      echo '</tbody>';
    }

    public static function format_values($data) {
      for ($i=0; $i < count($data); $i++) {
        $data[$i]['Date'] = date('Y-m-d h:iA', strtotime($data[$i]['Date']));
        $data[$i]['TransactionCode'] = strval($data[$i]['TransactionCode']);
        // Need to validate this
        $data[$i]['CustomerNumber'] = intval($data[$i]['CustomerNumber']);
        $data[$i]['Reference'] = strval($data[$i]['Reference']);
        $data[$i]['Amount'] = number_format(round(floatval($data[$i]['Amount']), 2), 2, '.', '');
      }
      return $data;
    }
  }
  
  if (($handle = fopen("BankTransactions.csv", "r")) === FALSE)
    throw new Exception("Couldn't open .csv");

  $csv = explode( "\n", file_get_contents( 'BankTransactions.csv' ) );
  $lines = EditData::remove_utf8_bom($csv);
  $header = array_shift($lines);
  $header_array = explode(",", rtrim($header));
  $data = array();

  foreach ($lines as $line) {
    $line_array = explode(",", $line);
    $data[] = array_combine($header_array, $line_array);  
  }

  $date = array_column($data, 'Date');
  array_multisort($date, $data);
  $header_row_column = 0;
  // console_log($data);

  $data = EditData::format_values($data);
  EditData::form_table($header_row_column, $header_array, $data);
?>
  </body>
</html>
