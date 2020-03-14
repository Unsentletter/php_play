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
  print_r("!".rtrim($header)."!");
  $header_array = explode(",", rtrim($header));
  $csv = array();

  foreach ($lines as $line) {
    $line_array = explode(",", $line);
    $csv[] = array_combine($header_array, $line_array);  
  }

  print_r($csv);
  $date = array_column($csv, 'Date');
  array_multisort($date, $csv);

print_r("\n");
?> 