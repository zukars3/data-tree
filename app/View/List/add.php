<h1>ADD <?php if (isset($element)) {
        echo $element['name'];
    } else {
        echo 'parent';
    } ?></h1>

<form action="<?php if (isset($element)) {
    echo '/add/' . $element['id'];
} else {
    echo '/add/0';
} ?>" method="post">
    <label for="name">Name:</label><br>
    <input type="text" id="name" name="name"><br><br>
    <label for="description">Description:</label><br>
    <input type="text" id="description" name="description"><br><br>
    <input type="submit" value="Create">
</form>