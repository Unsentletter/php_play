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
    <link rel="stylesheet" type="text/css" media="screen" href="index.css" />
    <script src="main.js"></script>
  </head>
  <body>

<h1>Upload new CSV</h1>
  <form action="upload.php" method="POST" enctype="multipart/form-data" class="form">
    Select CSV to upload:
    <div class="buttonWrapper">
      <input type="file" name="uploadedFile" class="button">
      <input type="submit" value="Upload File" name="uploadBtn" class="uploadButton">
    </div>
  </form>
  <?php
    if (isset($_SESSION['message']) && $_SESSION['message'])
    {
      printf('<b>%s</b>', $_SESSION['message']);
      unset($_SESSION['message']);
    }
  ?>
  <?php
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
      echo '<tbody class="dataTable">';
      echo '<table ><tr>';
      while ($header_row_column < count($header_array)) {
        echo '<th class="headerItem">'.$header_array[$header_row_column].'</th>';
        $header_row_column++;
      }
      echo '</tr>';
      while($row < count($data)) {
        if ($row % 2 == 0) {
          echo '<tr class="dataRowEven">';
        } else {
          echo '<tr class="dataRowOdd">';
        }
        
        for ($i=0; $i < count($data[$row]); $i++) {
          $header_name = $header_array[$i];
          if ($header_name == 'Amount') {
            if ($data[$row][$header_name] < 0) {
              echo '<td class="negativeAmount">'.$data[$row][$header_name].'</td>';  
            } else {
              echo '<td >'.$data[$row][$header_name].'</td>';
            }
          } else {
            echo '<td>'.$data[$row][$header_name].'</td>';
          }
        }
        echo '</tr>';
        $row++;
      }
      echo '</table>';
      echo '</tbody>';
    }

    public static function format_values($data) 
    {
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
  
  function sort_by_time($a, $b) 
  {
    return $b - $a;
  }

  $files = glob("./uploads/*.csv");
  $times = array();
  foreach($files as $file) {
    $times[] = filemtime($file);
  }

  $unsorted_times_array = $times;
  usort($times, 'sort_by_time');
  $key = array_search($times[0], $unsorted_times_array);

  if (count($files) == 0) return;

  if (($handle = fopen($files[$key], "r")) === FALSE)
    throw new Exception("Couldn't open .csv");

  $csv = explode( "\n", rtrim(file_get_contents($files[$key])));
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
  $data = EditData::format_values($data);
  EditData::form_table($header_row_column, $header_array, $data);
?>
  </body>
</html>
