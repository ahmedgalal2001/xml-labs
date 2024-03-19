<?php
$xmlFile = 'file.xml';

if (file_exists($xmlFile)) {
    $xml = simplexml_load_file($xmlFile);
} else {
    // If the file doesn't exist, create a new XML structure
    $xml = new SimpleXMLElement('<?xml version="1.0"?><entries></entries>');
}

function getCurrentIndex()
{
    return isset($_SESSION['currentIndex']) ? $_SESSION['currentIndex'] : 0;
}

function setCurrentIndex($index)
{
    $_SESSION['currentIndex'] = $index;
}

function getNextUser($xml)
{
    $currentIndex = getCurrentIndex();
    if ($currentIndex < count($xml->user) - 1) {
        setCurrentIndex($currentIndex + 1);
    }
}

function getPreviousUser()
{
    $currentIndex = getCurrentIndex();
    if ($currentIndex > 0) {
        setCurrentIndex($currentIndex - 1);
    }
}
function displayUserDetails($xml)
{
    $currentIndex = getCurrentIndex();
    $user = $xml->user[$currentIndex];
    return [
        'name' => (string)$user->name,
        'email' => (string)$user->email,
        'address' => (string)$user->address,
        'phone' => (string)$user->phone
    ];
}

function insertEntry($xml, $name, $email, $address, $phone)
{
    $entry = $xml->addChild('user');
    $entry->addChild('name', $name);
    $entry->addChild('email', $email);
    $entry->addChild('address', $address);
    $entry->addChild('phone', $phone);

    // Save changes back to the file
    $xml->asXML('file.xml');
}

function updateEntry($xml, $email, $newName, $newAddress, $newPhone)
{
    foreach ($xml->user as $user) {
        if ($user->email == $email) {
            $user->name = $newName;
            $user->address = $newAddress;
            $user->phone = $newPhone;
            // Save changes back to the file
            $xml->asXML('file.xml');
            return;
        }
    }
    echo "User with email $email not found.";
}

function deleteEntry($xml, $email)
{
    foreach ($xml->user as $user) {
        if ($user->email == $email) {
            $dom = dom_import_simplexml($user);
            $dom->parentNode->removeChild($dom);
            // Save changes back to the file
            $xml->asXML('file.xml');
            return;
        }
    }
    echo "User with email $email not found.";
}

function searchByName($xml, $name)
{
    $results = [];
    foreach ($xml->user as $user) {
        if (stripos($user->name, $name) !== false) {
            $results[] = $user;
        }
    }
    return $results;
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["insert"])) {
        insertEntry($xml, $_POST["name"], $_POST["email"], $_POST["address"], $_POST["phone"]);
    } elseif (isset($_POST["update"])) {
        updateEntry($xml, $_POST["email"], $_POST["name"],  $_POST["address"], $_POST["phone"]);
    } elseif (isset($_POST["delete"])) {
        deleteEntry($xml, $_POST["email"]);
    } elseif (isset($_POST["search"])) {
        $name = $_POST["name"];
        $results = searchByName($xml, $name);
        // Display search results
        if (!empty($results)) {
            echo "<h3>Search Results:</h3>";
            foreach ($results as $user) {
                echo "Name: $user->name, Email: $user->email, Address: $user->address, Phone: $user->phone <br>";
            }
        } else {
            echo "No results found for name: $name";
        }
    } elseif (isset($_POST["next"])) {
        getNextUser($xml);
    } elseif (isset($_POST["previous"])) {
        getPreviousUser();
    }
}

$userDetails = displayUserDetails($xml);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management</title>
    <style>
        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        button {
            padding: 8px 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <form action="" method="post">
        <h2>Library Management</h2>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" placeholder="Enter name" value="<?php echo $userDetails['name']; ?>">

        <label for="email">Email:</label>
        <input type="text" id="email" name="email" placeholder="Enter email" value="<?php echo $userDetails['email']; ?>">

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" placeholder="Enter address" value="<?php echo $userDetails['address']; ?>">

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" placeholder="Enter phone" value="<?php echo $userDetails['phone']; ?>">


        <button type="submit" name="previous">Previous</button>
        <button type="submit" name="insert">Insert</button>
        <button type="submit" name="update">Update</button>
        <button type="submit" name="delete">Delete</button>
        <button type="submit" name="search">Search</button>
        <button type="submit" name="next">Next</button>
    </form>

</body>

</html>