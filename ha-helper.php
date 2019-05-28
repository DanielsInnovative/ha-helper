<?php

  define("HAHELPER_DIR", "/var/lib/ha-helper/");
  define("HAHELPER_VERSION", "1.0.0528");

  $response = [
//    "device" => "",
//    "host" => "",
//    "cmd" => "",
    "status" => 200,
    "error" => "",
"debug" => "",    
    "date" => date("c"),
    "version" => HAHELPER_VERSION
  ];
  $parameters = [ "cmd" => "", "device" => "", "host" => "" ];

  try {
    foreach ($parameters as $parameter => $value) {
      if (isset($_GET[$parameter])) {
	$response[$parameter] = $_GET[$parameter];
        $parameters[$parameter] = $_GET[$parameter];
      } else {
	$missingParameter = "Missing required parameter ".strtoupper($parameter);
      }
      if (!preg_match('/^[a-zA-Z0-9_\-\.\ ]+$/', $parameters[$parameter])) {
        $invalidParameter = "Invalid character(s) in ".strtoupper($parameter);
      }
    }
    if (isset($missingParameter)) {
      $response["status"] = 400;
      throw new Exception($missingParameter);
    }
    if (isset($invalidParameter)) {
      $response["status"] = 400;
      throw new Exception($invalidParameter);
    }

    $deviceList = scandir(HAHELPER_DIR."/connectors/");
    while (substr($dir[0], 0, 1) == "." || substr($dir[0], 0, 1) == "_") array_shift($deviceList);
    if (!in_array($parameters["device"], $deviceList)) {
      $response["status"] = 500;
      throw new Exception("DEVICE definition not found, ".$parameters["device"]);
    }
    include(HAHELPER_DIR."/connectors/".$parameters["device"]);
    $response["connector_version"] = $helper->version;

    if (!$helper->check_dependencies()) {
      $response["status"] = 500;
      throw new Exception("Missing dependency for DEVICE ".$parameters["device"]);
    }
    if (!$helper->check_command($parameters["cmd"])) {
      $response["status"] = 500;
      throw new Exception("Malformed or illegal CMD");
    }

    $helper->execute_command($parameters["host"], $parameters["cmd"]);
    $response["output"] = $helper->output;

  } catch (Exception $e) {
    $response["error"] = $e->getMessage();
  } finally {
    http_response_code($response["status"]);
    header('Content-Type: application/json');
    printf("%s", json_encode($response, JSON_PRETTY_PRINT));
  }
?>
