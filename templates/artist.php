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
      <div id="artist_page">
        <h1 class="page-title"><?php echo $artist["artist_name"];?></h1>
        <div class="artist-pic"></div>
        <div class="artist-info">
          <div class="page-division blue-division"><span class="page-division-title">bio</span></div>
          <span class="keyword">País</span><span class="artist-text"><?php echo $artist["artist_country"];?></span><br>
          <span class="keyword">Contacto</span><span class="artist-text"><?php echo $artist["artist_email"];?></span><br>
          <p class="artist-text"><?php echo $artist["artist_bio"];?></p>
        </div> 
        <div class="artist-prints">
          <div class="page-division white-division"><span class="page-division-title">obras</span></div>
          <div class="prints-buttons">
            <button id="add-print" type="submit"><i class="fa fa-plus-square"></i>Adicionar Gravura</button>
            <button id="delete-print" type="submit"><i class="fa fa-minus-square"></i>Eliminar Gravura</button>
          </div> 
          <form method="post" class="new-print">
            <input type="hidden" name="print-operation" value="add-print">
            <input type="hidden" name="artist-id" value="<?php echo $artist["artist_id"];?>">
            <span class="search-divider">Adicionar Gravura</span>
            <fieldset>
              <label for="name">Nome:</label>
              <input type="text" id="name" name="print-name" placeholder="Nome da Obra">
              <label for="technique">Técninca:</label>
              <input type="text" id="technique" name="print-technique" placeholder="Técnica da Obra">
              <label for="year">Ano:</label>
              <input type="number" id="year" name="print-year" placeholder="Ano da Obra">
            </fieldset>
            <fieldset>  
              <label for="dimensions">Dimensões:</label>
              <input type="text" id="dimensions" name="print-dimensions" placeholder="Dimensões da Obra (ex: 20x20)">
              <label for="bienal-edition">Bienal:</label>
              <select name="bienal-edition">
                <?php 
                for($i=1;$i<8;$i++){?>
                  <option value="<?php echo $i;?>"><?php echo $i;?>º Edição</option>
                <?php 
                }?>
              </select>  
            </fieldset>
            <center>
              <button type="submit">Adicionar Obra</button>
            </center>
          </form>
          <?php $bienal="";?>
          <?php foreach ($prints as $print){ 
            $newBienal=$print["bienal_name"];
            if($newBienal!=$bienal){
              $bienal=$newBienal;?>
              <p class="bienal-edition"><?php echo $print["bienal_name"]?></p>
            <?php } ?>
            <div class="artist-print" name="<?php echo $print["print_name"]?>">
              <form class="remove-print-form" id="print-form<?php echo $print["print_id"];?>" action="" method="post"><input type="hidden" name="artist-id" value="<?php echo $artist["artist_id"];?>"><input type="hidden" name="print-operation" value="delete-print"><input type="hidden" name="print-name" value="<?php echo $print["print_name"];?>"><input type="hidden" name="print-id" value="<?php echo $print["print_id"];?>"></form>       
              <div class="print-pic"></div>
              <p class="print-title"><?php echo $print["print_name"]?></p>
              <span class="print-info print-info-key gold">Ano</span><span class="print-info"><?php echo $print["print_year"]?></span>
              <span class="print-info print-info-key gold">Dimensões</span><span class="print-info"><?php echo $print["print_dimensions"]?></span>
              <span class="print-info print-info-key gold">Técnica</span><span class="print-info"><?php echo $print["print_technique"]?></span>
            </div>
          <?php } ?>
        </div>
        <div class="search-artists">
          <div class="page-division blue-division"><span class="page-division-title">artistas</span></div>
          <form action="" method="post">
            <input placeholder="Pesquise por artista..." class="search" type="text" name="search-words">
            <select name="search-by">
                <option value="artist">Artista</option>
                <option value="country">País</option>
            </select>        
            <input class="submit-search" type="submit" value="Pesquisar">
          </form>
        </div> 
      </div>
    </div>
  </body>
  <script type="text/javascript" src="js/function.js"></script>
</html>