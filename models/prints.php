<?php


class Prints {

  public function getPrintsbyCountry($db,$country,$edition) {
    $prints = array();
    try{
        $query="select Distinct Print.* ,  artist_name, artist_country
          FROM Print
          JOIN Artist
          ON Artist.artist_id = Print.artist_id 
          WHERE Artist.artist_country LIKE '%".$country."%'";
        if($edition!="all"){
            $query=$query."AND Print.bienal_id = ".$edition." ";
        }
        $query=$query."ORDER BY artist_name, Print.bienal_id";
        $results = $db->prepare($query);     
        if($results->execute()){
          $prints = $results->fetchAll(PDO::FETCH_ASSOC);
        }else{
          $prints = NULL;
        }
    }catch(Exception $e){
        echo $e->getMessage();
        die("MORREU");
    }
    return $prints;
  }  

  public function getPrintsbyArtist($db,$artist_name,$edition) {
    $prints = array();
    try{
        $query="select Distinct Print.* ,  artist_name, artist_country
          FROM Print
          JOIN Artist
          ON Artist.artist_id = Print.artist_id 
          WHERE Artist.artist_name LIKE '%".$artist_name."%'";
        if($edition!="all"){
            $query=$query."AND Print.bienal_id = ".$edition." ";
        }
        $query=$query."ORDER BY artist_name, Print.bienal_id";
        $results = $db->prepare($query);     
        if($results->execute()){
          $prints = $results->fetchAll(PDO::FETCH_ASSOC);
        }else{
          $prints = NULL;
        }
    }catch(Exception $e){
        echo $e->getMessage();
        die("MORREU");
    }
    return $prints;
  }  

  public function getPrintsbyTechnique($db,$technique,$edition) {
    $prints = array();
    try{
        $query="select Distinct Print.* ,  artist_name, artist_country
          FROM Print
          JOIN Artist
          ON Artist.artist_id = Print.artist_id 
          WHERE Print.print_technique LIKE '%".$technique."%'";
        if($edition!="all"){
            $query=$query."AND Print.bienal_id = ".$edition." ";
        }
        $query=$query."ORDER BY print_technique, artist_name";
        $results = $db->prepare($query);     
        if($results->execute()){
          $prints = $results->fetchAll(PDO::FETCH_ASSOC);
        }else{
          $prints = NULL;
        }
    }catch(Exception $e){
        echo $e->getMessage();
        die("MORREU");
    }
    return $prints;
  }  
  
