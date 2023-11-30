$j = jQuery.noConflict();
$j(document).ready(function () {
  var nav = $j('#nav');
  $j('#left-header').hide();

  var token = '18108534610.1677ed0.6540c263c78e47e99f89dae723e9b6da',
    num_photos = 20;

  var i_div = 0; // celkovy counter
  var i_div2 = 0; // counter na pocitanie dvojic
  var i_div3 = 0; // counter na pocitanie sestic
  var i_div3_6 = 0;
  var cache = '';
  var i_inst_mobile = 6;
  var cache2 = '';

  $j.ajax({
    url: 'https://api.instagram.com/v1/users/self/media/recent',
    dataType: 'jsonp',
    type: 'GET',
    data: { access_token: token, count: num_photos },
    success: function (data) {
      instaDesktop(data),
        instaMobile(data)
    },
    error: function (data) {
      //console.log(data);
    }
  });


  function instaDesktop(data) {
    //console.log(data);
    for (x in data.data) {
      if (i_div2 === 0) {
        if (i_div3 === 1) {
          cache = cache + '<div class="insta-feed-divB">';
          i_div3 = 2;
        }
        else {
          cache = cache + '<div class="insta-feed-divA">';
        }
      }
      i_div = i_div + 1;
      i_div2 = i_div2 + 1;
      i_div3_6 = i_div3_6 + 1;
      if (i_div === 6) {
        i_div3 = 1;
      }
      if (i_div3 === 2) {
        cache = cache + '<a href="https://www.instagram.com/vitaboxsk/"><div class="img-box2"><img src="' + data.data[x].images.standard_resolution.url + '"></div></a>';
      }
      else {
        cache = cache + '<a href="https://www.instagram.com/vitaboxsk/"><div class="img-box"><img src="' + data.data[x].images.low_resolution.url + '"></div></a>';
      }

      if (i_div === 4) {
        i_div2 = 0;

        cache = cache + '</div><a href="https://www.instagram.com/vitaboxsk/"><div class="insta-feed-divA"><div class="inst-sleduj"><div class="insts-logo"></div><div class="insts-caption">Sleduj nás na instagrame</div><div class="insts-link">ZOBRAZIŤ PROFIL</div></div></a>';
        i_div = i_div + 1;
        i_div2 = i_div2 + 1;
        i_div3_6 = i_div3_6 + 1;
      }

      if (i_div2 === 2 || i_div3 === 2) {
        if (i_div3 === 2) {
          i_div3 = 0;
          i_div3_6 = 0;
        }
        cache = cache + '</div>';
        i_div2 = 0;
      }

    }
    $j('#instagram-feed').append(cache);
  };

  function instaMobile(data) {
    //console.log(data);
    i_div = 0;
    cache2 = '<div class="row" style="margin:0;">';
    for (x in data.data) {
      if (i_div < 5) {
        if (i_div === 1) {
          cache2 = cache2 + '<div class="col-6 m-inst-img-div"><a href="https://www.instagram.com/vitaboxsk/"><a href="https://www.instagram.com/vitaboxsk/"><img src="images/instagram-img.png" class="img-fluid"></a></div>';
        }
        cache2 = cache2 + '<div class="col-6 m-inst-img-div"><a href="https://www.instagram.com/vitaboxsk/"><img src="' + data.data[x].images.standard_resolution.url + '" class="img-fluid"></a></div>';
      }
      i_div = i_div + 1;
    }
    cache2 = cache2 + '</div>';
    $j('#instagram-feed-m').append(cache2);
  };

  // -------------------------------------------------------------
  //   Basic Navigation
  // -------------------------------------------------------------
  (function () {
    var $frame = $j('#basic');
    var $slidee = $frame.children('ul').eq(0);
    var $wrap = $frame.parent();
    var $navig = $j('.s-nami-buttons');

    // Call Sly on frame
    $frame.sly({
      horizontal: 1,
      itemNav: 'forceCentered',  // Item navigation type. Can be: 'basic', 'centered', 'forceCentered'.
      smart: 1,
      activateOn: 'click',
      mouseDragging: 1,
      touchDragging: 1,
      releaseSwing: 1,
      startAt: 0,
      scrollBar: $wrap.find('.scrollbar'),
      scrollBy: 1,
      speed: 3000,
      pagesBar: $wrap.find('.pages'),
      activatePageOn: 'click',
      elasticBounds: 1,
      easing: 'easeOutExpo',
      dragHandle: 1,
      dynamicHandle: 1,
      clickBar: 1,
      cycleBy: 'items',  // Enable automatic cycling by 'items' or 'pages'.
      cycleInterval: 8000,  // Delay between cycles in milliseconds.


      // Buttons
      prevPage: $navig.find('.snb-left'),
      nextPage: $navig.find('.snb-right')
    });

  }());

  $j(window).scroll(function () {
    var h_div = jQuery(".header").height();
    var h_div2 = h_div + 150;
    if ($j(this).scrollTop() > h_div) {
      nav.addClass("scroll-nav");
      $j('#header').hide();
      $j('#left-header').show();
      if ($j(this).scrollTop() > (h_div)) {
        jQuery("#nav_logo").removeClass("hide_div");
      }
      else {
        jQuery("#nav_logo").addClass("hide_div");
      }
    }
    else {
      nav.removeClass("scroll-nav");
      $j('#header').show();
      $j('#left-header').hide();
      jQuery(".nav_logo").hide();
    }
  });


  var person = [];

  person[0] = { text: "Vďaka Vitaboxu jem konečne pravidelne, zdravo a navyše veľmi chutne. S radosťou odporúčam :)", meno: "DOMINIKA ŽIARANOVÁ", povolanie: "herečka", fotka: g_www_root + "/images/klient/dominika-ziaranova.jpg" };
  person[1] = { text: "Cítim sa omnoho lepšie a moja výkonnosť rastie. Nežijem preto, aby som jedla, ale jem preto, aby som žila a s Vitaboxom sa mi to podarí.", meno: "MGR. JANKA ROZIAKOVÁ", povolanie: "kondičná trénerka / futbalistka Bundesligy", fotka: g_www_root + "/images/klient/janka-roziakova.jpg" };
  person[2] = { text: "Cvičím preto, aby som mohla jesť. To je moje heslo. Ale aj aby som mala dostatok energie a zároveň som nebola z jedla unavená.", meno: "TATIANA ŽIDEKOVÁ", povolanie: "Influencerka zdravého životného štýlu", fotka: g_www_root + "/images/klient/tatiana-zidekova.jpg" };
  person[3] = { text: "Keď sa ma niekto opýta: 'Tebe to chutí? Ako dlho chceš držať diétu?!' Moja odpoveď je: 'Toto nie je diéta, ale životný štýl a chutí mi to najviac na svete.' :)", meno: "LUCIA MOKRÁŇOVÁ", povolanie: "fitness trénerka", fotka: g_www_root + "/images/klient/lucia-mokranova.jpg" };
  person[4] = { text: "Posledné obdobie som bola so svojou hmotnosťou a zdravím ako na hojdačke. Preto som sa rozhodla pre zmenu. Už mesiac fungujem na Vitaboxe a ručička na váhe  ukázala -5 kíl.", meno: "DÁŠA ŠARKÓZYOVÁ", povolanie: "speváčka, herečka", fotka: g_www_root + "/images/klient/dasa-sarkozyova.jpg" };
  person[5] = { text: "S Vitaboxom sa cítim oveľa lepšie, schudla som, mám viac energie a samozrejme metabolizmus už pracuje ako má. Odporúčam.", meno: "MÁRIA ZELINOVÁ", povolanie: "modelka", fotka: g_www_root + "/images/klient/maria-zelinova.jpg" };
  person[6] = { text: "S Vitaboxom som schudla, mám viac energie a náročné dni zvládam ľahšie ako inokedy :)", meno: "KATKA BRYCHTOVÁ", povolanie: "moderátorka", fotka: g_www_root + "/images/klient/katka-brychtova.jpg" };
  person[7] = { text: "Vitabox som vyskúšal a presvedčil som sa, že jedlá výborne chutia a navyše spĺňajú zásady zdravej výživy. Odporúčam.", meno: "ANDREJ SEKERA", povolanie: "hráč NHL", fotka: g_www_root + "/images/klient/andrej-sekera.jpg" };

  var i_loop = 0;
  var i_loop_manual = -1;

  setTimeout(function () {
    clientCycle(i_loop);
  }, 6000);

  function clientCycle(i_loop) {
    var klient = '';
    var i = 0;
    var j = 0;
    var k = 0;
    if (i_loop_manual !== i_loop && i_loop_manual > -1) {
      i_loop = i_loop_manual;
      i_loop_manual = -1;
    }
    klient = i_loop;
    if (klient == 7) {
      i = klient;
      j = 0;
      k = 1;
    }
    else if (klient == 6) {
      i = klient;
      j = 7;
      k = 0;
    }
    else {
      i = klient;
      j = klient + 1;
      k = klient + 2;
    }

    $j(".klient").removeClass("active");
    $j("#klient" + i_loop).addClass("active");

    //      $j(".zakaznik-1").animate({
    //        opacity: '0'
    //      },2000);
    $j("#z1-meno").html(person[i].meno);
    $j("#z1-povolanie").html(person[i].povolanie);
    $j("#z1-text").html(person[i].text);
    $j("#z1-img").attr("src", person[i].fotka);
    //      $j(".zakaznik-1").animate({
    //        opacity: '1'
    //      },2000);

    //      $j(".zakaznik-2").animate({
    //        opacity: '0'
    //      },2000);
    $j("#z2-meno").html(person[j].meno);
    $j("#z2-povolanie").html(person[j].povolanie);
    $j("#z2-text").html(person[j].text);
    $j("#z2-img").attr("src", person[j].fotka);
    //      $j(".zakaznik-2").animate({
    //        opacity: '1'
    //      },2000);

    //      $j(".zakaznik-3").animate({
    //        opacity: '0'
    //      },2000);
    $j("#z3-meno").html(person[k].meno);
    $j("#z3-povolanie").html(person[k].povolanie);
    $j("#z3-text").html(person[k].text);
    $j("#z3-img").attr("src", person[k].fotka);
    //      $j(".zakaznik-3").animate({
    //        opacity: '1'
    //      },2000);

    i_loop = i_loop + 1;
    if (i_loop == 8) {
      i_loop = 0;
    }
    setTimeout(function () {
      clientCycle(i_loop);
    }, 6000);
  }

  $j(document).on("click", ".klient", function (event) {
    event.preventDefault();
    var klient = '';
    var text = '';
    var meno = '';
    var povolanie = '';
    var fotka = '';
    var i = 0;
    var j = 0;
    var k = 0;
    klient = $j(this).data('id_klient') * 1;
    i_loop_manual = klient + 1;
    i = klient;
    if (klient == 7) {
      i = klient;
      j = 0;
      k = 1;
    }
    else if (klient == 6) {
      i = klient;
      j = 7;
      k = 0;
    }
    else {
      i = klient;
      j = klient + 1;
      k = klient + 2;
    }

    $j(".klient").removeClass("active");
    $j("#klient" + klient).addClass("active");

    //        person[0] = {text:"Vďaka Vitaboxu jem konečne pravidelne, zdravo a navyše veľmi chutne. S radosťou odporúčam :)", meno:"DOMINIKA ŽIARANOVÁ", povolanie:"herečka", fotka: g_www_root + "/images/klient/dominika-ziaranova.jpg"};
    //        person[1] = {text:"Cítim sa omnoho lepšie a moja výkonnosť rastie. Nežijem preto, aby som jedla, ale jem preto, aby som žila a s Vitaboxom sa mi to podarí.", meno:"MGR. JANKA ROZIAKOVÁ", povolanie:"kondičná trénerka / futbalistka Bundesligy", fotka: g_www_root + "/images/klient/janka-roziakova.jpg"};
    //        person[2] = {text:"Cvičím preto, aby som mohla jesť. To je moje heslo. Ale aj aby som mala dostatok energie a zároveň som nebola z jedla unavená.", meno:"TATIANA ŽIDEKOVÁ", povolanie:"Influencerka zdravého životného štýlu", fotka: g_www_root + "/images/klient/tatiana-zidekova.jpg"};
    //        person[3] = {text:"Keď sa ma niekto opýta: 'Tebe to chutí? Ako dlho chceš držať diétu?!' Moja odpoveď je: 'Toto nie je diéta, ale životný štýl a chutí mi to najviac na svete.' :)", meno:"LUCIA MOKRÁŇOVÁ", povolanie:"fitness trénerka", fotka: g_www_root + "/images/klient/lucia-mokranova.jpg"};
    //        person[4] = {text:"Posledné obdobie som bola so svojou hmotnosťou a zdravím ako na hojdačke. Preto som sa rozhodla pre zmenu. Už mesiac fungujem na Vitaboxe a ručička na váhe  ukázala -5 kíl.", meno:"DÁŠA ŠARKÓZYOVÁ", povolanie:"speváčka, herečka", fotka: g_www_root + "/images/klient/dasa-sarkozyova.jpg"};
    //        person[5] = {text:"S Vitaboxom sa cítim oveľa lepšie, schudla som, mám viac energie a samozrejme metabolizmus už pracuje ako má. Odporúčam.", meno:"MÁRIA ZELINOVÁ", povolanie:"modelka", fotka: g_www_root + "/images/klient/maria-zelinova.jpg"};
    //        person[6] = {text:"S Vitaboxom som schudla, mám viac energie a náročné dni zvládam ľahšie ako inokedy :)", meno:"KATKA BRYCHTOVÁ", povolanie:"moderátorka", fotka: g_www_root + "/images/klient/katka-brychtova.jpg"};
    //        person[7] = {text:"Vitabox som vyskúšal a presvedčil som sa, že jedlá výborne chutia a navyše spĺňajú zásady zdravej výživy. Odporúčam.", meno:"ANDREJ SEKERA", povolanie:"hráč NHL", fotka: g_www_root + "/images/klient/katka-brychtova.jpg"};
    $j("#z1-meno").html(person[i].meno);
    $j("#z1-povolanie").html(person[i].povolanie);
    $j("#z1-text").html(person[i].text);
    $j("#z1-img").attr("src", person[i].fotka);

    $j("#z2-meno").html(person[j].meno);
    $j("#z2-povolanie").html(person[j].povolanie);
    $j("#z2-text").html(person[j].text);
    $j("#z2-img").attr("src", person[j].fotka);

    $j("#z3-meno").html(person[k].meno);
    $j("#z3-povolanie").html(person[k].povolanie);
    $j("#z3-text").html(person[k].text);
    $j("#z3-img").attr("src", person[k].fotka);
  });

  $j(document).on("click", ".klient-m", function (event) {
    event.preventDefault();
    var i = 0;
    i = $j(this).data('id_klient') * 1;

    $j(".klient-m").removeClass("active");
    $j("#klient-m" + i).addClass("active");

    $j("#z-meno").html(person[i].meno);
    $j("#z-povolanie").html(person[i].povolanie);
    $j("#z-text").html(person[i].text);
    $j("#z-img").attr("src", person[i].fotka);
  });

  $j(document).on("click", ".nav-content", function (event) {
    event.preventDefault();
    var thisItem = this;
    var week = $j(this).data('week');
    var nav = $j(this).data('nav');
    var den = $j(this).data('den');
    var tab = $j(this).data('tab');
    var nasledujuci_den = '';
    var den_code = '';
    switch (den) {
      case "pondelok":
        if (nav == "prev") {
          nasledujuci_den = 'sobota';
          den_code = 'so';
        }
        else {
          nasledujuci_den = 'utorok';
          den_code = 'ut';
        }
        break;
      case "utorok":
        if (nav == "prev") {
          nasledujuci_den = 'pondelok';
          den_code = 'po';
        }
        else {
          nasledujuci_den = 'streda';
          den_code = 'st';
        }
        break;
      case "streda":
        if (nav == "prev") {
          nasledujuci_den = 'utorok';
          den_code = 'ut';
        }
        else {
          nasledujuci_den = 'štvrtok';
          den_code = 'stv';
        }
        break;
      case "štvrtok":
        if (nav == "prev") {
          nasledujuci_den = 'streda';
          den_code = 'str';
        }
        else {
          nasledujuci_den = 'piatok';
          den_code = 'pi';
        }
        break;
      case "piatok":
        if (nav == "prev") {
          nasledujuci_den = 'štvrtok';
          den_code = 'stv';
        }
        else {
          nasledujuci_den = 'sobota';
          den_code = 'so';
        }
        break;
      case "sobota":
        if (nav == "prev") {
          nasledujuci_den = 'piatok';
          den_code = 'pi';
        }
        else {
          nasledujuci_den = 'pondelok';
          den_code = 'po';
        }
        break;
    }


    $j(this).parent("div")
      .find('.thw-caption-m')
      .html('<i class="fas fa-spinner fa-pulse"></i>');

    var request = $j.ajax({
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-menu-m', tab: tab, week: week, nasledujuci_den: nasledujuci_den, den: den, den_code: den_code }
    });

    request.done(function (rtn_data) {
      //console.log(rtn_data);
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);
      if (obj.result == "ok") {
        $j('#tblcc-' + tab + '-content-m').html(obj.div_data);
        $j(thisItem).parent("div")
          .find('.thw-caption-m')
          .html(obj.nasledujuci_den);


        $j(thisItem)
          .parent("div")
          .find('.nav-content')
          .attr('data-den', nasledujuci_den);

        $j(thisItem)
          .parent("div")
          .find('.nav-content')
          .data('den', nasledujuci_den);
      }
      else {
        $j('#tblcc-' + tab + '-content-m').html(obj.div_data);

        $j(thisItem).parent("div")
          .find('.thw-caption-m')
          .html('CHYBA!');
      }
      return false;
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });
  });


  $j(document).on("click", ".arr-right", function (event) {
    event.preventDefault();
    var pobocka = '';
    pobocka = $j(this).data('id');
    $j('.pb-extra').addClass('pbe-hide');
    $j('#' + pobocka).removeClass("pbe-hide");
  });

  $j(document).on("click", "#ako-to-funguje-viac", function (event) {
    event.preventDefault();
    $j(".li_hidden").removeClass('li_hidden');
    $j("#ako-to-funguje-viac-div").css('display', 'none');
  });

  $j(document).on("click", "#nl_suhlas", function (event) {
    event.preventDefault();
    var suhlas = 0;
    suhlas = $j(this).data('suhlas');
    if (suhlas == 0) {
      $j(this).removeClass('nl_suhlas_0');
      $j(this).addClass('nl_suhlas_1');
      $j(this).data('suhlas', "1");
      $j(this).attr('data-suhlas', "1");
      $j('#newsletter_form_suhlas').val(1);
    }
    else {
      $j(this).removeClass('nl_suhlas_1');
      $j(this).addClass('nl_suhlas_0');
      $j(this).data('suhlas', "0");
      $j(this).attr('data-suhlas', "0");
      $j('#newsletter_form_suhlas').val(0);
    }
  });

  $j(document).on("click", "#nl_suhlas-m", function (event) {
    event.preventDefault();
    var suhlas = 0;
    suhlas = $j(this).data('suhlas');
    if (suhlas == 0) {
      $j(this).removeClass('nl_suhlas_0');
      $j(this).addClass('nl_suhlas_1');
      $j(this).data('suhlas', "1");
      $j(this).attr('data-suhlas', "1");
      $j('#newsletter_form_suhlas-m').val(1);
    }
    else {
      $j(this).removeClass('nl_suhlas_1');
      $j(this).addClass('nl_suhlas_0');
      $j(this).data('suhlas', "0");
      $j(this).attr('data-suhlas', "0");
      $j('#newsletter_form_suhlas-m').val(0);
    }
  });

  $j(document).on("click", ".tmm-menu-hbg", function (event) {
    event.preventDefault();
    if ($j('#tmm-menu').css("display") === "none") {
      $j('#tmm-menu').css("display", "block");
    }
    else {
      $j('#tmm-menu').css("display", "none");
    }
  });

  $j(document).on("click", ".tblch.cennik-tab", function (event) {
    event.preventDefault();
    $j('.tblch').removeClass('active');
    $j(this).addClass('active');
    $j('.tblc-content').toggleClass('hidden-div');
  });

  $j(document).on("click", ".tblch.menu-tab", function (event) {
    event.preventDefault();
    var tab = '';
    tab = $j(this).data('tab');
    $j('.tblch').removeClass('active');
    $j(this).addClass('active');
    $j('.tblc-content').addClass('hidden-div');
    $j('#menu-' + tab).removeClass('hidden-div');
  });

  $j(document).on("click", ".tab", function (event) {
    event.preventDefault();
    var tab = '';
    tab = $j(this).data('tab');
    $j('.tblch').removeClass('active');
    $j(this).addClass('active');
    $j('.tblc-content').addClass('hidden-div');
    $j('#menu-' + tab).removeClass('hidden-div');
  });

  $j(document).on("click", ".faq-header", function (event) {
    event.preventDefault();
    content = $j(this).data('content');
    $j('#' + content).toggleClass('hidden-div');
  });

  $j(document).on("click", "#konzultacie", function () {
    event.preventDefault();
    var val = $j(this).data('val');
    if (val === 0) {
      $j(this).data('val', "1");
      $j(this).attr('data-val', "1");
      $j("input[name='konzultacie']").val("1");
    }
    else {
      $j(this).data('val', "0");
      $j(this).attr('data-val', "0");
      $j("input[name='konzultacie']").val("0");
    }
    $j(this).toggleClass('active');
  });

  $j(document).on("click", ".tb", function (event) {
    event.preventDefault();
    $j(this).parent("div")
      .find('.tbc-detail')
      .toggleClass("hidden-div");
  });

  $j("#top-menu").on("mouseover", function (event) {
    event.preventDefault();
    $j("#top-sub-menu").animate({ top: "0px" });
  });

  // $j("#inv-stripe").on("mouseover", function (event) {
  //   event.preventDefault();
  //   $j("#top-sub-menu").animate({ top: "-430px" });
  // });

  $j(document).click(function () {
    if ($j("#top-sub-menu").css("top") == "0px") {
      $j("#top-sub-menu").animate({ top: "-430px" });
    }
  });


  // $j("#top-menu").click(function(event) {
  //     event.stopPropagation();
  // });

  $j(document).on("click", ".block-nav", function (event) {
    event.preventDefault();
    var id = $j(this).data('id');
    $j('#bc' + id).toggleClass('hidden-div');
    $j(this).toggleClass('expand');
  });


  /*
  * verzia pre vyber viacerych programov
  *
  $j(document).on("click",".program-box", function(event) {
    event.preventDefault();
    $j(this).toggleClass('active');
    $j(this).children('.clearfix').children('.pbi').children('.pbimg').toggleClass('active');
  });
  */

  // vyber programu
  $j(document).on("click", ".program-box", function (event) {
    event.preventDefault();

    var program_id = $j(this).data('program-id');
    $j('.program-box').removeClass('active');
    $j('.pbimg').removeClass('active');
    $j(this).addClass('active');
    $j(this).children('.clearfix').children('.pbi').children('.pbimg').addClass('active');

    $j('input[name="id_program"]').val(program_id);
    setSession('selected_id_program', program_id);
    dlzkaObjednavkyCheck();

    var request = $j.ajax({
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-cal-generate' }
    });

    request.done(function (rtn_data) {
      //console.log('cal-generate: ' + rtn_data);
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);
      if (obj.result == "ok") {
        $j('.obj-cal').html(obj.div_data);
      }
      else {

      }
      return false;
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });

    // ak bol vybrany program 9, 14, 16 alebo 17 tak nastavime dlzku objednavky na tyzden
    if (program_id == 9 || program_id == 14 || program_id == 16 || program_id == 17) {
      setSession('selected_dlzka_objednavky', 5);
      $j('input[name="dlzka_objednavky"]').val(5);
      $j('.dlzka-box').removeClass('active');
      $j('#dlzka-box-5').addClass('active');
    }
  });

  // vyber dlzky programu
  $j(document).on("click", ".dlzka-box", function (event) {
    event.preventDefault();

    var dlzka_objednavky = $j(this).data('dlzka');
    var id_program = $j('input[name="id_program"]').val();
    $j('.dlzka-box').removeClass('active');
    if (id_program == 9 || id_program == 14 || id_program == 16 || id_program == 17) {
      dlzka_objednavky = 5;
      $j('#dlzka-box-5').addClass('active');
    }
    else {
      $j(this).addClass('active');
    }


    $j('input[name="dlzka_objednavky"]').val(dlzka_objednavky);
    setSession('selected_dlzka_objednavky', dlzka_objednavky);
  });


  // vyber dna v kalendari

  // $j(document).on("click", ".cal-chkbx", function (event) {
  //   event.preventDefault();
  //   var datum = $j(this).data('datum');
  //   $j(this).toggleClass('selected');
  //   if ($j(this).hasClass('selected')) {
  //     $j('#' + datum).val(1);
  //   }
  //   else {
  //     $j('#' + datum).val(0);
  //   }
  // });

  $j(document).on("click", ".cal-chkbx", function (event) {
    event.preventDefault();
    var datum = $j(this).data('datum'); // datum, na ktory uzivatel klikol
    //console.log(datum);

    // zvoleny datum odosleme na asynchronne spracovanie skriptu action.php:

    var request = $j.ajax({
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-cal-click', datum: datum }
    });

    request.done(function (rtn_data) {
      //console.log('cal-click: ' + rtn_data);
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);
      if (obj.result == "ok") {
        $j('.obj-cal').html(obj.div_data);


        $j('#pridane-do-kosika').addClass('hidden-div');
        $j('#pridat-do-kosika').removeClass('hidden-div');

      }
      else {

      }
      return false;
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });
  });

  // pridavanie dni pre detox a restart menu
  $j(document).on("click", ".cal-d1-chkbx", function (event) {
    event.preventDefault();
    var datum = $j(this).data('datum'); // datum, na ktory uzivatel klikol
    //console.log(datum);

    // zvoleny datum odosleme na asynchronne spracovanie skriptu action.php:

    var request = $j.ajax({
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-cal-d-click', datum: datum }
    });

    request.done(function (rtn_data) {
      //console.log('cal-click: ' + rtn_data);
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);
      if (obj.result == "ok") {
        $j('.obj-cal').html(obj.div_data);


        $j('#pridane-do-kosika').addClass('hidden-div');
        $j('#pridat-do-kosika').removeClass('hidden-div');

      }
      else {

      }
      return false;
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });
  });

  $j(document).on("click", ".cal-m-chkbx", function (event) {
    event.preventDefault();
    var datum = $j(this).data('datum'); // datum, na ktory uzivatel klikol
    //console.log(datum);

    // zvoleny datum odosleme na asynchronne spracovanie skriptu action.php:

    var request = $j.ajax({
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-m-cal-click', datum: datum }
    });

    request.done(function (rtn_data) {
      //console.log('cal-click: ' + rtn_data);
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);
      if (obj.result == "ok") {
        $j('#tbl-m-calendar').html(obj.data_calendar);
        $j('#tbl-m-pocet-dni').html(obj.data_pocet_dni);
      }
      else {

      }
      return false;
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });
  });

  $j(document).on("click", ".cal-m-d1-chkbx", function (event) {
    event.preventDefault();
    var datum = $j(this).data('datum'); // datum, na ktory uzivatel klikol
    //console.log(datum);

    // zvoleny datum odosleme na asynchronne spracovanie skriptu action.php:

    var request = $j.ajax({
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-m-cal-d-click', datum: datum }
    });

    request.done(function (rtn_data) {
      //console.log('cal-click: ' + rtn_data);
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);
      if (obj.result == "ok") {
        $j('#tbl-m-calendar').html(obj.data_calendar);
        $j('#tbl-m-pocet-dni').html(obj.data_pocet_dni);
      }
      else {

      }
      return false;
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });
  });

  $j(document).on("click", "#obj-dni-odstranit", function (event) {
    event.preventDefault();

    var request = $j.ajax({
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-m-cal-delete' }
    });

    request.done(function (rtn_data) {
      console.log('cal-click: ' + rtn_data);
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);
      if (obj.result == "ok") {
        $j('#tbl-m-calendar').html(obj.data_calendar);
        $j('#tbl-m-pocet-dni').html(obj.data_pocet_dni);
        $j('#cal-m-prev-nav').addClass('hidden-div');
        $j('#tblm-week').html(obj.week);
        $j('#cal-m-prev-nav').attr('data-week_day', obj.week_day);
        $j('#cal-m-prev-nav').data('week_day', obj.week_day);
        $j('#cal-m-next-nav').attr('data-week_day', obj.week_day);
        $j('#cal-m-next-nav').data('week_day', obj.week_day);
      }
      else {

      }
      return false;
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });
  });
  // $j(document).on("click","bt-modal", function(event) {
  //   event.preventDefault();
  // });

  $j(document).on("click", ".volba-pobocky-link", function (event) {
    var url_part = $j(this).data('back-url');
    $j('.volba-pobocky').each(function () {
      var oldUrl = $j(this).attr("href"); // Get current url
      var newUrl = oldUrl + 'b/' + url_part + '/'; // Create new url
      $j(this).attr("href", newUrl); // Set herf value
    });
  });

  // nahlad menu

  $j(document).on("click", ".tblch.obj-tab", function (event) {
    event.preventDefault();
    $j('#menu-1').toggleClass('hidden-div');
    $j('.arr-tab').toggleClass('active');
  });

  // doprava radio button
  $j(document).on("click", ".doprava-sbx", function (event) {
    event.preventDefault();
    var div_value = $j(this).data('value');
    $j("input[name='obj_doprava']").val(div_value);
    setSession('obj_doprava', div_value);
    $j('.doprava-sbx').removeClass('selected');
    $j(this).toggleClass('selected');
    $j('.doprava-volba-div').addClass('hidden-div');
    $j('#doprava-volba-' + div_value).removeClass('hidden-div');

    $j('#kosik-vyuctovanie-div').html('<div style="height: 182px; padding-top:60px;text-align:center;"><i class="fas fa-spinner fa-spin fa-2x t-green"></i></div>');

    var request = $j.ajax({
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-load-order-summary' }
    });

    request.done(function (rtn_data) {
      console.log('add-to-order: ' + rtn_data);
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);
      if (obj.result == "ok") {
        $j('#kosik-vyuctovanie-div').html(obj.data_kosik_vyuctovanie);
      }
      else {

      }
      return false;
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });

  });

  // doprava radio button - mobile
  $j(document).on("click", ".doprava-sbx-m", function (event) {
    event.preventDefault();
    var div_value = $j(this).data('value');
    $j("input[name='obj_doprava']").val(div_value);
    setSession('obj_doprava', div_value);
    $j('.doprava-sbx-m').removeClass('selected');
    $j(this).toggleClass('selected');
    if (div_value == 1) {
      $j("#doprava-odberne-miesto-div").addClass('hidden-div');
      $j("#doprava-lokalita-rozvozu-div").removeClass('hidden-div');
    } else {
      $j("#doprava-lokalita-rozvozu-div").addClass('hidden-div');
      $j("#doprava-odberne-miesto-div").removeClass('hidden-div');
    }

    $j('#obj-s3-medzisucet').html('<i class="fas fa-spinner fa-spin t-green"></i>');

    var request = $j.ajax({
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-m-obj-s3-medzisucet' }
    });

    request.done(function (rtn_data) {
      console.log('add-to-order: ' + rtn_data);
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);
      if (obj.result == "ok") {
        $j('#obj-s3-medzisucet').html(obj.medzisucet_html);
      }
      else {

      }
      return false;
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });

  });

  // lokalita rozvozu radio button
  $j(document).on("click", ".lokalita-sbx", function (event) {
    event.preventDefault();
    var div_value = $j(this).data('value');
    $j("input[name='obj_lokalita']").val(div_value);
    setSession('obj_lokalita', div_value);
    $j('.lokalita-sbx').removeClass('selected');
    $j(this).toggleClass('selected');

    $j('#kosik-vyuctovanie-div').html('<div style="height: 182px; padding-top:60px;text-align:center;"><i class="fas fa-spinner fa-spin fa-2x t-green"></i></div>');

    var request = $j.ajax({
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-load-order-summary' }
    });

    request.done(function (rtn_data) {
      console.log('add-to-order: ' + rtn_data);
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);
      if (obj.result == "ok") {
        $j('#kosik-vyuctovanie-div').html(obj.data_kosik_vyuctovanie);
      }
      else {

      }
      return false;
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });
  });

  // lokalita rozvozu radio button - mobile
  $j(document).on("click", ".lokalita-sbx-m", function (event) {
    event.preventDefault();
    var div_value = $j(this).data('value');
    $j("input[name='obj_lokalita']").val(div_value);
    setSession('obj_lokalita', div_value);
    $j('.lokalita-sbx-m').removeClass('selected');
    $j(this).toggleClass('selected');

    $j('#obj-s3-medzisucet').html('<i class="fas fa-spinner fa-spin t-green"></i>');

    var request = $j.ajax({
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-m-obj-s3-medzisucet' }
    });

    request.done(function (rtn_data) {
      console.log('add-to-order: ' + rtn_data);
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);
      if (obj.result == "ok") {
        $j('#obj-s3-medzisucet').html(obj.medzisucet_html);
      }
      else {

      }
      return false;
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });
  });

  // odberne miesto radio button
  $j(document).on("click", ".odberne-miesto-sbx", function (event) {
    event.preventDefault();
    var div_value = $j(this).data('value');
    $j("input[name='obj_odberne_miesto']").val(div_value);
    setSession('obj_odberne_miesto', div_value);
    $j('.odberne-miesto-sbx').removeClass('selected');
    $j(this).toggleClass('selected');
  });

  // platba radio button
  $j(document).on("click", ".platba-sbx", function (event) {
    event.preventDefault();
    var div_value = $j(this).data('value');
    $j("input[name='obj_platba']").val(div_value);
    setSession('obj_platba', div_value);
    $j('.platba-sbx').removeClass('selected');
    $j(this).toggleClass('selected');
  });

  // fakturacia pre firmy check box
  $j(document).on("click", "#fa-firma", function (event) {
    event.preventDefault();
    $j(this).toggleClass('selected');
    if ($j(this).hasClass('selected')) {
      $j('#fakturacne-udaje-firma').removeClass('hidden-div');
      $j("input[name='obj_fa_firma']").val(1);
      setSession('obj_fa_firma', 1);
    }
    else {
      $j('#fakturacne-udaje-firma').addClass('hidden-div');
      $j("input[name='obj_fa_firma']").val(0);
      setSession('obj_fa_firma', 0);
    }
  });

  // eko_obal radio button
  $j(document).on("click", "#eko-obal", function (event) {
    event.preventDefault();
    $j(this).toggleClass('selected');
    if ($j(this).hasClass('selected')) {
      $j(this).attr('data-value', 1);
      $j(this).data('value', 1);
    }
    else {
      $j(this).attr('data-value', 0);
      $j(this).data('value', 0);
    }
    var div_value = $j(this).data('value');
    $j("input[name='obj_eko_obal']").val(div_value);
    setSession('obj_eko_obal', div_value);

    $j('#kosik-vyuctovanie-div').html('<div style="height: 182px; padding-top:60px;text-align:center;"><i class="fas fa-spinner fa-spin fa-2x t-green"></i></div>');

    var request = $j.ajax({
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-load-order-summary' }
    });

    request.done(function (rtn_data) {
      console.log('add-to-order: ' + rtn_data);
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);
      if (obj.result == "ok") {
        $j('#kosik-vyuctovanie-div').html(obj.data_kosik_vyuctovanie);
      }
      else {

      }
      return false;
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });
  });

  // eko_obal radio button - mobile
  $j(document).on("click", "#eko-obal-m", function (event) {
    event.preventDefault();
    $j(this).toggleClass('selected');
    if ($j(this).hasClass('selected')) {
      $j(this).attr('data-value', 1);
      $j(this).data('value', 1);
    }
    else {
      $j(this).attr('data-value', 0);
      $j(this).data('value', 0);
    }
    var div_value = $j(this).data('value');
    $j("input[name='obj_eko_obal']").val(div_value);
    setSession('obj_eko_obal', div_value);

    $j('#obj-s2-medzisucet').html('<i class="fas fa-spinner fa-spin t-green"></i>');

    var request = $j.ajax({
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-m-obj-s2-medzisucet' }
    });

    request.done(function (rtn_data) {
      console.log('eko-obal-m: ' + rtn_data);
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);
      if (obj.result == "ok") {
        $j('#obj-s2-medzisucet').html(obj.medzisucet_html);
      }
      else {

      }
      return false;
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });
  });

  // pridanie vybranych dni a zvoleneho menu do kosika

  $j(document).on("click", "#add-to-order", function (event) {
    event.preventDefault();
    var mnozstvo = $j('#mnozstvo option:selected').val();
    var id_program = $j('#obj_id_program').val();

    //console.log('mnozstvo: ' + mnozstvo + ', program: ' + id_program);

    $j('#pridat-do-kosika').addClass('hidden-div');
    $j('#pridat-do-kosika-wait').removeClass('hidden-div');

    var request = $j.ajax({
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-add-to-order', mnozstvo: mnozstvo, id_program: id_program }
    });

    request.done(function (rtn_data) {
      console.log('add-to-order: ' + rtn_data);
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);
      if (obj.result == "ok") {
        $j('#obj-cal-div').html(obj.data_obj_cal);
        $j('#kosik-content-div').html(obj.data_kosik_content);
        $j('#kosik-vyuctovanie-div').html(obj.data_kosik_vyuctovanie);
        $j('#cash-back-hodnota').html(obj.data_cash_back_hodnota);

        $j('#pridat-do-kosika-wait').addClass('hidden-div');
        $j('#pridane-do-kosika').removeClass('hidden-div');

        $j('#kosik-content').removeClass('hidden-div');
        $j('#empty-kosik-content').addClass('hidden-div');

        $j('#doprava-disabled-content').addClass('hidden-div');
        $j('#doprava-content').removeClass('hidden-div');

        $j('#lokalita-disabled-content').addClass('hidden-div');
        $j('#lokalita-content').removeClass('hidden-div');

        $j('#odberne-miesto-disabled-content').addClass('hidden-div');
        $j('#odberne-miesto-content').removeClass('hidden-div');

        $j('#adresa-dorucenia-disabled-content').addClass('hidden-div');
        $j('#adresa-dorucenia-content').removeClass('hidden-div');

        $j('#platba-disabled-content').addClass('hidden-div');
        $j('#platba-content').removeClass('hidden-div');

        $j('#fakturacia-disabled-content').addClass('hidden-div');
        $j('#fakturacia-content').removeClass('hidden-div');
      }
      else {

      }
      return false;
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });
  });

  // odstranenie polozky z kosika - otazka
  $j(document).on("click", ".k_del_btn", function (event) {
    event.preventDefault();
    var id = $j(this).data('id');

    var request = $j.ajax({
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-remove-sc-item', id: id }
    });

    request.done(function (rtn_data) {
      //console.log('add-to-order: ' + rtn_data);
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);
      if (obj.result == "ok") {
        $j('#vymazat-polozku-div').html(obj.data_polozka);
        $j('#vymazatPolozku').modal();
      }
      else {

      }
      return false;
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });
    //vymazat-polozku-div
  });

  // odstranenie polozky z kosika - akcia
  //
  $j(document).on("click", "#delete-order-item", function (event) {
    event.preventDefault();
    var id = $j(this).data('id');

    $j('#vymazat-polozku-div').html('<div style="height: 206px; padding-top:80px;text-align:center;"><i class="fas fa-spinner fa-spin fa-2x t-green"></i></div>');

    var request = $j.ajax({
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-delete-sc-item', id: id }
    });

    request.done(function (rtn_data) {
      //console.log('add-to-order: ' + rtn_data);
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);
      if (obj.result == "ok") {
        $j('#vymazatPolozku').modal('hide');

        $j('#kosik-content-div').html(obj.data_kosik_content);
        $j('#kosik-vyuctovanie-div').html(obj.data_kosik_vyuctovanie);
        $j('#cash-back-hodnota').html(obj.data_cash_back_hodnota);

        if (obj.empty_kosik == 1) {
          $j('#kosik-content').addClass('hidden-div');
          $j('#empty-kosik-content').removeClass('hidden-div');

          $j('#doprava-disabled-content').removeClass('hidden-div');
          $j('#doprava-content').addClass('hidden-div');

          $j('#lokalita-disabled-content').removeClass('hidden-div');
          $j('#lokalita-content').addClass('hidden-div');

          $j('#odberne-miesto-disabled-content').removeClass('hidden-div');
          $j('#odberne-miesto-content').addClass('hidden-div');

          $j('#adresa-dorucenia-disabled-content').removeClass('hidden-div');
          $j('#adresa-dorucenia-content').addClass('hidden-div');

          $j('#platba-disabled-content').removeClass('hidden-div');
          $j('#platba-content').addClass('hidden-div');

          $j('#fakturacia-disabled-content').removeClass('hidden-div');
          $j('#fakturacia-content').addClass('hidden-div');
        }

      }
      else {

      }
      return false;
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });
    //vymazat-polozku-div
  });

  // suhlas - obchodne podmienky
  $j(document).on("click", "#chck-op", function (event) {
    event.preventDefault();
    var current_value = $j('#obj_suhlas_op').val();
    var next_value = 0;
    if (current_value == 1) {
      next_value = 0;
    } else {
      next_value = 1;
    }
    $j("input[name='obj_suhlas_op']").val(next_value);
    setSession('obj_suhlas_op', next_value);
    $j(this).toggleClass('selected');
    var current_value2 = $j('#obj_suhlas_ou').val();
    setOrderBtn();
  });

  // suhlas - obchodne podmienky - mobile
  $j(document).on("click", "#chck-op-m", function (event) {
    event.preventDefault();
    var current_value = $j('#obj_suhlas_op').val();
    var next_value = 0;
    if (current_value == 1) {
      next_value = 0;
    } else {
      next_value = 1;
    }
    $j("input[name='obj_suhlas_op']").val(next_value);
    setSession('obj_suhlas_op', next_value);
    $j(this).toggleClass('selected');
    var current_value2 = $j('#obj_suhlas_ou').val();
    setOrderBtnM();
  });

  // suhlas - osobne udaje
  $j(document).on("click", "#chck-ou", function (event) {
    event.preventDefault();
    var current_value = $j('#obj_suhlas_ou').val();
    var next_value = 0;
    if (current_value == 1) {
      next_value = 0;
    } else {
      next_value = 1;
    }
    $j("input[name='obj_suhlas_ou']").val(next_value);
    setSession('obj_suhlas_ou', next_value);
    $j(this).toggleClass('selected');
    var current_value2 = $j('#obj_suhlas_op').val();
    setOrderBtn();
  });

  // suhlas - osobne udaje - mobile
  $j(document).on("click", "#chck-ou-m", function (event) {
    event.preventDefault();
    var current_value = $j('#obj_suhlas_ou').val();
    var next_value = 0;
    if (current_value == 1) {
      next_value = 0;
    } else {
      next_value = 1;
    }
    $j("input[name='obj_suhlas_ou']").val(next_value);
    setSession('obj_suhlas_ou', next_value);
    $j(this).toggleClass('selected');
    var current_value2 = $j('#obj_suhlas_op').val();
    setOrderBtnM();
  });

  // suhlas - jedalny listok
  $j(document).on("click", "#chck-jl", function (event) {
    event.preventDefault();
    var current_value = $j('#obj_suhlas_jl').val();
    var next_value = 0;
    if (current_value == 1) {
      next_value = 0;
    } else {
      next_value = 1;
    }
    $j("input[name='obj_suhlas_jl']").val(next_value);
    setSession('obj_suhlas_jl', next_value);
    $j(this).toggleClass('selected');
  });

  // suhlas - news letter
  $j(document).on("click", "#chck-nl", function (event) {
    event.preventDefault();
    var current_value = $j('#obj_suhlas_nl').val();
    var next_value = 0;
    if (current_value == 1) {
      next_value = 0;
    } else {
      next_value = 1;
    }
    $j("input[name='obj_suhlas_nl']").val(next_value);
    setSession('obj_suhlas_nl', next_value);
    $j(this).toggleClass('selected');
  });

  // objednavka - button - zavazne objednat
  // $j(document).on("click", "#zavazne-objednat-btn", function (event) {
  //   event.preventDefault();
  //   if ($j(this).hasClass('general-green-btn-2')) {

  //   }
  // });

  // fakturacne udaje - kontrola
  $j(document).on("keypress", "#fa_meno, #fa_telefon, #fa_adresa, #adresa_dorucenia", function (event) {
    setOrderBtn();
  });



  // odoslanie formulara
  $j(document).on("click", ".btn-action", function (event) {
    event.preventDefault();
    var form_id = $j(this).data('form_id');

    switch (form_id) {
      case 'form-login':
        var r_value = checkLoginForm();
        $j('#c-fields').append('<div style="position: absolute;background:rgba(238,238,238,0.75); top:0px; left:0px; width: 360px; height: 110px; padding-top:46px;text-align:center;"><i class="fas fa-spinner fa-spin fa-2x t-green"></i></div>');
        break;
      case 'form-loginP':
        var r_value = checkLoginForm('P');
        break;
      case 'novy-zakaznik':
        var r_value = checkNovyZakaznikForm();
        break;
      case 'editacia-uctu':
        var r_value = checkEditAccount();
        break;
      case "zmena-hesla":
        var r_value = checkPasswordChange();
        break;
      case "add-m-item-to-order":
        var r_value = checkItemsToAdd();
        break;
      case "obj-krok-3":
        var r_value = { result: true };
        break;
      case "obj-krok-4":
        var doprava = $j('#obj_doprava').val();
        if (doprava==1)
        {
          var adresa_dorucenia = $j('#adresa_dorucenia').val();
          if (adresa_dorucenia.length<5)
          {
            var r_value = { result: false, modal_title: 'Chyba!', modal_body: 'Musíte zadať platnú adresu doručenia!' };
          } else
          {
            var r_value = { result: true };
          }
        }
        else {
          var r_value = { result: true };
        }
        break;
      case "obj-krok-5":
        if ($j(this).hasClass('general-green-btn-2')) {
          var r_value = { result: true };
        } else {
          var r_value = { result: false, modal_title: 'Chyba!', modal_body: 'Musíte si prečítať a súhlasiť s obchodnými podmienkami ako aj so spracovaním osobných údajov!' };
        }
        break;
        case "form-lost-pass":
            var r_value = { result: true };
          break;
    }
    if (r_value.result == true) {
      $j('#' + form_id).submit();
    }
    else {
      $j('#formErrorTitle').html(r_value.modal_title);
      $j('#formErrorDiv').html(r_value.modal_body);
      $j('#formError').modal();
    }
    event.stopPropagation();
  });

  $j(document).on("click", "#formErrorModal", function (event) {
    event.stopPropagation();
  });

  $j(document).on("click", "#top-sub-menu", function (event) {
    event.stopPropagation();
  });

  $j(document).on("click", "#chck-fa", function (event) {
    event.preventDefault();
    $j(this).toggleClass('selected');
    $j('#fa-div').toggleClass('hidden-div');
  });

  $j(document).on("click", "#chck-suhlas1", function (event) {
    event.preventDefault();
    var value = 0;
    $j(this).toggleClass('selected');
    if ($j(this).hasClass('selected')) {
      value = 1;
    }
    else {
      value = 0;
    }
    $j("input[name='suhlas1']").val(value);
  });

  $j(document).on("click", "#chck-suhlas2", function (event) {
    event.preventDefault();
    var value = 0;
    $j(this).toggleClass('selected');
    if ($j(this).hasClass('selected')) {
      value = 1;
    }
    else {
      value = 0;
    }
    $j("input[name='suhlas2']").val(value);
  });


  $j(document).on("change", "#kontakt", function (event) {
    event.preventDefault();
    var kontakt = $j('#kontakt option:selected').val();
    if (kontakt == "Od známeho") {
      $j('#id-kontakt').removeClass('hidden-div');
    }
    else {
      $j('#id-kontakt').addClass('hidden-div');
    }
  });

  $j(document).on("click", "#kontrola_id_znameho", function (event) {
    event.preventDefault();
    var id_kontakt = $j('#id_kontakt_znameho').val();
    var request = $j.ajax({
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-check-id-user', id_kontakt: id_kontakt }
    });

    request.done(function (rtn_data) {
      console.log(rtn_data);
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);
      if (obj.result == "ok") {
        $j('#id-kontakt-meno').html(obj.meno);
      }
      else {
        return false;
      }
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });
  });

  $j(document).on("click", "#uplatni-kredit", function (event) {
    event.preventDefault();
    $j('#kosik-vyuctovanie-div').html('<div style="height: 182px; padding-top:60px;text-align:center;"><i class="fas fa-spinner fa-spin fa-2x t-green"></i></div>');
    var kredit = $j(this).data('kredit');
    $j("input[name='obj_zlava_kredit']").val(kredit);
    setSession('obj_zlava_kredit', kredit);
    var request = $j.ajax({
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-uplatni-kredit', kredit: kredit }
    });

    request.done(function (rtn_data) {
      console.log(rtn_data);
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);
      if (obj.result == "ok") {
        $j('#kosik-vyuctovanie-div').html(obj.data_kosik_vyuctovanie);
      }
      else {
        return false;
      }
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });
  });


  $j(document).on("click", "#zrus-kredit", function (event) {
    event.preventDefault();
    $j('#kosik-vyuctovanie-div').html('<div style="height: 182px; padding-top:60px;text-align:center;"><i class="fas fa-spinner fa-spin fa-2x t-green"></i></div>');
    var kredit = 0;
    $j("input[name='obj_zlava_kredit']").val(kredit);
    setSession('obj_zlava_kredit', kredit);
    var request = $j.ajax({
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-uplatni-kredit', kredit: kredit }
    });

    request.done(function (rtn_data) {
      console.log(rtn_data);
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);
      if (obj.result == "ok") {
        $j('#kosik-vyuctovanie-div').html(obj.data_kosik_vyuctovanie);
      }
      else {
        return false;
      }
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });
  });

  $j(document).on("click", "#uplatni-zlavovy-kod", function (event) {
    event.preventDefault();
    $j('#zlavaKod').modal();
  });

  $j(document).on("click", "#zlavovy-kod-overit", function (event) {
    event.preventDefault();
    var zlavovy_kod = $j('#zlavovy_kod').val();
    $j('#zlavaKod').modal('hide');
    $j('#kosik-vyuctovanie-div').html('<div style="height: 182px; padding-top:60px;text-align:center;"><i class="fas fa-spinner fa-spin fa-2x t-green"></i></div>');

    var request = $j.ajax({
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-zlavovy-kod-overit', zlavovy_kod: zlavovy_kod }
    });

    request.done(function (rtn_data) {
      console.log(rtn_data);
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);
      if (obj.result == "ok") {
        setSession('obj_zlava_kod_vyska', obj.zlavovy_kod_vyska);
        setSession('obj_zlava_kod', obj.zlavovy_kod);
        $j("input[name='obj_zlava_kod']").val(obj.zlavovy_kod);
        $j("input[name='obj_zlava_kod_vyska']").val(obj.zlavovy_kod_vyska);
        $j('#kosik-vyuctovanie-div').html(obj.data_kosik_vyuctovanie);
      }
      else {
        $j('#kosik-vyuctovanie-div').html(obj.data_kosik_vyuctovanie);
      }
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });
  });

  // overenie zlavoveho kodu - mobile
  $j(document).on("click", "#zlavovy-kod-overit-m", function (event) {
    event.preventDefault();
    var zlavovy_kod = $j('#zlava-kod').val();

    $j('#zlavovy-kod-div').html('<div style="height: 124px; padding-top:60px;text-align:center;"><i class="fas fa-spinner fa-spin fa-2x t-green"></i></div>');
    $j('#obj-s4-medzisucet').html('<i class="fas fa-spinner fa-spin t-green"></i>');

    var request = $j.ajax({
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-zlavovy-kod-overit-m', zlavovy_kod: zlavovy_kod }
    });

    request.done(function (rtn_data) {
      console.log(rtn_data);
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);
      if (obj.result == "ok") {
        setSession('obj_zlava_kod_vyska', obj.zlavovy_kod_vyska);
        setSession('obj_zlava_kod', obj.zlavovy_kod);
        $j("input[name='obj_zlava_kod']").val(obj.zlavovy_kod);
        $j("input[name='obj_zlava_kod_vyska']").val(obj.zlavovy_kod_vyska);
        $j('#zlavovy-kod-div').html(obj.data_zlavovy_kod_div);
        $j('#obj-s4-medzisucet').html(obj.medzisucet_html);
      }
      else {
        $j('#zlavovy-kod-div').html('<div class="row"><div class="col-12 text-center">Ak vlastníte zľavový kód, prosím vložte ho do nasledovného poľa:</div></div><div class="row"><div class="col-6 offset-3"><input id="zlava-kod" type="text" class="form-control vbx-frm narrow" placeholder="" name="zlava-kod"></div></div><div class="row"><div class="col-12 text-center pt-2"><a href="" class="general-green-btn small-btn" id="zlavovy-kod-overit-m">Overiť</a></div></div>');
        $j('#obj-s4-medzisucet').html(obj.medzisucet_html);
      }
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });
  });

  // uplatnenie zlavy z kreditu - mobile
  $j(document).on("click", "#zlava-kredit-m", function (event) {
    event.preventDefault();
    var kredit = $j(this).data("zlava_kredit");
    setSession('obj_zlava_kredit', kredit);
    $j("input[name='obj_zlava_kredit']").val(kredit);
    $j('#obj-s4-medzisucet').html('<i class="fas fa-spinner fa-spin t-green"></i>');

    var request = $j.ajax({
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-m-obj-s4-medzisucet' }

    });

    request.done(function (rtn_data) {
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);
      if (obj.result == "ok") {
        $j('#obj-s4-medzisucet').html(obj.medzisucet_html);
        $j('#objednavka-kredit').html(obj.uplatneny_kredit_html);
        if (obj.zlava_kupon == 1) {
          $j('#zlavovy-kupon-div').html(obj.zlavovy_kupon_div);
        }
        if (obj.zlava_kredit == 1) {
          $j('#objednavka-kredit').html(obj.zlava_kredit_div);
        }
      }
      else {
        $j('#obj-s4-medzisucet').html('?');
      }
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });
  });

  // objednavka - button - zavazne objednat
  $j(document).on("click", "#zavazne-objednat-btn", function (event) {
    event.preventDefault();
    $j('#zo-btn-div').html('<i class="fas fa-spinner fa-spin fa-2x t-green" style="position:relative;top:10px;"></i>');
    $j('#obj-form').submit();
  });


  $j(document).on("change", "#m-id_program", function (event) {
    event.preventDefault();
    var id_program = $j('#m-id_program option:selected').val();
    if (id_program > 0) {
      setSession('selected_id_program', id_program);
      $j('#obj_id_program').val(id_program);

      var request = $j.ajax({
        url: g_www_root + "inc/action.php",
        type: "POST",
        data: { action_id: 'ajx-get-week', direction: 'none', week_day: 'actual' }

      });

      request.done(function (rtn_data) {
        obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);
        if (obj.result == "ok") {
          $j('#tbl-m-calendar').html(obj.data_calendar);
          if (obj.prev_button == "disabled") {
            $j('#cal-m-prev-nav').addClass('hidden-div');
          }
          else {
            $j('#cal-m-prev-nav').removeClass('hidden-div');
          }
          $j('#tblm-week').html(obj.week);
          $j('#cal-m-prev-nav').attr('data-week_day', obj.week_day);
          $j('#cal-m-prev-nav').data('week_day', obj.week_day);
          $j('#cal-m-next-nav').attr('data-week_day', obj.week_day);
          $j('#cal-m-next-nav').data('week_day', obj.week_day);
        }
        else {
          $j('#tbl-m-calendar').html(obj.data_calendar);
        }
      });

      request.fail(function (jqXHR, textStatus) {
        return false;
      });

      $j('#dlzka-objednavky-content, #objednavka-m-kalendar').removeClass('hidden-div');

      // ak bol vybrany program 9, 14, 16 alebo 17 tak nastavime dlzku objednavky na tyzden
      if (id_program == 9 || id_program == 14 || id_program == 16 || id_program == 17) {
        setSession('selected_dlzka_objednavky', 5);
        $j('input[name="dlzka_objednavky"]').val(5);
        $j('.dlzka-box').removeClass('active');
        $j('#dlzka-box-5').addClass('active');
      }
    }
  });


  $j(document).on("click", ".obj-m-week", function (event) {
    event.preventDefault();
    var week_day = $j(this).data('week_day');
    var direction = $j(this).data('direction');

    var request = $j.ajax({
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-get-week', direction: direction, week_day: week_day }

    });

    request.done(function (rtn_data) {
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);
      if (obj.result == "ok") {
        $j('#tbl-m-calendar').html(obj.data_calendar);
        if (obj.prev_button == "disabled") {
          $j('#cal-m-prev-nav').addClass('hidden-div');
        }
        else {
          $j('#cal-m-prev-nav').removeClass('hidden-div');
        }
        $j('#tblm-week').html(obj.week);
        $j('#cal-m-prev-nav').attr('data-week_day', obj.week_day);
        $j('#cal-m-prev-nav').data('week_day', obj.week_day);
        $j('#cal-m-next-nav').attr('data-week_day', obj.week_day);
        $j('#cal-m-next-nav').data('week_day', obj.week_day);
      }
      else {
        $j('#tbl-m-calendar').html(obj.data_calendar);
      }
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });
  });

  function checkItemsToAdd() {
    var request = $j.ajax({
      async: false,
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-items-to-add' }
    });

    request.done(function (rtn_data) {
      //console.log(rtn_data);
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);

      if (obj.result == "ok") {
        res = { result: true };
      }
      else {
        res = { result: false, modal_title: obj.modal_title, modal_body: obj.modal_body };
      }
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });

    return res;
  }

  function dlzkaObjednavkyCheck() {
    if (!$j('#dlzka-objednavky-disabled-content').hasClass('hidden-div')) {
      $j('#dlzka-objednavky-disabled-content').addClass('hidden-div')
    }
    if ($j('#dlzka-objednavky-content').hasClass('hidden-div')) {
      $j('#dlzka-objednavky-content').removeClass('hidden-div')
    }
  }

  function setOrderBtn() {
    var suhlas1 = $j('#obj_suhlas_op').val();
    var suhlas2 = $j('#obj_suhlas_ou').val();
    var fa_meno = $j('#fa_meno').val();
    var fa_telefon = $j('#fa_telefon').val();
    var fa_adresa = $j('#fa_adresa').val();
    var obj_doprava = $j('#obj_doprava').val();
    if (obj_doprava == 1) {
      var adresa_dorucenia = $j('#adresa_dorucenia').val();
      if (suhlas1 == 1 && suhlas2 == 1 && fa_meno.length > 2 && fa_adresa.length > 5 && fa_telefon.length > 4 && adresa_dorucenia.length > 5) {
        $j('#zavazne-objednat-btn').removeClass('general-disabled-btn-2');
        $j('#zavazne-objednat-btn').addClass('general-green-btn-2');
      }
      else {
        $j('#zavazne-objednat-btn').removeClass('general-green-btn-2');
        $j('#zavazne-objednat-btn').addClass('general-disabled-btn-2');
      }
    } else {
      if (suhlas1 == 1 && suhlas2 == 1 && fa_meno.length > 2 && fa_adresa.length > 5 && fa_telefon.length > 4) {
        $j('#zavazne-objednat-btn').removeClass('general-disabled-btn-2');
        $j('#zavazne-objednat-btn').addClass('general-green-btn-2');
      }
      else {
        $j('#zavazne-objednat-btn').removeClass('general-green-btn-2');
        $j('#zavazne-objednat-btn').addClass('general-disabled-btn-2');
      }
    }
  }

  function setOrderBtnM() {
    var suhlas1 = $j('#obj_suhlas_op').val();
    var suhlas2 = $j('#obj_suhlas_ou').val();
    if (suhlas1 == 1 && suhlas2 == 1) {
      $j('#zavazne-objednat-btn-m').removeClass('general-disabled-btn-2');
      $j('#zavazne-objednat-btn-m').addClass('general-green-btn-2');
    }
    else {
      $j('#zavazne-objednat-btn-m').removeClass('general-green-btn-2');
      $j('#zavazne-objednat-btn-m').addClass('general-disabled-btn-2');
    }
  }

  function setSession(session_name, session_value) {
    var request = $j.ajax({
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-set-session', session_name: session_name, session_value: session_value }
    });

    request.done(function (rtn_data) {
      //console.log(rtn_data);
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);
      if (obj.result == "ok") {

      }
      else {

      }
      return false;
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });
  }

  function checkLoginForm(typ='') {
    var email = $j('#formLoginEmail' + typ).val();
    var pass = $j('#formLoginPass' + typ).val();
    var res = new Object();
console.log('email: ' + email + ', pass: ' + pass);
    var request = $j.ajax({
      async: false,
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-form-check', form: 'login', email: email, pass: pass }
    });

    request.done(function (rtn_data) {
      //console.log(rtn_data);
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);

      if (obj.result == "ok") {
        res = { result: true };
      }
      else {
        res = { result: false, modal_title: obj.modal_title, modal_body: obj.modal_body };
      }
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });

    return res;
  }

  function checkNovyZakaznikForm() {
    var email = $j('#email').val();
    var pwdA = $j('#pwdA').val();
    var pwdB = $j('#pwdB').val();
    var pobocka = $j('#pobocka').val();
    var suhlas1 = $j('#suhlas1').val();

    var res = new Object();

    var request = $j.ajax({
      async: false,
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-form-check', form: 'novy-zakaznik', email: email, pwdA: pwdA, pwdB: pwdB, pobocka: pobocka, suhlas1: suhlas1 }
    });

    request.done(function (rtn_data) {
      //console.log(rtn_data);
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);

      if (obj.result == "ok") {
        res = { result: true };
      }
      else {
        res = { result: false, modal_title: obj.modal_title, modal_body: obj.modal_body };
      }
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });

    return res;
  }

  function checkEditAccount() {
    var email = $j('#email').val();

    var res = new Object();

    var request = $j.ajax({
      async: false,
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-form-check', form: 'editacia-uctu', email: email }
    });

    request.done(function (rtn_data) {
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);

      if (obj.result == "ok") {
        res = { result: true };
      }
      else {
        res = { result: false, modal_title: obj.modal_title, modal_body: obj.modal_body };
      }
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });

    return res;
  }

  function checkPasswordChange() {
    var actual_pwd = $j('#actual_pwd').val();
    var pwdA = $j('#pwdA').val();
    var pwdB = $j('#pwdB').val();

    var res = new Object();

    var request = $j.ajax({
      async: false,
      url: g_www_root + "inc/action.php",
      type: "POST",
      data: { action_id: 'ajx-form-check', form: 'change-my-password', actual_pwd: actual_pwd, pwdA: pwdA, pwdB: pwdB }
    });

    request.done(function (rtn_data) {
      console.log(rtn_data);
      obj = JSON && JSON.parse(rtn_data) || $j.parseJSON(rtn_data);

      if (obj.result == "ok") {
        res = { result: true };
      }
      else {
        res = { result: false, modal_title: obj.modal_title, modal_body: obj.modal_body };
      }
    });

    request.fail(function (jqXHR, textStatus) {
      return false;
    });

    return res;
  }
});


