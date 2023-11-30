<?php
//******************************************************
//* paging class                                       *
//* Vytvara a zobrazuje strankovanie vypisu z databazy *
//* 2005 by mavin                                      *
//******************************************************

class Pager {
  var $query;    // sql prikaz
  var $params;    // sql prikaz
  var $numRecs;  // pocet zobrazenych zaznamov
  var $output;   // generovany vystup
  var $totalRecs;
  var $numPages;
  var $rows;

  function __construct($query, $param_array, $numRecs=10)
  {
    global $db_pdo;
    global $g_pg;
    if (preg_match("/^SELECT/",$query))
    {
      $stmt = $db_pdo->prepare($query);
      if (count($param_array)>0)
      {
        $stmt->execute($param_array);
      }
      else
      {
        $stmt->execute();
      }
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $this->totalRecs = count($rows);
      $this->query = $query;
      $this->params = $param_array;
      $this->pg = $g_pg;
    }
    else
    {
      die('SQL command error');
    }
    
    if (is_int($numRecs) && $numRecs>0)
    {
      $this->numRecs=$numRecs;
    }
    else
    {
      die('Chybný počet záznamov: '.$numRecs);
    }

    // vypocet poctu stanok
    $this->numPages = ceil($this->totalRecs/$this->numRecs);

    $this->output = '';

  }
  
  function displayNavigationPagesSHRT_ADM($page, $url_part = '')
  {
    
    $this->output = '<div class="pagesLinks">';
    // vylidacia ukazovatela na stranku
    if(!preg_match("/^\d{1,4}$/",$page) || $page < 1 || $page > $this->numPages)
    {
  	  $page=1;
    }
    
    //echo "nop:".$this->numPages."<br>";
    
    $start_dots = 0;
    $end_dots = 0;
    switch($this->numPages)
    {
      case 1:
      case 2:
      case 3:
      case 4:
      case 5:
      case 6:
      case 7:
      case 8:
      case 9:
        $od = 1;
        $do = $this->numPages;
      break;
      default :
        if ($page >= 7)
        {
          $od = $page - 4;
          $start_dots = 1;
        }
        else
        {
          $od = 1;
        }
        switch ($this->numPages - $page)
        {
          case 0:
            $do = $this->numPages;
          break;
          case 1:
            $do = ($page+1);
          break;
          case 2:
            $do = ($page+2);
          break;
          case 3:
            $do = ($page+3);
          break;    
          case 4:
            $do = ($page+4);
          break; 
          case 5:
            $do = ($page+5);
          break; 
          default:
            if (($this->numPages - $page) > 0)
            {
              $do = ($page+4);
              $end_dots = 1;
            }
            else
            {
              $do = $this->numPages;
            }
          break;
        }
      break;
    }
       
    if ($page!=1)
    {
      $this->output .= '<a href="'.DIR_WWW_ROOT.'admin/index.php?page='.($page-1).''.$url_part.'"><i class="fas fa-angle-double-left"></i></a>';
    }
    for($i=$od;$i<=$do;$i++)
    {
      if ($start_dots==1 && $i==$od)
      {
        $this->output.='<a href="'.DIR_WWW_ROOT.'admin/index.php?page=1'.$url_part.'.html">1</a> ... ';
      }
      elseif ($end_dots==1 && $i==$do)
      {
        $this->output.=' ... <a href="'.DIR_WWW_ROOT.'admin/index.php?page='.($this->numPages).''.$url_part.'">'.$this->numPages.'</a>';
      }
      else
      {
      ($i!=$page) ? $this->output.='<a href="'.DIR_WWW_ROOT.'admin/index.php?page='.($i).''.$url_part.'">'.$i.'</a>' : $this->output.='<a href="'.DIR_WWW_ROOT.'admin/index.php?page='.($i).''.$url_part.'" class="btn_selected">'.$i.'</a>';
      }
    }
    if ($page!=$this->numPages)
    {
      $this->output.='<a href="'.DIR_WWW_ROOT.'admin/index.php?page='.($page + 1).''.$url_part.'"><i class="fas fa-angle-double-right"></i></a>';
    }
    $this->output .= '</div>';
    return $this->output;
  }
  
