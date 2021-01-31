<?php
require_once dirname(__FILE__) . '/../config.php';

if(!empty($_POST["keyword"])) {
    $query = "SELECT city FROM ".$tbl." WHERE city like '" . $_POST["keyword"] . "%' ORDER BY city";
    $result = $mysqli->query($query);
    if(!empty($result)) { ?>
        <ul id="country-list"><?php
            foreach($result as $country) { ?>
                <li onClick="selectCountry('<?php echo $country["city"]; ?>');"><?php echo $country["city"]; ?></li> <?php } ?>
        </ul><?php 
    }
}
?>