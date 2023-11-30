<?php
switch($a.'_'.$b)
{
  case "designer-portfolio_del":
    $text = $lang[$_SESSION["admin"]["global"]["lang"]]["Chcete_vymazat_zaznam?"].'?';
    
    $nadpis = $lang[$_SESSION["admin"]["global"]["lang"]]["Dizajner"].' - '.$lang[$_SESSION["admin"]["global"]["lang"]]["Portfolio"].' - '.$lang[$_SESSION["admin"]["global"]["lang"]]["Vymazat"];
    
    $sql = "SELECT * FROM ".TBL_PORTFOLIO." WHERE id = :id";
    $stmt = $db_pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $text .= '<div class="msg_content">'.$record["title"].'</div>';
    if ($record["file"]!='')
    {
      $img_src = DIR_WWW_ROOT.'files/img/portfolio/t-'.$record["file"];
      $img = '<img src="'.$img_src.'" class="img-fluid">';
      $text .= '<div class="msg_content">'.$img.'</div>';
    }
    
    $url_ano = DIR_WWW_ROOT.'admin/act/designer-portfolio_delete/'.$id.'/';
    $url_nie = DIR_WWW_ROOT.'admin/app/designer/'.$record["id_user"].'/portfolio/p-1.html';
    break;
  case "designer-gallery_del":
    $text = $lang[$_SESSION["admin"]["global"]["lang"]]["Chcete_vymazat_zaznam?"].'?';
    
    $nadpis = $lang[$_SESSION["admin"]["global"]["lang"]]["Dizajner"].' - '.$lang[$_SESSION["admin"]["global"]["lang"]]["Portfolio"].' - '.$lang[$_SESSION["admin"]["global"]["lang"]]["Galeria"].' - '.$lang[$_SESSION["admin"]["global"]["lang"]]["Vymazat"];
    
    $sql = "SELECT * FROM ".TBL_P_GALLERY." WHERE id = :id";
    $stmt = $db_pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $text .= '<div class="msg_content">'.$record["title"].'</div>';
    if ($record["file"]!='')
    {
      $img_src = DIR_WWW_ROOT.'files/img/portfolio/t-'.$record["file"];
      $img = '<img src="'.$img_src.'" class="img-fluid">';
      $text .= '<div class="msg_content">'.$img.'</div>';
    }
    
    $url_ano = DIR_WWW_ROOT.'admin/act/designer-portfolio-gallery_delete/'.$id.'/';
    $url_nie = DIR_WWW_ROOT.'admin/app/designer/'.$record["id_user"].'/gallery/'.$record["id_portfolio"].'/detail.html';
    break;
  case "designer_del":
    $text = $lang[$_SESSION["admin"]["global"]["lang"]]["Chcete_vymazat_zaznam?"].'?';
    
    $nadpis = $lang[$_SESSION["admin"]["global"]["lang"]]["Dizajner"].' - '.$lang[$_SESSION["admin"]["global"]["lang"]]["Vymazat"];
    
    $sql = "SELECT * FROM ".TBL_USER." WHERE id = :id";
    $stmt = $db_pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    $text .= '<div class="msg_content">'.$record["nickname"].' / '.$record["email"].'</div>';
    
    $url_ano = DIR_WWW_ROOT.'admin/act/designer_delete/'.$id.'/';
    $url_nie = DIR_WWW_ROOT.'admin/app/designer/p-1.html';
    break;
  case "influencer_del":
    $text = $lang[$_SESSION["admin"]["global"]["lang"]]["Chcete_vymazat_zaznam?"].'?';
    
    $nadpis = $lang[$_SESSION["admin"]["global"]["lang"]]["Influencer"].' - '.$lang[$_SESSION["admin"]["global"]["lang"]]["Vymazat"];
    
    $sql = "SELECT * FROM ".TBL_USER." WHERE id = :id";
    $stmt = $db_pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    $text .= '<div class="msg_content">'.$record["nickname"].' / '.$record["email"].'</div>';
    
    $url_ano = DIR_WWW_ROOT.'admin/act/influencer_delete/'.$id.'/';
    $url_nie = DIR_WWW_ROOT.'admin/app/influencer/p-1.html';
    break;
}

?>
<div class="container">
  <div class="row">
    <div class="col-12">&nbsp;</div>
  </div>
  <div class="row">
    <div class="col-12 offset-sm-2 col-sm-8 offset-md-3 col-md-6 form_area">
      <div class="row">
        <div class="col-12 text-center">
          <div class="login_window">
            <?php
            if (isset($nadpis))
            {
              ?>
              <div class="row">
                <div class="col-12 text-center py-3">
                   <h2><?php echo $nadpis;?></h2>
                </div>
              </div>
              <?php
            }
            ?>
            <div class="row py-1">
              <div class="col-12 text-center">
                <?php echo $text; ?>
              </div>
            </div>
            <div class="row py-1">
              <div class="col-12 text-center">
                <a href="<?php echo $url_ano; ?>" class="adm_btn d_inline w_45p" role="button" aria-pressed="true"><?php echo $lang[$_SESSION["admin"]["global"]["lang"]]["Ano"]; ?></a>
                <a href="<?php echo $url_nie; ?>" class="adm_btn d_inline w_45p" role="button" aria-pressed="true"><?php echo $lang[$_SESSION["admin"]["global"]["lang"]]["Nie"]; ?></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
