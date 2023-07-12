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
    if(trim($item["vin"]) != '') {
        $itemArray["VIN"] = $item["vin"];
    }else {
        $itemArray["VIN"] = $item["listing_refid"];
    }
    $itemArray["Type"] = 'U';
    $itemArray["Stock"] = '';
    $itemArray["Make"] = getMakeName($item["make_id"], $db);
    $itemArray["Model"] = getModelName($item["model_id"], $db);
    $itemArray["ModelYear"] = $item["year"];
    $itemArray["Trim"] = str_replace(",", ";", $item["trim"]);
    $itemArray["BodyStyle"] = str_replace(",", ";", getFeature($item["feature_type_id"], $db));
    $itemArray["Mileage"] = str_replace(",", ";", $item["listing_mileage"]);
    $itemArray["Cylinders"] = getFeature($item["feature_class_id"], $db); //Engine
    $itemArray["EngineDescription"] = '';
    $itemArray["FuelType"] = str_replace(",", ";", getFeature($item["feature_fuel_id"], $db));
    $itemArray["Transmission"] = str_replace(",", ";", getFeature($item["feature_gearbox_id"], $db));
    $itemArray["Price"] = str_replace(",", ";", $item["listing_price"]);
    $itemArray["ExteriorColor"] = getFeature($item["feature_colors_id"], $db);
    $itemArray["InteriorColor"] = '';
    $itemArray["OptionText"] = '';
    $itemArray["images"] = getImages($item["id"], $db);
    $itemArray["Description"] = getDescription($item["id"], $db);
    
    // if( $itemArray["images"] != '') {
    //     $itemsArray[] = $itemArray;
    // }
    $itemsArray[] = $itemArray;
}

// echo '<pre>';
// print_r($itemsArray);
// echo count($itemsArray);

// Generate the CSV data
$csvData = '';
$csvData .= '"'.implode('","', array_keys($itemsArray[0])) . "\"\n";
// echo $csvData;
foreach ($itemsArray as $row) {
    $csvData .= '"'.implode('","', $row) . "\"\n";
}

echo $csvData;

?>