  public function getAllPrintsbyEdition($db,$searchBy,$edition) {
    $prints = array();
    try{
        if($searchBy=="artist"){
         $results = $db->prepare("select Distinct Print.*, artist_name, artist_country 
          FROM Print 
          JOIN Artist
          ON Artist.artist_id = Print.artist_id 
          WHERE Print.bienal_id=".$edition."
          ORDER BY artist_name , Print.bienal_id ");
        }else if($searchBy=="country"){
         $results = $db->prepare("select Distinct Print.* , artist_name , artist_country
          FROM Print 
          JOIN Artist
          ON Artist.artist_id = Print.artist_id
          WHERE Print.bienal_id=".$edition." 
          ORDER BY artist_country , artist_name,  Print.bienal_id  ");
        }else if($searchBy=="technique"){
         $results = $db->prepare("select Distinct Print.* , artist_name , artist_country
          FROM Print 
          JOIN Artist
          ON Artist.artist_id = Print.artist_id
          WHERE Print.bienal_id=".$edition." 
          ORDER BY print_technique , artist_name,  Print.bienal_id  ");
        }
        if($results->execute()){
          $prints = $results->fetchAll(PDO::FETCH_ASSOC);
        }else{
          $prints = NULL;
        }
    }catch(Exception $e){
        echo $e->getMessage();
        die("MORREU");
    }
    return $prints;
  }

  public function getAllPrints($db,$searchBy) {
    $prints = array();
    try{
        if($searchBy=="artist"){
         $results = $db->prepare("select Distinct Print.* , artist_name, artist_country
          FROM Print 
          JOIN Artist
          ON Artist.artist_id = Print.artist_id 
          ORDER BY artist_name , Print.bienal_id ");
        }else if($searchBy=="country"){
         $results = $db->prepare("select Distinct Print.* , artist_name, artist_country
          FROM Print 
          JOIN Artist
          ON Artist.artist_id = Print.artist_id
          ORDER BY artist_country , artist_name,  Print.bienal_id  ");
        }else if($searchBy=="technique"){
         $results = $db->prepare("select Distinct Print.* , artist_name, artist_country
          FROM Print 
          JOIN Artist
          ON Artist.artist_id = Print.artist_id
          ORDER BY print_technique , artist_name,  Print.bienal_id  ");
        }  
        if($results->execute()){
          $prints = $results->fetchAll(PDO::FETCH_ASSOC);
        }else{
          $prints = NULL;
        }
    }catch(Exception $e){
        echo $e->getMessage();
        die("MORREU");
    }
    return $prints;
  }

  public function getAllPrintsRandom($db) {
    $prints = array();
    try{
         $results = $db->prepare("select Distinct Print.* , artist_name, artist_country
          FROM Print 
          JOIN Artist
          ON Artist.artist_id = Print.artist_id
          ORDER BY RAND()");
        if($results->execute()){
          $prints = $results->fetchAll(PDO::FETCH_ASSOC);
        }else{
          $prints = NULL;
        }
    }catch(Exception $e){
        echo $e->getMessage();
        die("MORREU");
    }
    return $prints;
  }

  public function getPrintsByArtistID($db,$artist_id) {
    $prints = array();
    try{
         $results = $db->prepare("select Print.*, Bienal.bienal_name
          FROM Print
          JOIN Bienal
          ON Print.bienal_id = Bienal.bienal_id 
          WHERE Print.artist_id = ".$artist_id." 
          ORDER BY Bienal.bienal_id ASC");  
        if($results->execute()){
          $prints = $results->fetchAll(PDO::FETCH_ASSOC);
        }else{
          $prints = NULL;
        }
    }catch(Exception $e){
        echo $e->getMessage();
        die("Couldn't Find it");
    }
    return $prints;
  }

  public function addPrint($db,$artist_id,$printName, $printYear, $printTechnique, $printDimensions, $printBienal) {
    $artist = array();
    try{
        $results = $db->prepare("INSERT INTO Print 
          (artist_id , print_name, print_technique, print_year, print_dimensions, bienal_id) 
          VALUES ('".$artist_id."' , '".$printName."' , '".$printTechnique."' , '".$printYear."', '".$printDimensions."' , '".$printBienal."' );");
        if($results->execute()){
          $id=$db->lastInsertId();
        }else{
          $id = NULL;
        }
    }catch(Exception $e){
        echo $e->getMessage();
        die("CONNECTION FAILED");
    }
    return $id;
  }

  public function deletePrint($db,$id){
    try{
        $results = $db->prepare("DELETE FROM Print
          WHERE print_id=".$id);
        if($results->execute()){
          $deleted=true;
        }else{
          $deleted = false;
        }
    }catch(Exception $e){
        echo $e->getMessage();
        die("CONNECTION FAILED");
    }
    return $deleted;
  }

  public function deletePrintbyArtist($db, $id){
    try{
        $results = $db->prepare("DELETE FROM Print
          WHERE artist_id=".$id);
        if($results->execute()){
          $deleted=true;
        }else{
          $deleted = false;
        }
    }catch(Exception $e){
        echo $e->getMessage();
        die("CONNECTION FAILED");
    }
    return $deleted;
  }


  public function addInventory($db,$id){
    try{
        $results = $db->prepare("UPDATE Print
          SET inventory=1
          WHERE print_id=".$id);
        if($results->execute()){
          $updated=true;
        }else{
          $updated = false;
        }
    }catch(Exception $e){
        echo $e->getMessage();
        die("CONNECTION FAILED");
    }
    return $updated;
  }

  public function removeInventory($db,$id){
    try{
        $results = $db->prepare("UPDATE Print
          SET inventory=0
          WHERE print_id=".$id);
        if($results->execute()){
          $deleted=true;
        }else{
          $deleted = false;
        }
    }catch(Exception $e){
        echo $e->getMessage();
        die("CONNECTION FAILED");
    }
    return $deleted;
  }


}

    