  function displayNavigationPagesSHRT($page, $url_part)
  {
    
    $this->output = '';
    // vylidacia ukazovatela na stranku
    if(!preg_match("/^\d{1,4}$/",$page) || $page < 1 || $page > $this->numPages)
    {
  	  $page=1;
    }
    
    //echo "nop:".$this->numPages."<br>";
    
    $start_dots = 0;
    $end_dots = 0;
    switch($this->numPages)
    {
      case 1:
      case 2:
      case 3:
      case 4:
      case 5:
      case 6:
      case 7:
      case 8:
      case 9:
        $od = 1;
        $do = $this->numPages;
      break;
      default :
        if ($page >= 7)
        {
          $od = $page - 4;
          $start_dots = 1;
        }
        else
        {
          $od = 1;
        }
        switch ($this->numPages - $page)
        {
          case 0:
            $do = $this->numPages;
          break;
          case 1:
            $do = ($page+1);
          break;
          case 2:
            $do = ($page+2);
          break;
          case 3:
            $do = ($page+3);
          break;    
          case 4:
            $do = ($page+4);
          break; 
          case 5:
            $do = ($page+5);
          break; 
          default:
            if (($this->numPages - $page) > 0)
            {
              $do = ($page+4);
              $end_dots = 1;
            }
            else
            {
              $do = $this->numPages;
            }
          break;
        }
      break;
    }
       
    if ($page!=1)
    {
      //$this->output .= '<a href="'.DIR_WWW_ROOT.$url_part.'/strana-'.($page-1).'.html" class="btn btn-default" role="button">&lt;&lt;</a>';
      $this->output .= '<form class="form_pagination" action="'.DIR_WWW_ROOT.$url_part.'/strana-'.($page - 1).'.html"><input type="submit" class="pb_pagination_btn" value="&lt;" /></form>';
    }
    for($i=$od;$i<=$do;$i++)
    {
      if ($start_dots==1 && $i==$od)
      {
        //$this->output.='<a href="'.DIR_WWW_ROOT.$url_part.'/strana-1.html" class="btn btn-default" role="button">1</a> ... ';
          $this->output.='<form class="form_pagination" action="'.DIR_WWW_ROOT.$url_part.'/strana-1.html"><input type="submit" class="pb_pagination_btn" value="1" /></form> ... ';
      }
      elseif ($end_dots==1 && $i==$do)
      {
        //$this->output.=' ... <a href="'.DIR_WWW_ROOT.$url_part.'/strana-'.$this->numPages.'.html" class="btn btn-default" role="button">'.$this->numPages.'</a>';
        $this->output.=' ... <form class="form_pagination" action="'.DIR_WWW_ROOT.$url_part.'/strana-'.$this->numPages.'.html"><input type="submit" class="pb_pagination_btn" value="'.$this->numPages.'" /></form>';
      }
      else
      {
      //($i!=$page) ? $this->output.='<a href="'.DIR_WWW_ROOT.$url_part.'/strana-'.$i.'.html" class="btn btn-default" role="button">'.$i.'</a>' : $this->output.='<a href="'.DIR_WWW_ROOT.$url_part.'/strana-'.$i.'.html" class="btn btn-info" role="button">'.$i.'</a>';
      ($i!=$page) ? $this->output.='<form class="form_pagination" action="'.DIR_WWW_ROOT.$url_part.'/strana-'.$i.'.html"><input type="submit" class="pb_pagination_btn" value="'.$i.'" /></form>' : $this->output.='<form class="form_pagination" action="'.DIR_WWW_ROOT.$url_part.'/strana-'.$i.'.html"><input type="submit" class="pb_pagination_btn_current" value="'.$i.'" /></form>';
      }
    }
    if ($page!=$this->numPages)
    {
      //$this->output.='<a href="'.DIR_WWW_ROOT.$url_part.'/strana-'.($page + 1).'.html" class="btn btn-default" role="button">&gt;&gt;</a>';
      $this->output.='<form class="form_pagination" action="'.DIR_WWW_ROOT.$url_part.'/strana-'.($page + 1).'.html"><input type="submit" class="pb_pagination_btn" value="&gt;" /></form>';
    }
    return $this->output;
  }
  
  
  function displayRecords($page,$table,$part1="")
  {
    global $db_pdo;
    // validacia ukazovatela na stranku
    if(!preg_match("/^\d{1,4}$/",$page) || $page < 1 || $page > $this->numPages)
    {
  	  $page=1;
    }

    // prevziatie pozadovanych dat z sql servera
    //$result = mysql_query($this->query.' LIMIT '.($page-1)*$this->numRecs.' , '.$this->numRecs);
    $stmt = $db_pdo->prepare($this->query.' LIMIT '.($page-1)*$this->numRecs.' , '.$this->numRecs);
    $stmt->execute($this->params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($this->numPages > 0)
    {
      $i = 0;
      foreach ($rows as $row)
      {
        $i++;
        if (($i%2)==1)
        {
          $bg_color = TD_BG_01;
        }
        else
        {
          $bg_color = TD_BG_01;
        }  
        echo $this->displayROWS($row,$page,$table,$part1,$bg_color);
      }
    }
  }

    function displayROWS($row,$page,$table,$part1,$bg_color)
    {
        global $basic;
        global $db_pdo;

        $text = "";
        switch ($table)
        {
            case "p_suroviny":
                global $db_pdo;
                global $pg;

                $cache = '<tr>';
                if ($row["id"]==5 OR $row["id"]==10)
                {
                    $cache .= '<th scope="row"><a href="'.DIR_WWW_ROOT.'admin/index.php?pg='.$this->pg.'&amp;akcia=edit&amp;id='.$row["id"].'" class="adm_btn_action">edit</a><a href="'.DIR_WWW_ROOT.'admin/index.php?pg='.$this->pg.'&amp;akcia=detail&amp;id='.$row["id"].'" class="adm_btn_action">detail</a></th>';
                }
                else
                {
                    $cache .= '<th scope="row"><a href="'.DIR_WWW_ROOT.'admin/index.php?pg='.$this->pg.'&amp;akcia=edit&amp;id='.$row["id"].'" class="adm_btn_action">edit</a><a href="javascript:decision(\'Skutočne chcete záznam vymazať?\',\''.DIR_WWW_ROOT.'admin/include/action.php?action_id=del_'.$this->pg.'&amp;id='.$row["id"].'\')" class="adm_btn_action">del</a><br /><a href="'.DIR_WWW_ROOT.'admin/index.php?pg='.$this->pg.'&amp;akcia=detail&amp;id='.$row["id"].'" class="adm_btn_action">detail</a></th>';
                }
                if (strlen(trim($row["picture"]))>10)
                {
                    $cache .= '<td class="d-none d-sm-table-cell"><img src="'.DIR_WWW_ROOT.'images/surovina/t'.$row["picture"].'" width="100" height="100" /></td>';
                }
                else
                {
                    $cache .= '<td class="d-none d-sm-table-cell"><img src="'.DIR_WWW_ROOT.'images/surovina/no-image.png" width="100" height="100" /></td>';
                }
                $cache .= '<td class="text-left">'.$row["name_sk"].'</td>';
                $cache .= '<td class="d-none d-md-table-cell text-left">'.$row["name_en"].'</td>';
                $cache .= '<td class="d-none d-sm-table-cell text-left">'.$row["code"].'</td>';
                if ($row["status"]==1)
                {
                    $cache .= '<td><i class="fas fa-check-circle fa-lg"></i></td>';
                }
                else
                {
                    $cache .= '<td><i class="fas fa-times-circle fa-lg"></i></td>';
                }
                $cache .= '</tr>';
                break;
            case "p_produkty":
                global $db_pdo;
                global $pg;

                $sql = "SELECT count(id) as pocet FROM ".TBL_PRODUKT_SUROVINA." WHERE id_produkt = :id_produkt AND included = 1";
                $stmt = $db_pdo->prepare($sql);
                $stmt->bindValue(':id_produkt', $row["id"], PDO::PARAM_INT);
                $stmt->execute();
                $record = $stmt->fetch(PDO::FETCH_ASSOC);
                $included = $record["pocet"];
                
                $sql = "SELECT count(id) as pocet FROM ".TBL_PRODUKT_SUROVINA." WHERE id_produkt = :id_produkt AND included = 0";
                $stmt = $db_pdo->prepare($sql);
                $stmt->bindValue(':id_produkt', $row["id"], PDO::PARAM_INT);
                $stmt->execute();
                $record = $stmt->fetch(PDO::FETCH_ASSOC);
                $excluded = $record["pocet"];
                
                $cache = '<tr>';
                $cache .= '<th scope="row"><a href="'.DIR_WWW_ROOT.'admin/index.php?pg='.$this->pg.'&amp;akcia=edit&amp;id='.$row["id"].'" class="adm_btn_action">edit</a><a href="javascript:decision(\'Skutočne chcete záznam vymazať?\',\''.DIR_WWW_ROOT.'admin/include/action.php?action_id=del_'.$this->pg.'&amp;id='.$row["id"].'\')" class="adm_btn_action">del</a><br /><a href="'.DIR_WWW_ROOT.'admin/index.php?pg=produkt_surovina&amp;id_produkt='.$row["id"].'" class="adm_btn_action">suroviny '.$included.'+'.$excluded.'</a></div><div><a href="'.DIR_WWW_ROOT.'admin/index.php?pg='.$this->pg.'&amp;akcia=detail&amp;id='.$row["id"].'" class="adm_btn_action">detail</a></th>';
                if (strlen(trim($row["picture"]))>10)
                {
                    $cache .= '<td class="d-none d-sm-table-cell"><img src="'.DIR_WWW_ROOT.'images/produkt/t'.$row["picture"].'" width="100" height="100" /></td>';
                }
                else
                {
                    $cache .= '<td class="d-none d-sm-table-cell"><img src="'.DIR_WWW_ROOT.'images/produkt/no-image.png" width="100" height="100" /></td>';
                }
                $cache .= '<td class="text-left">'.$row["name_sk"].'<div class="cena">'.number_format($row["price"],2,",","").'</div></td>';
                $cache .= '<td class="d-none d-md-table-cell text-left">'.$row["name_en"].'</td>';
                $cache .= '<td class="d-none d-sm-table-cell text-left">'.$row["code"].'</td>';
                if ($row["status"]==1)
                {
                    $cache .= '<td><i class="fas fa-check-circle fa-lg"></i></td>';
                }
                else
                {
                    $cache .= '<td><i class="fas fa-times-circle fa-lg"></i></td>';
                }
                $cache .= '</tr>';
                break;
            case "p_produkt_surovina":
                global $db_pdo;
                global $pg;

                $cache = '<tr>';
                $cache .= '<th scope="row"><a href="javascript:decision(\'Skutočne chcete záznam vymazať?\',\''.DIR_WWW_ROOT.'admin/include/action.php?action_id=del_'.$this->pg.'&amp;id='.$row["id"].'&amp;id_produkt='.$row["id_produkt"].'\')" class="adm_btn_action">del</a><a href="'.DIR_WWW_ROOT.'admin/index.php?pg='.$this->pg.'&amp;akcia=edit&amp;id='.$row["id"].'&amp;id_produkt='.$row["id_produkt"].'" class="adm_btn_action">edit</a><br /><div><a href="'.DIR_WWW_ROOT.'admin/index.php?pg='.$this->pg.'&amp;akcia=detail&amp;id='.$row["id"].'&amp;id_produkt='.$row["id_produkt"].'" class="adm_btn_action">detail</a></th>';
                if ($row["fixed"]==1)
                {
                    $fixed = '<br /><i class="fas fa-exclamation-circle fa-lg"></i>';
                }
                else
                {
                    $fixed;
                }
                if ($row["included"]==1)
                {
                    $cache .= '<td><i class="fas fa-star fa-lg"></i>'.$fixed.'</td>';
                }
                else
                {
                    $cache .= '<td><i class="far fa-star fa-lg"></i>'.$fixed.'</td>';
                }
                $cache .= '<td class="text-left"><strong>'.$row["name_sk"].'</strong><br />'.$row["surovina"].'</td>';
                if (strlen(trim($row["obrazok"]))>10)
                {
                    $cache .= '<td class="d-none d-sm-table-cell"><img src="'.DIR_WWW_ROOT.'images/surovina/t'.$row["obrazok"].'" width="100" height="100" /></td>';
                }
                else
                {
                    $cache .= '<td class="d-none d-sm-table-cell"><img src="'.DIR_WWW_ROOT.'images/surovina/no-image.png" width="100" height="100" /></td>';
                }
                
                $cache .= '<td class="d-none d-md-table-cell text-left">'.$row["quantity"].'</td>';
                $cache .= '<td class="d-none d-sm-table-cell text-left">'.number_format($row["price"],2,',','').' &euro;</td>';
                $cache .= '</tr>';
                break;
            case "p_produkty_polievky":
                global $db_pdo;
                global $pg;

                $cache = '<tr>';
                $cache .= '<th scope="row"><a href="'.DIR_WWW_ROOT.'admin/index.php?pg='.$this->pg.'&amp;akcia=edit&amp;id='.$row["id"].'" class="adm_btn_action">edit</a><a href="javascript:decision(\'Skutočne chcete záznam vymazať?\',\''.DIR_WWW_ROOT.'admin/include/action.php?action_id=del_'.$this->pg.'&amp;id='.$row["id"].'\')" class="adm_btn_action">del</a><br/><a href="'.DIR_WWW_ROOT.'admin/index.php?pg='.$this->pg.'&amp;akcia=detail&amp;id='.$row["id"].'" class="adm_btn_action">detail</a></th>';
                if (strlen(trim($row["picture"]))>10)
                {
                    $cache .= '<td class="d-none d-sm-table-cell"><img src="'.DIR_WWW_ROOT.'images/produkt/t'.$row["picture"].'" width="100" height="100" /></td>';
                }
                else
                {
                    $cache .= '<td class="d-none d-sm-table-cell"><img src="'.DIR_WWW_ROOT.'images/produkt/no-image.png" width="100" height="100" /></td>';
                }
                $cache .= '<td class="text-left">'.$row["name_sk"].'<div class="cena">'.number_format($row["price"],2,",","").'</div></td>';
                $cache .= '<td class="d-none d-md-table-cell text-left">'.$row["name_en"].'</td>';
                $cache .= '<td class="d-none d-sm-table-cell text-left">'.$row["code"].'</td>';
                if ($row["status"]==1)
                {
                    $cache .= '<td><i class="fas fa-check-circle fa-lg"></i></td>';
                }
                else
                {
                    $cache .= '<td><i class="fas fa-times-circle fa-lg"></i></td>';
                }
                $cache .= '</tr>';
                break;
            case "p_akcie":
                global $db_pdo;
                global $pg;

                $sql = "SELECT * FROM ".TBL_PRODUKT." WHERE id = :id";
                $stmt = $db_pdo->prepare($sql);
                $stmt->bindValue(':id', $row["id_produktA"], PDO::PARAM_INT);
                $stmt->execute();
                $record_produktA = $stmt->fetch(PDO::FETCH_ASSOC);
                
                $sql = "SELECT * FROM ".TBL_PRODUKT." WHERE id = :id";
                $stmt = $db_pdo->prepare($sql);
                $stmt->bindValue(':id', $row["id_produktB"], PDO::PARAM_INT);
                $stmt->execute();
                $record_produktB = $stmt->fetch(PDO::FETCH_ASSOC);
                
                $cache = '<tr>';
                $cache .= '<th scope="row"><div><a href="'.DIR_WWW_ROOT.'admin/index.php?pg='.$this->pg.'&amp;akcia=edit&amp;id='.$row["id"].'" class="adm_btn_action">edit</a><a href="javascript:decision(\'Skutočne chcete záznam vymazať?\',\''.DIR_WWW_ROOT.'admin/include/action.php?action_id=del_'.$this->pg.'&amp;id='.$row["id"].'\')" class="adm_btn_action">del</a></div></th>';
                //<div><a href="'.DIR_WWW_ROOT.'admin/index.php?pg='.$this->pg.'&amp;akcia=detail&amp;id='.$row["id"].'" class="adm_btn_action">detail</a></div>
                if (strlen(trim($row["picture"]))>10)
                {
                    $cache .= '<td class="d-none d-sm-table-cell"><img src="'.DIR_WWW_ROOT.'images/produkt/t'.$row["picture"].'" width="100" height="100" /></td>';
                }
                else
                {
                    $cache .= '<td class="d-none d-sm-table-cell"><img src="'.DIR_WWW_ROOT.'images/produkt/no-image.png" width="100" height="100" /></td>';
                }
                $cache .= '<td class="text-left">'.$row["name_sk"].'</td>';
                $cache .= '<td class="d-none d-md-table-cell text-left">'.$record_produktA["name_sk"].'</td>';
                $cache .= '<td class="d-none d-sm-table-cell text-left">'.$record_produktB["name_sk"].'</td>';
                if ($row["status"]==1)
                {
                    $cache .= '<td><i class="fas fa-check-circle fa-lg"></i></td>';
                }
                else
                {
                    $cache .= '<td><i class="fas fa-times-circle fa-lg"></i></td>';
                }
                $cache .= '</tr>';
                break;
            case "p_objednavky":
                global $db_pdo;
                global $pg;
                global $stav_objednavky;

                $sql = "SELECT ".TBL_PRODUKT_OBJ.".cena, ".TBL_PRODUKT.".name_sk as nazov FROM ".TBL_PRODUKT_OBJ.", ".TBL_PRODUKT." WHERE ".TBL_PRODUKT_OBJ.".id_produkt = ".TBL_PRODUKT.".id AND ".TBL_PRODUKT_OBJ.".id_objednavka = :id_objednavka";
                $stmt = $db_pdo->prepare($sql);
                $stmt->bindValue(':id_objednavka', $row["id"], PDO::PARAM_STR);
                $stmt->execute();
                $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $i = 0;
                $objednavka = '';
                foreach($records as $record)
                {
                    $i++;
                    $objednavka .= $i.'. '.$record["nazov"].' ('.(number_format($record["cena"],2,",","")).'&euro;)<br>';
                }
                $sql = "SELECT ".TBL_PRODUKT_OBJ.".cena, ".TBL_PRODUKT_OBJ.".id_produkt_group FROM ".TBL_PRODUKT_OBJ." WHERE ".TBL_PRODUKT_OBJ.".id_objednavka = :id_objednavka AND  ".TBL_PRODUKT_OBJ.".id_produkt = -1";
                $stmt = $db_pdo->prepare($sql);
                $stmt->bindValue(':id_objednavka', $row["id"], PDO::PARAM_STR);
                $stmt->execute();
                if ($stmt->rowCount()>0)
                {
                    $records_akcie = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach($records_akcie as $record_akcie)
                    {
                        $sql = "SELECT ".TBL_AKCIE.".* FROM ".TBL_AKCIE." WHERE ".TBL_AKCIE.".id = :id";
                        $stmt = $db_pdo->prepare($sql);
                        $stmt->bindValue(':id', $record_akcie["id_produkt_group"], PDO::PARAM_STR);
                        $stmt->execute();
                        $akcia = $stmt->fetch(PDO::FETCH_ASSOC);

                        $i++;
                        $objednavka .= $i.'. <strong>AKCIA: </strong>'.$akcia["name_sk"].' ('.(number_format($record_akcie["cena"],2,",","")).'&euro;)<br>';
                    }
                }
                $neoverena = '';
                if ($row["ts_overena"]=='0000-00-00 00:00:00')
                {
                    $neoverena = '<div style="color:darkred;font-weight:bold;">NEOVERENÁ OBJEDNÁVKA</div>';
                }
                
                $vytvorena = '<div style="color:darkgreen;padding-bottom:4px;"><span style="background-color:#ebfceb;padding:4px;">'.date("j.n.Y H:i:s",strtotime($row["ts_created"])).'</span></div>';
                if ($row["ts_created"]!=$row["ts_radenie"])
                {
                  $dorucit = '<div style="color:white;font-weight:bold;font-size:18px;padding-bottom:4px;"><span style="background-color:darkred;padding:4px 8px;">'.date("H:i",strtotime($row["ts_radenie"])).'</span></div>';
                }
                else
                {
                  $dorucit = '';
                }
                
                $osobne = '';
                if ($row["osobne"]==1)
                {
                    $osobne = '<div style="color:darkred;padding-bottom:4px;"><span style="background-color:#ffd1e4;padding:4px;">OSOBNÝ ODBER NA POBOČKE</span></div>';
                }
                
                $cache = '<tr>';
                $cache .= '<th scope="row">'.$neoverena.'<div><a href="javascript:decision(\'Skutočne chcete záznam vymazať?\',\''.DIR_WWW_ROOT.'admin/include/action.php?action_id=del_'.$this->pg.'&amp;id='.$row["id"].'\')" class="adm_btn_action">del</a> <a href="'.DIR_WWW_ROOT.'admin/index.php?pg='.$this->pg.'&amp;akcia=detail&amp;id='.$row["id"].'" class="adm_btn_action">spracovať</a> <a href="'.DIR_WWW_ROOT.'admin/include/action.php?action_id=generuj_pdf&amp;id='.$row["id"].'" class="adm_btn_action" target="_blank">pdf</a></div></th>';
                $cache .= '<td class="text-left">'.$row["id"].'</td>';
                $cache .= '<td class="d-none d-md-table-cell text-left">'.$vytvorena.$objednavka.'</td>';
                if ($row["osobne"]==1)
                {
                  $cache .= '<td class="text-right">'.(number_format($row["suma"],2,",","")).'&euro;</td>';
                }
                else
                {
                  $cache .= '<td class="text-right">'.(number_format($row["suma"],2,",","")).'&euro;<br />+'.(number_format($row["suma_doprava"],2,",","")).'&euro;<br /><strong>'.(number_format(($row["suma"]+$row["suma_doprava"]),2,",","")).'&euro;</strong></td>';
                }
                if ($row["osobne"]==1)
                {
                  $cache .= '<td class="d-none d-md-table-cell text-left">'.$osobne.$dorucit.'<strong>'.$row["adresa"].'</strong><br />'.$row["meno"].'<br><strong>'.$row["telefon"].'</strong></td>';
                }
                else
                {
                  $sql = "SELECT ".TBL_ROZVOZ_ZONA.".zona FROM ".TBL_ROZVOZ_ZONA." WHERE id = :id";
                  $stmt = $db_pdo->prepare($sql);
                  $stmt->bindValue(':id', $row["id_rozvoz_zona"], PDO::PARAM_INT);
                  $stmt->execute();
                  $record_rz = $stmt->fetch(PDO::FETCH_ASSOC);
                  
                  $cache .= '<td class="d-none d-md-table-cell text-left">'.$osobne.$dorucit.'<strong>'.$row["adresa"].'</strong><br />'.$row["meno"].'<br><strong>'.$row["telefon"].'</strong><br />zóna: <strong>'.$record_rz["zona"].'</strong></td>';
                }
                $cache .= '<td class="d-none d-md-table-cell text-left">'.$stav_objednavky[$row["stav"]].'</td>';
                $cache .= '</tr>';
                break;    
            case "p_objednavky1":
                global $db_pdo;
                global $pg;
                global $stav_objednavky;

                $sql = "SELECT ".TBL_PRODUKT_OBJ.".cena, ".TBL_PRODUKT.".name_sk as nazov FROM ".TBL_PRODUKT_OBJ.", ".TBL_PRODUKT." WHERE ".TBL_PRODUKT_OBJ.".id_produkt = ".TBL_PRODUKT.".id AND ".TBL_PRODUKT_OBJ.".id_objednavka = :id_objednavka";
                $stmt = $db_pdo->prepare($sql);
                $stmt->bindValue(':id_objednavka', $row["id"], PDO::PARAM_STR);
                $stmt->execute();
                $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $i = 0;
                $objednavka = '';
                foreach($records as $record)
                {
                    $i++;
                    $objednavka .= $i.'. '.$record["nazov"].' ('.(number_format($record["cena"],2,",","")).'&euro;)<br>';
                }
                
                if ($row["ts_created"]!=$row["ts_radenie"])
                {
                  $dorucit = '<div style="color:white;font-weight:bold;font-size:18px;padding-bottom:4px;"><span style="background-color:darkred;padding:4px 8px;">'.date("H:i",strtotime($row["ts_radenie"])).'</span></div>';
                }
                else
                {
                  $dorucit = '';
                }
                
                $cache = '<tr>';
                $cache .= '<th scope="row"><div><a href="'.DIR_WWW_ROOT.'admin/index.php?pg='.$this->pg.'&amp;akcia=detail_kuchyna&amp;id='.$row["id"].'" class="adm_btn_action">detail</a> <a href="'.DIR_WWW_ROOT.'admin/index.php?pg='.$this->pg.'&amp;akcia=rozvoz&amp;id='.$row["id"].'" class="adm_btn_action">do rozvozu</a> <a href="'.DIR_WWW_ROOT.'admin/include/action.php?action_id=generuj_pdf&amp;id='.$row["id"].'" class="adm_btn_action" target="_blank">pdf</a></div></th>';
                $cache .= '<td class="text-left">'.$row["id"].'</td>';
                $cache .= '<td class="d-none d-md-table-cell text-left">'.$objednavka.'</td>';
                if ($row["osobne"]==1)
                {
                  $cache .= '<td class="text-right">'.$dorucit.(number_format($row["suma"],2,",","")).'&euro;</td>';
                }
                else
                {
                  $cache .= '<td class="text-right">'.$dorucit.(number_format($row["suma"],2,",","")).'&euro;<br />+'.(number_format($row["suma_doprava"],2,",","")).'&euro;<br /><strong>'.(number_format(($row["suma"]+$row["suma_doprava"]),2,",","")).'&euro;</strong></td>';
                }
                if ($row["osobne"]==1)
                {
                  $cache .= '<td class="d-none d-md-table-cell text-left"><strong>'.$row["adresa"].'</strong><br />'.$row["meno"].'<br><strong>'.$row["telefon"].'</strong></td>';
                }
                else
                {
                  $sql = "SELECT ".TBL_ROZVOZ_ZONA.".zona FROM ".TBL_ROZVOZ_ZONA." WHERE id = :id";
                  $stmt = $db_pdo->prepare($sql);
                  $stmt->bindValue(':id', $row["id_rozvoz_zona"], PDO::PARAM_INT);
                  $stmt->execute();
                  $record_rz = $stmt->fetch(PDO::FETCH_ASSOC);
                  
                  $cache .= '<td class="d-none d-md-table-cell text-left"><strong>'.$row["adresa"].'</strong><br />'.$row["meno"].'<br><strong>'.$row["telefon"].'</strong><br />zóna: <strong>'.$record_rz["zona"].'</strong></td>';
                }
                
                $cache .= '<td class="d-none d-md-table-cell text-left">'.$stav_objednavky[$row["stav"]].'</td>';
                $cache .= '</tr>';
                break;    
            case "p_objednavky2":
                global $db_pdo;
                global $pg;
                global $stav_objednavky;

                $sql = "SELECT ".TBL_PRODUKT_OBJ.".cena, ".TBL_PRODUKT.".name_sk as nazov FROM ".TBL_PRODUKT_OBJ.", ".TBL_PRODUKT." WHERE ".TBL_PRODUKT_OBJ.".id_produkt = ".TBL_PRODUKT.".id AND ".TBL_PRODUKT_OBJ.".id_objednavka = :id_objednavka";
                $stmt = $db_pdo->prepare($sql);
                $stmt->bindValue(':id_objednavka', $row["id"], PDO::PARAM_STR);
                $stmt->execute();
                $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $i = 0;
                $objednavka = '';
                foreach($records as $record)
                {
                    $i++;
                    $objednavka .= $i.'. '.$record["nazov"].' ('.(number_format($record["cena"],2,",","")).'&euro;)<br>';
                }
                
                $sql = "SELECT * FROM ".TBL_ROZVOZ." WHERE id = :id";
                $stmt = $db_pdo->prepare($sql);
                $stmt->bindValue(':id', $row["id_rozvoz"], PDO::PARAM_STR);
                $stmt->execute();
                $record_rozvoz = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($_SESSION["admin"]["auth_access_rights"]==1)
                {
                  $zmen_btn = '<br /><a href="'.DIR_WWW_ROOT.'admin/index.php?pg='.$this->pg.'&amp;akcia=zmen_rozvoz&amp;id='.$row["id"].'" class="adm_btn_action">zmena rozvozu</a>';
                }
                else
                {
                  $zmen_btn = '';
                }
                
                if ($row["ts_created"]!=$row["ts_radenie"])
                {
                  $dorucit = '<div style="color:white;font-weight:bold;font-size:18px;padding-bottom:4px;"><span style="background-color:darkred;padding:4px 8px;">'.date("H:i",strtotime($row["ts_radenie"])).'</span></div>';
                }
                else
                {
                  $dorucit = '';
                }
                
                $cache = '<tr>';
                $cache .= '<th scope="row"><div><a href="'.DIR_WWW_ROOT.'admin/index.php?pg='.$this->pg.'&amp;akcia=detail_rozvoz&amp;id='.$row["id"].'" class="adm_btn_action">detail</a> <a href="'.DIR_WWW_ROOT.'admin/index.php?pg='.$this->pg.'&amp;akcia=dorucena&amp;id='.$row["id"].'" class="adm_btn_action">doručená</a> <a href="'.DIR_WWW_ROOT.'admin/include/action.php?action_id=generuj_pdf&amp;id='.$row["id"].'" class="adm_btn_action" target="_blank">pdf</a>'.$zmen_btn.'</div></th>';
                $cache .= '<td class="text-left"><strong>'.$row["id"].'</strong></td>';
                $cache .= '<td class="d-none d-md-table-cell text-left">'.$record_rozvoz["nazov"].'</td>';
                $cache .= '<td class="text-left"><strong>'.$row["adresa"].'</strong><br />'.$row["meno"].'<br><strong>'.$row["telefon"].'</strong></td>';
                if ($row["osobne"]==1)
                {
                  $cache .= '<td class="text-right">'.$dorucit.(number_format($row["suma"],2,",","")).'&euro;</td>';
                }
                else
                {
                  $cache .= '<td class="text-right">'.$dorucit.(number_format($row["suma"],2,",","")).'&euro;<br />+'.(number_format($row["suma_doprava"],2,",","")).'&euro;<br /><strong>'.(number_format(($row["suma"]+$row["suma_doprava"]),2,",","")).'&euro;</strong></td>';
                }
                
                $cache .= '<td class="d-none d-md-table-cell text-left">'.$stav_objednavky[$row["stav"]].'</td>';
                $cache .= '</tr>';
                break;
            case "p_objednavky3":
                global $db_pdo;
                global $pg;
                global $stav_objednavky;

                $sql = "SELECT ".TBL_PRODUKT_OBJ.".cena, ".TBL_PRODUKT.".name_sk as nazov FROM ".TBL_PRODUKT_OBJ.", ".TBL_PRODUKT." WHERE ".TBL_PRODUKT_OBJ.".id_produkt = ".TBL_PRODUKT.".id AND ".TBL_PRODUKT_OBJ.".id_objednavka = :id_objednavka";
                $stmt = $db_pdo->prepare($sql);
                $stmt->bindValue(':id_objednavka', $row["id"], PDO::PARAM_STR);
                $stmt->execute();
                $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $i = 0;
                $objednavka = '';
                $dorucit = '';
                foreach($records as $record)
                {
                    $i++;
                    $objednavka .= $i.'. '.$record["nazov"].' ('.(number_format($record["cena"],2,",","")).'&euro;)<br>';
                }
                
                $cache = '<tr>';
                $cache .= '<th scope="row"><div><a href="'.DIR_WWW_ROOT.'admin/index.php?pg='.$this->pg.'&amp;akcia=detail&amp;id='.$row["id"].'" class="adm_btn_action">detail</a> <a href="'.DIR_WWW_ROOT.'admin/include/action.php?action_id=generuj_pdf&amp;id='.$row["id"].'" class="adm_btn_action" target="_blank">pdf</a></div></th>';
                $cache .= '<td class="d-none d-md-table-cell text-left">'.$objednavka.'</td>';
                if ($row["osobne"]==1)
                {
                  $cache .= '<td class="text-right">'.$dorucit.(number_format($row["suma"],2,",","")).'&euro;</td>';
                }
                else
                {
                  $cache .= '<td class="text-right">'.$dorucit.(number_format($row["suma"],2,",","")).'&euro;<br />+'.(number_format($row["suma_doprava"],2,",","")).'&euro;<br /><strong>'.(number_format(($row["suma"]+$row["suma_doprava"]),2,",","")).'&euro;</strong></td>';
                }
                $cache .= '<td class="d-none d-md-table-cell text-left"><strong>'.$row["adresa"].'</strong><br />'.$row["meno"].'<br><strong>'.$row["telefon"].'</strong></td>';
                $cache .= '<td class="d-none d-md-table-cell text-left">'.$stav_objednavky[$row["stav"]].'</td>';
                $cache .= '</tr>';
                break;
            case "p_zakaznik":
                global $db_pdo;
                global $pg;
                global $stav_objednavky;

                
                $cache = '<tr>';
                $cache .= '<th scope="row"><div><a href="'.DIR_WWW_ROOT.'admin/index.php?pg='.$this->pg.'&amp;akcia=edit&amp;id='.$row["id"].'" class="adm_btn_action">edit</a><a href="'.DIR_WWW_ROOT.'admin/index.php?pg='.$this->pg.'&amp;akcia=detail&amp;id='.$row["id"].'" class="adm_btn_action">detail</a></div></th>';
                $cache .= '<td class="d-none d-sm-table-cell text-left">'.$row["meno"].'</td>';
                $cache .= '<td class="text-left">'.$row["telefon"].'</td>';
                $cache .= '<td class="d-none d-md-table-cell text-left">'.$row["objednavky"].'</td>';
                $cache .= '<td class="d-none d-sm-table-cell text-right">'.(number_format($row["suma"],2,",","")).'&euro;</td>';
                if ($row["status"]==1)
                {
                    $cache .= '<td class="text-center"><i class="fas fa-check-circle fa-lg"></i></td>';
                }
                else
                {
                    $cache .= '<td class="text-center"><i class="fas fa-times-circle fa-lg"></i></td>';
                }
                $cache .= '</tr>';
                break;
            case "p_zakaznik_filter":
                global $db_pdo;
                global $pg;
                global $stav_objednavky;

                
                $cache = '<tr>';
                $cache .= '<th scope="row"><input type="checkbox" name="phone[]" value="'.$row["telefon"].'" class="phone_chkbx" /></th>';
                $cache .= '<td class="text-left">'.$row["meno"].'</td>';
                $cache .= '<td class="text-left">'.$row["telefon"].'</td>';
                $cache .= '<td class="text-left">'.$row["pocet_objednavok"].'</td>';
                $cache .= '<td class="text-right">'.(number_format($row["suma_objednavky"],2,",","")).'&euro;</td>';
                $cache .= '</tr>';
                break;
            case "p_telefonne_cisla":
                global $db_pdo;
                global $pg;
                global $stav_objednavky;

                
                $cache = '<tr>';
                $cache .= '<th scope="row"><div><a href="'.DIR_WWW_ROOT.'admin/index.php?pg='.$this->pg.'&amp;akcia=edit&amp;id='.$row["id"].'" class="adm_btn_action">edit</a></div></th>';
                $cache .= '<td class="text-left">'.$row["country"].'</td>';
                $cache .= '<td class="d-none d-md-table-cell text-left">'.$row["code"].'</td>';
                if ($row["status"]==1)
                {
                    $cache .= '<td><i class="fas fa-check-circle fa-lg"></i></td>';
                }
                else
                {
                    $cache .= '<td><i class="fas fa-times-circle fa-lg"></i></td>';
                }
                $cache .= '</tr>';
                break;
            case "p_telefonne_cisla_prefix":
                global $db_pdo;
                global $pg;
                global $id;
                
                $cache = '<tr>';
                $cache .= '<th scope="row"><div><a href="'.DIR_WWW_ROOT.'admin/index.php?pg='.$this->pg.'&amp;akcia=edit_prefix&amp;id='.$id.'&amp;id_prefix='.$row["id"].'" class="adm_btn_action">edit</a> <a href="javascript:decision(\'Skutočne chcete záznam vymazať?\',\''.DIR_WWW_ROOT.'admin/include/action.php?action_id=del_'.$this->pg.'_prefix&amp;id_prefix='.$row["id"].'&amp;id='.$id.'\')" class="adm_btn_action">del</a></div></th>';
                $cache .= '<td class="text-left">'.$row["prefix"].'</td>';
                if ($row["status"]==1)
                {
                    $cache .= '<td><i class="fas fa-check-circle fa-lg"></i></td>';
                }
                else
                {
                    $cache .= '<td><i class="fas fa-times-circle fa-lg"></i></td>';
                }
                $cache .= '</tr>';
                break;
            case "p_rozvoz":
                global $db_pdo;
                global $pg;
                global $stav_objednavky;

                if (date("G") <= OTVARACIA_DOBA_DO && OTVARACIA_DOBA_DO != -1)
                {
                    $datum = date("Y-m-d",(time()-(60*60*24))).' 00:00:00';
                }
                else 
                {
                    $datum = date("Y-m-d").' 00:00:00';
                }
                
                $sql = "SELECT ".TBL_OBJEDNAVKA.".id FROM ".TBL_OBJEDNAVKA." WHERE ".TBL_OBJEDNAVKA.".id_rozvoz = :id_rozvoz AND ts_dorucene = '0000-00-00 00:00:00'";
                $stmt = $db_pdo->prepare($sql);
                $stmt->bindValue(':id_rozvoz', $row["id"], PDO::PARAM_STR);
                $stmt->execute();
                $pocet_objednavok = $stmt->rowCount();
                
                $sql = "SELECT ".TBL_OBJEDNAVKA.".id FROM ".TBL_OBJEDNAVKA." WHERE ".TBL_OBJEDNAVKA.".id_rozvoz = :id_rozvoz AND ts_dorucene > '0000-00-00 00:00:00' AND ts_created > :datum";
                $stmt = $db_pdo->prepare($sql);
                $stmt->bindValue(':id_rozvoz', $row["id"], PDO::PARAM_STR);
                $stmt->bindValue(':datum', $datum, PDO::PARAM_STR);
                $stmt->execute();
                $pocet_dorucenych_objednavok = $stmt->rowCount();
                
                $sql = "SELECT login FROM ".TBL_ADMIN." WHERE rights = 3 AND extra = :extra";
                $stmt = $db_pdo->prepare($sql);
                $stmt->bindValue(':extra', $row["id"], PDO::PARAM_INT);
                $stmt->execute();
                $login = '-';
                if ($stmt->rowCount()>0)
                {
                    $record_admin = $stmt->fetch(PDO::FETCH_ASSOC);
                    $login = '<span style="color:darkred;">'.$record_admin["login"].'</span>';
                }
                
                
                
                $cache = '<tr>';
                $cache .= '<th scope="row"><div><a href="'.DIR_WWW_ROOT.'admin/index.php?pg='.$this->pg.'&amp;akcia=edit&amp;id='.$row["id"].'" class="adm_btn_action">edit</a><a href="javascript:decision(\'Skutočne chcete záznam vymazať?\',\''.DIR_WWW_ROOT.'admin/include/action.php?action_id=del_'.$this->pg.'&amp;id='.$row["id"].'\')" class="adm_btn_action">del</a></div></th>';
                $cache .= '<td class="text-left">'.$row["nazov"].'<br>'.$login.'</td>';
                $cache .= '<td class="d-none d-md-table-cell text-left">'.$row["telefon"].'</td>';
                $cache .= '<td class="d-none d-md-table-cell text-center">'.$pocet_objednavok.' / '.$pocet_dorucenych_objednavok.'</td>';
                if ($row["stav"]==1)
                {
                    $cache .= '<td class="text-center"><i class="fas fa-check-circle fa-lg"></i></td>';
                }
                else
                {
                    $cache .= '<td class="text-center"><i class="fas fa-times-circle fa-lg"></i></td>';
                }
                $cache .= '</tr>';
                break;
            case "p_ucty":
                global $db_pdo;
                global $pg;
                
                $cache = '<tr>';
                $cache .= '<th scope="row"><div><a href="'.DIR_WWW_ROOT.'admin/index.php?pg='.$this->pg.'&amp;akcia=edit&amp;id='.$row["id"].'" class="adm_btn_action">edit</a><a href="javascript:decision(\'Skutočne chcete záznam vymazať?\',\''.DIR_WWW_ROOT.'admin/include/action.php?action_id=del_'.$this->pg.'&amp;id='.$row["id"].'\')" class="adm_btn_action">del</a></div></th>';
                $cache .= '<td class="text-left">'.$row["login"].'</td>';
                $cache .= '<td class="d-none d-md-table-cell text-left">'.$row["last_login"].'</td>';
                if ($row["status"]==1)
                {
                    $cache .= '<td class="text-center"><i class="fas fa-check-circle fa-lg"></i></td>';
                }
                else
                {
                    $cache .= '<td class="text-center"><i class="fas fa-times-circle fa-lg"></i></td>';
                }
                $cache .= '</tr>';
                break;
            case "p_zmazane_objednavky":
                global $db_pdo;
                global $pg;
                global $stav_objednavky;

                $sql = "SELECT ".TBL_PRODUKT_OBJ_DEL.".cena, ".TBL_PRODUKT.".name_sk as nazov FROM ".TBL_PRODUKT_OBJ_DEL.", ".TBL_PRODUKT." WHERE ".TBL_PRODUKT_OBJ_DEL.".id_produkt = ".TBL_PRODUKT.".id AND ".TBL_PRODUKT_OBJ_DEL.".id_objednavka = :id_objednavka";
                $stmt = $db_pdo->prepare($sql);
                $stmt->bindValue(':id_objednavka', $row["id"], PDO::PARAM_STR);
                $stmt->execute();
                $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $i = 0;
                $objednavka = '';
                foreach($records as $record)
                {
                    $i++;
                    $objednavka .= $i.'. '.$record["nazov"].' ('.(number_format($record["cena"],2,",","")).'&euro;)<br>';
                }
                $sql = "SELECT ".TBL_PRODUKT_OBJ_DEL.".cena, ".TBL_PRODUKT_OBJ_DEL.".id_produkt_group FROM ".TBL_PRODUKT_OBJ_DEL." WHERE ".TBL_PRODUKT_OBJ_DEL.".id_objednavka = :id_objednavka AND  ".TBL_PRODUKT_OBJ_DEL.".id_produkt = -1";
                $stmt = $db_pdo->prepare($sql);
                $stmt->bindValue(':id_objednavka', $row["id"], PDO::PARAM_STR);
                $stmt->execute();
                if ($stmt->rowCount()>0)
                {
                    $records_akcie = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach($records_akcie as $record_akcie)
                    {
                        $sql = "SELECT ".TBL_AKCIE.".* FROM ".TBL_AKCIE." WHERE ".TBL_AKCIE.".id = :id";
                        $stmt = $db_pdo->prepare($sql);
                        $stmt->bindValue(':id', $record_akcie["id_produkt_group"], PDO::PARAM_STR);
                        $stmt->execute();
                        $akcia = $stmt->fetch(PDO::FETCH_ASSOC);

                        $i++;
                        $objednavka .= $i.'. <strong>AKCIA: </strong>'.$akcia["name_sk"].' ('.(number_format($record_akcie["cena"],2,",","")).'&euro;)<br>';
                    }
                }
                $neoverena = '';
                if ($row["ts_overena"]=='0000-00-00 00:00:00')
                {
                    $neoverena = '<div style="color:darkred;font-weight:bold;">NEOVERENÁ OBJEDNÁVKA</div>';
                }
                
                $vytvorena = '<div style="color:darkgreen;padding-bottom:4px;"><span style="background-color:#ebfceb;padding:4px;">'.date("j.n.Y H:i:s",strtotime($row["ts_created"])).'</span></div>';
                if ($row["ts_created"]!=$row["ts_radenie"])
                {
                  $dorucit = '<div style="color:white;font-weight:bold;font-size:18px;padding-bottom:4px;"><span style="background-color:darkred;padding:4px 8px;">'.date("H:i",strtotime($row["ts_radenie"])).'</span></div>';
                }
                else
                {
                  $dorucit = '';
                }
                
                $osobne = '';
                if ($row["osobne"]==1)
                {
                    $osobne = '<div style="color:darkred;padding-bottom:4px;"><span style="background-color:#ffd1e4;padding:4px;">OSOBNÝ ODBER NA POBOČKE</span></div>';
                }
                
                $cache = '<tr>';
                $cache .= '<th scope="row">'.$neoverena.'<div><a href="'.DIR_WWW_ROOT.'admin/index.php?pg='.$this->pg.'&amp;akcia=detail&amp;id='.$row["id"].'" class="adm_btn_action">detail</a></div></th>';
                $cache .= '<td class="text-left">'.$row["id"].'</td>';
                $cache .= '<td class="d-none d-md-table-cell text-left">'.$vytvorena.$objednavka.'</td>';
                if ($row["osobne"]==1)
                {
                  $cache .= '<td class="text-right">'.(number_format($row["suma"],2,",","")).'&euro;</td>';
                }
                else
                {
                  $cache .= '<td class="text-right">'.(number_format($row["suma"],2,",","")).'&euro;<br />+'.(number_format($row["suma_doprava"],2,",","")).'&euro;<br /><strong>'.(number_format(($row["suma"]+$row["suma_doprava"]),2,",","")).'&euro;</strong></td>';
                }
                if ($row["osobne"]==1)
                {
                  $cache .= '<td class="d-none d-md-table-cell text-left">'.$osobne.$dorucit.'<strong>'.$row["adresa"].'</strong><br />'.$row["meno"].'<br><strong>'.$row["telefon"].'</strong></td>';
                }
                else
                {
                  $sql = "SELECT ".TBL_ROZVOZ_ZONA.".zona FROM ".TBL_ROZVOZ_ZONA." WHERE id = :id";
                  $stmt = $db_pdo->prepare($sql);
                  $stmt->bindValue(':id', $row["id_rozvoz_zona"], PDO::PARAM_INT);
                  $stmt->execute();
                  $record_rz = $stmt->fetch(PDO::FETCH_ASSOC);
                  
                  $cache .= '<td class="d-none d-md-table-cell text-left">'.$osobne.$dorucit.'<strong>'.$row["adresa"].'</strong><br />'.$row["meno"].'<br><strong>'.$row["telefon"].'</strong><br />zóna: <strong>'.$record_rz["zona"].'</strong></td>';
                }
                //$cache .= '<td class="d-none d-md-table-cell text-left">'.$stav_objednavky[$row["stav"]].'</td>';
                $cache .= '</tr>';
                break;
  	}
  	return $cache;
    }
}

?>