<?php
  // error_reporting(E_ALL);

  function remove_utf8_bom($text)
    {
        $bom = pack('H*','EFBBBF');
        $text = preg_replace("/^$bom/", '', $text);
        return $text;
    }

  if (($handle = fopen("BankTransactions.csv", "r")) === FALSE)
    throw new Exception("Couldn't open .csv");

  $data = explode( "\n", file_get_contents( 'BankTransactions.csv' ) );
  $lines = remove_utf8_bom($data);
  $header = array_shift($lines);
  $header_array = explode(",", $header);
  $csv = array();

  foreach ($lines as $row) {
    $row_array = explode(",", $row);
    $csv[] = array_combine($header_array, $row_array);  
  }

  print_r($csv[0]['Date']);


print_r("\n");
?>