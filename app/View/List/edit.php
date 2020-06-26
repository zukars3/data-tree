<h1>EDIT <?php echo $element['name']; ?></h1>

<form action="<?php echo '/edit/' . $element['id'] ?>" method="post">
    <label for="name">Name:</label><br>
    <input type="text" id="name" name="name" value="<?php echo $element['name'] ?>"><br><br>
    <label for="description">Description:</label><br>
    <input type="text" id="description" name="description" value="<?php echo $element['description'] ?>"><br><br>
    <input type="submit" value="Update">
</form>