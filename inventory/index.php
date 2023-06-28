<?php 
include("./config/index.php");
$database = new Database();
$db = $database->getConnection();

// print_r($_REQUEST);

// $requestUrl = $_SERVER['REQUEST_URI'];
// $encodedNumber = basename(dirname($requestUrl));
// $id = base64_decode($encodedNumber);


function getMakeName($makeId, $db) {
    $makeQuery = 'SELECT * FROM carlisting_multi_lang WHERE foreign_id = "' . $makeId . '" AND model = "pjMake" AND field = "name" AND locale = 1';
    $makeStmt = $db->query($makeQuery);
    $make = $makeStmt->fetch(PDO::FETCH_ASSOC);
    return $make["content"];
}

function getModelName($modelId, $db) {
    $modelQuery = 'SELECT * FROM carlisting_multi_lang WHERE foreign_id = "' . $modelId . '" AND model = "pjCarMod" AND field = "name" AND locale = 1';
    $modelStmt = $db->query($modelQuery);
    $model = $modelStmt->fetch(PDO::FETCH_ASSOC);
    return $model["content"];
}

function getFeature($featureId, $db) {
    $featureQuery = 'SELECT * FROM carlisting_multi_lang WHERE foreign_id = "' . $featureId . '" AND model = "pjFeature" AND field = "name" AND locale = 1';
    $featureStmt = $db->query($featureQuery);
    $feature = $featureStmt->fetch(PDO::FETCH_ASSOC);
    return $feature["content"];
}

function getImages($itemId, $db) {
    $imageQuery = 'SELECT medium_path FROM carlisting_plugin_gallery WHERE foreign_id ="' . $itemId . '"' ;
    $imageStmt = $db->query($imageQuery);
    $imageRows = $imageStmt->fetchAll(PDO::FETCH_ASSOC);
    
    $newImageRows = array();
    foreach ($imageRows as $item) {
        $newImageRows[] = "https://uploadvehicles.com/" . $item["medium_path"];
    }

    $imagePath = implode('|', $newImageRows);

    return $imagePath;
}

function getDescription($itemId, $db) {
    $descQuery = 'SELECT * FROM carlisting_multi_lang WHERE foreign_id="' . $itemId . '" AND model = "pjListing" AND field = "description"';
    $descStmt = $db->query($descQuery);
    $descRows = $descStmt->fetch(PDO::FETCH_ASSOC);

    return $descRows["content"];
}

$userQuery = 'SELECT * FROM carlisting_users WHERE name ="' . $_REQUEST["username"]. '"';
$userStmt = $db->query($userQuery);
$userRow = $userStmt->fetch(PDO::FETCH_ASSOC);

$listQuery = 'SELECT l.* FROM carlisting_listings l JOIN carlisting_users u ON l.owner_id = u.id WHERE l.owner_id = "'. $userRow["id"] .'"';
$listStmt = $db->query($listQuery);
$listRows = $listStmt->fetchAll(PDO::FETCH_ASSOC);

// echo '<pre>';
// print_r($listRows);

$itemsArray = array();
foreach($listRows as $item) {
    $itemArray = array();
    if($item["vin"] != '') {
        $itemArray["VIN"] = $item["vin"];
    }else {
        $itemArray["VIN"] = $item["listing_refid"];
    }
    $itemArray["Type"] = 'U';
    $itemArray["Make"] = getMakeName($item["make_id"], $db);
    $itemArray["Model"] = getModelName($item["model_id"], $db);
    $itemArray["ModelYear"] = $item["year"];
    $itemArray["Trim"] = $item["trim"];
    $itemArray["BodyStyle"] = getFeature($item["feature_type_id"], $db);
    $itemArray["Mileage"] = $item["listing_mileage"];
    $itemArray["EngineDescription"] = '';
    $itemArray["Cylinders"] = getFeature($item["feature_class_id"], $db);
    $itemArray["FuelType"] = getFeature($item["feature_fuel_id"], $db);
    $itemArray["Transmission"] = getFeature($item["feature_gearbox_id"], $db);
    $itemArray["Price"] = $item["listing_price"];
    $itemArray["ExteriorColor"] = getFeature($item["feature_colors_id"], $db);
    $itemArray["InteriorColor"] = '';
    $itemArray["OptionText"] = '';
    $itemArray["Description"] = getDescription($item["id"], $db);
    $itemArray["images"] = getImages($item["id"], $db);

    // $itemArray["dealer"] = $item["name"];
    // $itemArray["dealer_email"] = $item["email"];
    // $itemArray["deale_phone"] = $item["contact_phone"];
    // $itemArray["deale_url"] = $item["contact_url"];
    // $itemArray["zip"] = $item["address_postcode"];
    // $itemArray["address"] = $item["address_content"];
    // $itemArray["country"] = 'USA';
    // $itemArray["city"] = $item["address_city"];
    // $itemArray["state"] = $item["address_state"];
    // $itemArray["doors"] = getFeature($item["feature_doors_id"], $db);
    
    $itemsArray[] = $itemArray;
}

// echo '<pre>';
// print_r($itemsArray);

// Generate the CSV data
$csvData = '';
$csvData .= '"'.implode('","', array_keys($itemsArray[0])) . "\"\n";
foreach ($itemsArray as $row) {
    $csvData .= '"'.implode('","', $row) . "\"\n";
}

// Set headers for CSV download
// header('Content-Type: text/csv');
// header('Content-Disposition: attachment; filename="table.csv"');

// Output the CSV data
echo $csvData;

?>