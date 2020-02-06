<?php

class Refrigerator {
    private $quantity;

    public function __construct() {
        if (!session_id()) {
            session_start();
        }
        if(!isset($_SESSION["refrigeratorDoor"])){
           $_SESSION["refrigeratorDoor"] = 0;
        }
    }

    public function setQuantity($value) {
        $this->quantity=$value;
    }
    public function getQuantity() {
        return $this->quantity;
    }

    public function process(){
        $newQuantity = $this->getQuantity();

        if($newQuantity>0 && $newQuantity<20){ //doluluk kontrolünü yapar
            echo "Birinci raf kısmen dolu";
        } elseif($newQuantity==20) {
            echo "Birinci raf dolu";
        } elseif($newQuantity>20 && $newQuantity<40){
            echo "İkinci raf kısmen dolu";
        } elseif($newQuantity==40){
            echo "İkinci raf dolu";
        } elseif($newQuantity>40 && $newQuantity<60) {
            echo "Üçüncü raf kısmen dolu";
        } elseif($newQuantity<=0) {
           echo "Dolap Boş <br />";
        } else {
            echo "Dolap Dolu <br />";
        }
        setcookie ("Quantity", $newQuantity);
        echo "<br />";
        echo "Dolaptaki Kutu Adedi: " . $newQuantity . "<br/>";
        $_SESSION["refrigeratorDoor"] = 0;
    }

    public function addItem($value){ // Dolaba kutu ekleme
        if(!isset($_COOKIE["Quantity"])){
            $_COOKIE["Quantity"] = 1;
        }
        if(($_COOKIE["Quantity"]>=0 && $_COOKIE["Quantity"]<60)){
            $addItem = $_COOKIE["Quantity"] + $value;
            $this->setQuantity($addItem);
        } else {
            $addItem = 60;
        }

        $this->setQuantity($addItem);

        $this->process();
    }
    public function deleteItem($value){ //Dolaptan kutu çıkarma
        if(isset($_COOKIE["Quantity"]) && ($_COOKIE["Quantity"]>0)){
            $deleteItem = $_COOKIE["Quantity"] - $value;
        } else {
            $deleteItem = 0;
        }
        $this->setQuantity($deleteItem);
        $this->process();
    }
}


$refrigerator = new Refrigerator();
if(array_key_exists('refrigeratorDoor', $_POST)){
    $_SESSION["refrigeratorDoor"] = 1; // Dolap kapağı açıksa değer 1 olur
}
if(array_key_exists('add', $_POST) && (isset($_SESSION["refrigeratorDoor"]) && $_SESSION["refrigeratorDoor"] == 1)) { //Dolap kapağı açıksa ve kutu eklenmek isteniyorsa
    $refrigerator->addItem(1);
    if($refrigerator->getQuantity()<60){
        echo "Dolaba kutu eklendi ve dolap kapağı kapatıldı";
    }
} elseif(array_key_exists('delete', $_POST) && (isset($_SESSION["refrigeratorDoor"]) && $_SESSION["refrigeratorDoor"] == 1)) { //Dolap kapağı açıksa ve kutu çıkarılmak isteniyorsa
    $refrigerator->deleteItem(1);
    if($refrigerator->getQuantity()>0){
        echo "Dolaptan kutu alındı ve dolap kapağı kapatıldı";
    }
} elseif(isset($_SESSION["refrigeratorDoor"]) && $_SESSION["refrigeratorDoor"] == 0) {
    echo "Dolap kapağı kapalı";
}

?>

<html>
<head>
    <meta charset="utf-8">
    <title>Meşrubat Dolabı</title>
</head>

<body>
    <?php if(isset($_SESSION["refrigeratorDoor"]) && $_SESSION["refrigeratorDoor"] == 0) { // Dolap kapağı kapalıysa Dolap kapağını aç butonu gözükecek ?>
    <form method="post">
        <input type="submit" name="refrigeratorDoor"
               class="button" value="Dolap Kapağını Aç" />
    </form>
    <?php } ?>
    <?php if(isset($_SESSION["refrigeratorDoor"]) && $_SESSION["refrigeratorDoor"] == 1) { // Dolap kapağı açıksa ekle ve çıkar butonları görünür
        ?>
        <form method="post">
            <input type="submit" name="add"
                   class="button" value="Ekle" />

            <input type="submit" name="delete"
                   class="button" value="Çıkar" />
        </form>
    <?php } ?>
</body>
</html>