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
    function console_log($output, $with_script_tags = true) {
      $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . 
  ');';
      if ($with_script_tags) {
          $js_code = '<script>' . $js_code . '</script>';
      }
      echo $js_code;
  }

    class bankTransactions {
      var $date, $transaction_number, $customer_number, $reference, $amount;
      public function bankTransactions($data) {
        console_log($data);
        $this->Date = $data['Date'];
        $this->transation_number = $data['TransactionNumber'];
        $this->customer_number = $data['CustomerNumber'];
        $this->reference = $data['Reference'];
        $this->amount = $data['Amount'];
      }
    }

    function convertToObject($data) {
      $class_object = new bankTransactions($data);
      // console_log($class_object);
      return $class_object;
    }

    function comparator($object1, $object2) {
      return $object1->date > $object2->date;
    }



    $row = 1;
    // if(($handle = fopen("BankTransactions.csv", "r")) !== FALSE) {
      echo '<table border="1"';

      // $lines = explode( "\n", file_get_contents( 'BankTransactions.csv' ) );
      // $headers = str_getcsv( array_shift( $lines ) );
      // $data = array();
      // foreach ( $lines as $line ) {
      //   $row = array();
      //   foreach ( str_getcsv( $line ) as $key => $field )
      //     $row[ $headers[ $key ] ] = $field;
      //   $row = array_filter( $row );
      //   $data[] = $row;
      // }

      function remove_utf8_bom($text)
      {
          $bom = pack('H*','EFBBBF');
          $text = preg_replace("/^$bom/", '', $text);
          return $text;
      }
  
    if (($handle = fopen("BankTransactions.csv", "r")) === FALSE)
      throw new Exception("Couldn't open .csv");
  
    $csv = explode( "\n", file_get_contents( 'BankTransactions.csv' ) );
    $lines = remove_utf8_bom($csv);
    $header = array_shift($lines);
    $header_array = explode(",", $header);
    console_log($header_array);
    $data = array();
  
    foreach ($lines as $row) {
      $row_array = explode(",", $row);
      console_log($row_array);

      $data[] = array_combine($header_array, $row_array);  
    }

    console_log($data);
      
      // while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        $num = count($data);
        // console_log($data);
        convertToObject($data);
        if($row == 1) {
          echo '<thead><tr>';
        } else {
          echo '<tr>';
        }

        for($c=0; $c < $num; $c++) {
          // if(empty($data[$c])) {
          //   $value = "&nbsp;";
          // } else {
          //   $value = $data[$c];
          // }
          // if($row == 1) {
          //   $split_row = explode(",", $value);
          //   $row_num = count($split_row);

          //   for($i=0; $i < $row_num; $i++) {
          //     echo '<th>'.$split_row[$i].'</th>';
          //   }
          // } else {
          //   $split_row = explode(",", $value);
          //   $row_num = count($split_row);

          //   for($i=0; $i < $row_num; $i++) {
          //     echo '<td>'.$split_row[$i].'</td>';
          //   }
          // }
        }
        if($row == 1) {
          echo '</tr></thead?><tbody>';
        } else {
          echo '</tr>';
        }
        $row++;
      // }

      echo '<tbody></table>';
      // fclose($handle);
    // }
?>
  </body>
</html>
