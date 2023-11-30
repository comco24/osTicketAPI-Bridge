<?php
//******************************************************
//* paging class                                       *
//* Vytvara a zobrazuje strankovanie vypisu z databazy *
//******************************************************

class Pager {
  var $query;
  var $params;
  var $numRecs;
  var $output;
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
      $this->output .= '<a href="'.DIR_WWW_ROOT.'admin/'.$url_part.'p-'.($page-1).'.html"><i class="fas fa-angle-double-left"></i></a>';
    }
    for($i=$od;$i<=$do;$i++)
    {
      if ($start_dots==1 && $i==$od)
      {
        $this->output.='<a href="'.DIR_WWW_ROOT.'admin/'.$url_part.'p-1.html">1</a> ... ';
      }
      elseif ($end_dots==1 && $i==$do)
      {
        $this->output.=' ... <a href="'.DIR_WWW_ROOT.'admin/'.$url_part.'p-'.($this->numPages).'.html">'.$this->numPages.'</a>';
      }
      else
      {
      ($i!=$page) ? $this->output.='<a href="'.DIR_WWW_ROOT.'admin/'.$url_part.'p-'.($i).'.html">'.$i.'</a>' : $this->output.='<a href="'.DIR_WWW_ROOT.'admin/'.$url_part.'p-'.($i).'.html" class="btn_selected">'.$i.'</a>';
      }
    }
    if ($page!=$this->numPages)
    {
      $this->output.='<a href="'.DIR_WWW_ROOT.'admin/'.$url_part.'p-'.($page + 1).'.html"><i class="fas fa-angle-double-right"></i></a>';
    }
    $this->output .= '</div>';
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

    $stmt = $db_pdo->prepare($this->query.' LIMIT '.($page-1)*$this->numRecs.' , '.$this->numRecs);
    $stmt->execute($this->params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($this->numPages > 0)
    {
      foreach ($rows as $row)
      {
        echo $this->displayROWS($row,$page,$table,$part1);
      }
    }
  }

    function displayROWS($row,$page,$table,$part1)
    {
      global $basic;

      $cache = '';
      switch ($table)
      {
        case "p_log":
          $cache = $basic->getLogRecord($row);
          break;
        case "p_designer":
          $cache = $basic->getDesignerRecord($row);
          break;
        case "p_influencer":
          $cache = $basic->getInfluencerRecord($row);
          break;
        case "p_designer_portfolio":
          $cache = $basic->getDesignerPortfolioRecord($row);
          break;
      }
      return $cache;
    }
}

?>