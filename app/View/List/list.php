<form name="log_out" method="post" action="/logout">
    <input name="log_out" type="submit" id="log_out" value="Log Out">
</form>
<?php

echo " <a href='/add/0" . "' title='Add Child' data-toggle='tooltip'>Add first level parent</a>";

function buildTree($array, $currentParent, $currLevel = 0, $prevLevel = -1)
{
    foreach ($array as $categoryId => $category) {
        if ($currentParent == $category['parent_id']) {
            if ($currLevel > $prevLevel) {
                echo "<ol id='menutree'>";
            }
            if ($currLevel == $prevLevel) {
                echo "</li>";
            }
            echo '<li>'
                . $category["name"] . ' | Description: ' . $category['description'] .
                " <a href='/add/" . $categoryId . "' title='Add Child' data-toggle='tooltip'>Add child</a>" .
                " <a href='/edit/" . $categoryId . "' title='Edit Record' data-toggle='tooltip'>Edit</a>" .
                " <a href='/delete/" . $categoryId . "' title='Delete Record' data-toggle='tooltip'>Delete</a> ";
            if ($currLevel > $prevLevel) {
                $prevLevel = $currLevel;
            }
            $currLevel++;
            buildTree($array, $categoryId, $currLevel, $prevLevel);
            $currLevel--;
        }
    }
    if ($currLevel == $prevLevel) {
        echo "</li> </ol>";
    }
}

$conn = new mysqli('localhost', 'karlis', '@Tests12345', 'userData');

$query = "SELECT * FROM categories";
$result = mysqli_query($conn, $query);

$arrayCountry = array();

while ($row = mysqli_fetch_assoc($result)) {
    $arrayCountry[$row['id']] = array(
        "parent_id" => $row['parent_id'],
        "name" => $row['name'],
        "description" => $row['description']
    );
}

if (mysqli_num_rows($result) != 0) {
    buildTree($arrayCountry, 0);
}
?>
