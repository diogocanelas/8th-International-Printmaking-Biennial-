<?php


class Artist {

  public function getArtistsbyName($db,$artist_name,$edition) {
    $artists = array();
    try{
         $query="select Distinct Artist.* , Print.bienal_id
          FROM Artist
          LEFT JOIN Print
          ON Artist.artist_id = Print.artist_id
          WHERE artist_name LIKE '%".$artist_name."%'";
          if($edition!="all"){
            $query=$query."AND Print.bienal_id = ".$edition." ";
          }
          $query=$query."ORDER BY artist_name, Print.bienal_id";
         $results = $db->prepare($query);  
        if($results->execute()){
          $artists = $results->fetchAll(PDO::FETCH_ASSOC);
        }else{
          $artists = NULL;
        }
    }catch(Exception $e){
        echo $e->getMessage();
        die("Couldn't Find it");
    }
    return $artists;
  }



  public function getArtistbyID($db,$artist_id,$edition) {
    $artists = array();
    try{
        $query="select Distinct artist.* , Print.bienal_id
          FROM artist 
          Left JOIN Print
          ON Artist.artist_id = Print.artist_id
          WHERE artist.artist_id = ".$artist_id." ";
        if($edition!="all"){
            $query=$query."AND Print.bienal_id = ".$edition." ";
        }
        $query=$query."ORDER BY artist_name, Print.bienal_id";
        $results = $db->prepare($query);     
        if($results->execute()){
          $artists = $results->fetch(PDO::FETCH_ASSOC);
        }else{
          $artists = NULL;
        }
    }catch(Exception $e){
        echo $e->getMessage();
        die("Couldn't Find it");
    }
    return $artists;
  }

  public function getArtistsbyCountry($db,$artist_country,$edition) {
    $artists = array();
    try{
        $query="select Distinct Artist.* , Print.bienal_id
          FROM Artist
          Left JOIN Print
          ON Artist.artist_id = Print.artist_id 
          WHERE Artist.artist_country LIKE '".$artist_country."'";
        if($edition!="all"){
            $query=$query."AND Print.bienal_id = ".$edition." ";
        }
        $query=$query."ORDER BY artist_name, Print.bienal_id";
        $results = $db->prepare($query);     
        if($results->execute()){
          $artists = $results->fetchAll(PDO::FETCH_ASSOC);
        }else{
          $artists = NULL;
        }
    }catch(Exception $e){
        echo $e->getMessage();
        die("MORREU");
    }
    return $artists;
  }  
	
  public function getAllArtistsbyEdition($db,$searchBy,$edition) {
    $artists = array();
    try{
        if($searchBy!="country"){
         $results = $db->prepare("select Distinct Artist.*  , Print.bienal_id
          FROM Artist 
          JOIN Print
          ON Artist.artist_id = Print.artist_id 
          WHERE Print.bienal_id=".$edition."
          ORDER BY artist_name , Print.bienal_id ");
        }else{
         $results = $db->prepare("select Distinct Artist.* , Print.bienal_id
          FROM Artist 
          JOIN Print
          ON Artist.artist_id = Print.artist_id
          WHERE Print.bienal_id=".$edition." 
          ORDER BY artist_country , artist_name,  Print.bienal_id  ");
        }    
        if($results->execute()){
          $artists = $results->fetchAll(PDO::FETCH_ASSOC);
        }else{
          $artists = NULL;
        }
    }catch(Exception $e){
        echo $e->getMessage();
        die("MORREU");
    }
    return $artists;
  }

  public function getAllArtists($db,$searchBy) {
    $artists = array();
    try{
        if($searchBy!="country"){
         $results = $db->prepare("select Distinct Artist.*  , Print.bienal_id
          FROM Artist 
          LEFT JOIN Print
          ON Artist.artist_id = Print.artist_id 
          ORDER BY artist_name , Print.bienal_id ");
        }else{
         $results = $db->prepare("select Distinct Artist.* , Print.bienal_id
          FROM Artist 
          LEFT JOIN Print
          ON Artist.artist_id = Print.artist_id
          ORDER BY artist_country , artist_name,  Print.bienal_id  ");
        }    
        if($results->execute()){
          $artists = $results->fetchAll(PDO::FETCH_ASSOC);
        }else{
          $artists = NULL;
        }
    }catch(Exception $e){
        echo $e->getMessage();
        die("MORREU");
    }
    return $artists;
  }

  public function addArtist($db,$name, $country, $email, $bio) {
    $artist = array();
    try{
        $results = $db->prepare("INSERT INTO Artist 
          (artist_name , artist_country , artist_email, artist_bio) 
          VALUES ('".$name."' , '".$country."' , '".$email."' , '".$bio."' );");
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

  public function deleteArtist($db,$id){
    try{
        $results = $db->prepare("DELETE FROM Artist
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

}

    