<?php 
$artist="";
$country="";
$oldArtist="";
$technique="";
if (!isset($searchBy)) {
  $searchBy="artist";
}
$firstArtist=true;
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Bienal</title> 
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=ISO8859-15" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>    
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.5.1.min.js"></script>
  </head>
  <body> 
    <?php 
    if (!empty($_SESSION['slim.flash'])){
      foreach($_SESSION['slim.flash'] as $key=>$flash){
        if(strpos($key,'fail-text')===false) {?>
          <div id="feedback" class="<?php echo $key?>">
            <p><?php echo $flash?></p>
          </div>
        <?php 
        } 
      }
    }
    ?>      
    <header>
    </header>
    <div id="wrapper">
      <a href="index"><h1>Bienal</h1></a>
      <form method="post" id="logout-form">
        <input type="hidden" name="logout" value="1">
        <a id="logout"><i class="fa fa-sign-out"></i>Sair</a>
      </form> 
      <div class="search-type">
        <a class="search-type-link" href="index">Artistas</a>
        <a class="search-type-link" href="prints">Gravuras</a>
        <a class="search-type-link selected" href="inventory">Inventário</a>
      </div>  
      <form action="" method="post" id="search-form">
        <input type="hidden" name="inventory-operation" value="search">
        <input type="hidden" name="type" value="inventory">
        <input placeholder="Pesquisar Inventário..." class="search inventory-version" type="text" name="search-words">
        <select name="bienal-edition">
          <?php 
          if(!isset($edition) || $edition=="all"){ ?>
            <option value="all">Todas</option>
            <?php 
            for($i=1;$i<8;$i++){?>
              <option value="<?php echo $i;?>"><?php echo $i;?>º Edição</option>
            <?php
            }
          }else{?>
            <option value="<?php echo $edition;?>"><?php echo $edition;?>º Edição</option>
            <?php  
            for($i=1;$i<8;$i++){
              if($edition!=$i){?>
                <option value="<?php echo $i;?>"><?php echo $i;?>º Edição</option>
              <?php
              } 
            }?>
            <option value="all">Todas</option>
          <?php 
          }?>
        </select>      
        <select name="search-by">
          <?php 
          if($searchBy=="artist"){ ?>
            <option value="artist">Artista</option>
            <option value="country">País</option>
            <option value="technique">Técnica</option>
          <?php 
          }else if ($searchBy=="country"){ ?>
            <option value="country">País</option>
            <option value="artist">Artista</option>
            <option value="technique">Técnica</option>
          <?php 
          }else if ($searchBy=="technique"){ ?>
            <option value="technique">Técnica</option>
            <option value="country">País</option>
            <option value="artist">Artista</option>
          <?php 
          } ?>
        </select>
        <input class="submit-search" type="submit" value="Pesquisar">        
      </form>
      <div class="artist-buttons">
        <button id="remove-inventory" type="submit"><i class="fa fa-minus-square"></i>Retirar do Inventário</button>
      </div>
      
      <div id="results">
        <?php
        if (isset($edition)){ 
          if (isset($search)) {
            if ($edition!="all"){?>
              <p>Resultados da pesquisa <span class="searched"><?php echo '"'.$search.'" ('.$edition.'º Edição Bienal )';?></span></p>
            <?php 
            }else{?>
              <p>Resultados da pesquisa <span class="searched"><?php echo '"'.$search.'"';?></span></p>
            <?php 
            }
          }else{
            if ($edition!="all"){ ?>
              <p>Resultados da pesquisa <span class="searched"><?php if($searchBy=="artist"){ echo 'Todos os Artistas (';}else if($searchBy=="country"){ echo 'Todos os Países (';}else{ echo 'Todos as Técnicas (';} echo $edition.'º Edição Bienal )';?></span></p>
            <?php 
            }else{?>
              <p>Resultados da pesquisa <span class="searched"><?php if($searchBy=="artist"){ echo 'Todos os Artistas'; }else if($searchBy=="country"){ echo 'Todos os Países'; }else{ echo 'Todos as Técnicas'; } ?></span></p>
            <?php 
            }
          }
        }

       
        if (isset($prints)) {
          foreach($prints as $print){
            if($print["inventory"]!=0){
              if (!isset($search)) {                //se não houver palavras na pesquisa
                if($searchBy=="country"){           //se pesquisa por país  
                  $newCountry=$print["artist_country"];
                  if($newCountry!=$country){        //se o país for diferente do anterior imprime-o
                    $country=$newCountry;?>
                    <span class="search-divider"><?php echo $country;?></span>
                  <?php 
                  } ?>
                <?php 
                }else if($searchBy=="artist"){  //se pesquisa por artista 
                  $newArtist=$print["artist_name"]; 
                  if($newArtist!=$artist){            //e o artista for diferente da anterior imprime-o
                    $artist=$newArtist;?>
                    <span class="search-divider"><?php echo $artist;?></span>
                  <?php 
                  } ?>
                <?php 
                }else if($searchBy=="technique"){  //se pesquisa por técnica 
                  $newTechnique=$print["print_technique"]; 
                  if($newTechnique!=$technique){         //e a técnica for diferente da anterior imprime
                    $technique=$newTechnique;?>
                    <span class="search-divider"><?php echo $technique;?></span>
                  <?php 
                  } ?>
                <?php 
                } ?> 
              <?php 
              } ?>
              <div class="print inventory-version-print" name="<?php echo $print["print_name"];?>">
                <form class="add-inventory-form" id="inventory-form<?php echo $print["print_id"];?>" action="" method="post"><input type="hidden" name="inventory-operation" value="remove-inventory"><input type="hidden" name="print-name" value="<?php echo $print["print_name"];?>"><input type="hidden" name="print-id" value="<?php echo $print["print_id"];?>"> </form>     
                <div>
                  <div class="print-title-pic-inventory-version">
                      <span class="print-title"><?php echo $print["print_name"]; ?></span>
                  </div>  
                </div>
                <a class="artist-link" href="artist?id=<?php echo $print['artist_id']; ?>"><span class="print-title print-title-small"><?php echo $print["artist_name"]; ?></span></a>
                <span class="print-title print-title-small"><?php echo $print["artist_country"]; ?></span>
              </div>  
            <?php
            } 
          }?>
          </div>
          </div>
          </a>
        <?php 
        }else if(isset($randomPrints)){
          foreach($randomPrints as $print){
            if($print["inventory"]!=0){ ?>
              <div class="print inventory-version-print" name="<?php echo $print["print_name"];?>">
                <form class="add-inventory-form" id="inventory-form<?php echo $print["print_id"];?>" action="" method="post"><input type="hidden" name="inventory-operation" value="remove-inventory"><input type="hidden" name="print-name" value="<?php echo $print["print_name"];?>"><input type="hidden" name="print-id" value="<?php echo $print["print_id"];?>"> </form>     
                <div>
                  <div class="print-title-pic-inventory-version">
                      <span class="print-title"><?php echo $print["print_name"]; ?></span>
                  </div>  
                </div>
                <a class="artist-link" href="artist?id=<?php echo $print['artist_id']; ?>"><span class="print-title print-title-small"><?php echo $print["artist_name"]; ?></span></a>
                <span class="print-title print-title-small"><?php echo $print["artist_country"]; ?></span>
              </div> 
            <?php 
            }
          } 
        } ?>       
      </div> 
    </div>
  </body>
  <script type="text/javascript" src="js/function.js"></script>
</html>