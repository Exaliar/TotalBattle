import { newCalculator } from "./script.js";
function raport() {
  var gracz = 0;
  var gracz1 = $(".box").is(":checked");
  if (gracz1 == true) {
    gracz = 1;
  }
  $.ajax({
    method: "POST",
    url: "./php/oldWeb/raport.php",
    data: {
      gracz: gracz
    },
    success: function(rap) {
      $("#raport").html(rap);
    }
  });
}
export function oldMenu() {
  $(document).on("change", ".box", function() {
    raport();
  });
  //meni boczne
  $(document).on("click", "#zaloguj", function() {
    var nick = $("#nick1").val();
    var pass = $("#pass1").val();
    $.ajax({
      method: "POST",
      url: "./php/oldWeb/login.php",
      data: {
        loguj: "login",
        nick: nick,
        pass: pass
      }
    }).done(function(tak) {
      $("#login").html(tak);
    });
  });
  $(document).on("click", "#rejestruj", function() {
    $.ajax({
      method: "POST",
      url: "./php/oldWeb/login.php",
      data: { rejestruj: "rejestracja" }
    }).done(function(ttak) {
      $(".content").html(ttak);
    });
  });
  $(document).on("click", "#logout", function() {
    $.ajax({
      method: "POST",
      url: "./php/oldWeb/login.php",
      data: { logout: "logout" }
    }).done(function(log) {
      $("#login").html(log);
    });
    $.ajax({
      method: "POST",
      url: "login.php",
      data: { rejestruj: "rejestracja" }
    }).done(function(ttak) {
      $(".content").html(ttak);
    });
  });
  $(document).on("click", "#panel", function() {
    $.ajax({
      method: "POST",
      url: "./php/oldWeb/login.php",
      data: { panel: "panel" }
    }).done(function(pan) {
      $(".content").html(pan);
    });
  });
  //panel_administracyjny
  $(document).on("click", "#delete_panel, #klan_panel, #pass_panel, #email_panel", function() {
    let panel = [];
    $("#panel1 form .input-group input").each(function() {
      panel.push($(this).val());
    });
    console.log(panel);
    $.ajax({
      method: "POST",
      url: "./php/oldWeb/login.php",
      data: {
        panel_change: true,
        email_panel: panel[0],
        pass_panel1: panel[2],
        pass_panel2: panel[3],
        pass_panel3: panel[4],
        klan_panel: panel[6]
      }
    }).done(function(panel) {
      $(".content").html(panel);
    });
  });
  //wpis na forum
  $(document).on("click", "#conent", function() {
    var coment_1 = $("#coment_1");
    coment = coment_1.val();
    coment_1 = coment_1.val("");
    $.ajax({
      method: "POST",
      url: "./php/newWeb/projekt.php",
      data: {
        conent: coment
      }
    }).done(function(com) {
      $("#projekt_content").html(com);
    });
  });
  //LOGOWANIE
  $(document).on("click", ".btn-registration", function() {
    var milti = [];
    $(".content .registration-form form input").each(function() {
      milti.push($(this).val());
    });
    $.ajax({
      method: "POST",
      url: "./php/oldWeb/login.php",
      data: {
        name: milti[0],
        email: milti[1],
        pass_1: milti[2],
        pass_2: milti[3]
      }
    }).done(function(reg) {
      $(".content").html(reg);
    });
    $.ajax({
      method: "POST",
      url: "./oldWeb/login.php",
      data: {
        loguj: "login",
        nick: milti[0],
        pass: milti[2]
      }
    }).done(function(tak) {
      $("#login").html(tak);
    });
  });
  $(document).on("click", "#kalk", function() {
    $.ajax({
      method: "post",
      url: "./php/oldWeb/kalkulator.php",
      data: { meni: 1 }
    }).done(function(_val) {
      $(".content").html(_val);
      $(".top-menu-button-activ").toggleClass("top-menu-button-activ");
      $(".main-menu-activ").toggleClass("main-menu-activ");
    });
  });
  $(document).on("click", "#kalk_new", function() {
    $.ajax({
      method: "get",
      url: "php/newWeb/add_calk.php"
      //   data: { meni: 1 }
    }).done(function(_val) {
      $(".content").html(_val);
      newCalculator();
      $(".top-menu-button-activ").toggleClass("top-menu-button-activ");
      $(".main-menu-activ").toggleClass("main-menu-activ");
    });
  });
  $(document).on("click", "#about_me", function() {
    $.ajax({
      method: "get",
      url: "php/newWeb/aboutMe.php"
      //   data: { meni: 1 }
    }).done(function(_val) {
      $(".content").html(_val);
      $(".top-menu-button-activ").toggleClass("top-menu-button-activ");
      $(".main-menu-activ").toggleClass("main-menu-activ");
    });
  });
  $(document).on("click", "#proj", function() {
    $.ajax({
      method: "POST",
      url: "./php/newWeb/projekt.php",
      data: { projekt: 1 }
    }).done(function(proj) {
      $(".content").html(proj);
      $(".top-menu-button-activ").toggleClass("top-menu-button-activ");
      $(".main-menu-activ").toggleClass("main-menu-activ");
    });
  });
  $(document).on("change", ".gracz-old-menu", function() {
    var obecnie = $("#gracz tr").length - 1;
    var valflag = [];
    var ile = [];
    var bonus_ataku = [];
    var bonus_zycia = [];
    for (let i = 0; i < obecnie; i++) {
      valflag[i] = $("#pierwszy" + i).val() - 1;
      ile[i] = $("#ilosc" + i).val();
      bonus_ataku[i] = $("#bonus_ataku" + i).val();
      bonus_zycia[i] = $("#bonus_zycia" + i).val();
    }
    $.ajax({
      method: "POST",
      url: "./php/oldWeb/kalkulator.php",
      data: {
        change: 1,
        obecnie: obecnie,
        valflag: valflag,
        ilosc: ile,
        bonus_ataku: bonus_ataku,
        bonus_zycia: bonus_zycia
      }
    }).done(function(change) {
      $("#dane_gracza").html(change);
    });
    setTimeout(raport, 400);
  });
  //AJAX WYBRANE Z MENI I WYSLANIE DO KALKULATOR PHP
  $(document).on("click", "#zwykly", function() {
    var num_zwykly = this.value;
    $.ajax({
      method: "POST",
      url: "./php/oldWeb/kalkulator.php",
      data: { zwykly_id: num_zwykly }
    }).done(function(zwykly) {
      $("#kalkulator").html(zwykly);
    });
    setTimeout(raport, 400);
  });
  $(document).on("click", "#rzadki", function() {
    var num_rzadki = this.value;
    $.ajax({
      method: "post",
      url: "./php/oldWeb/kalkulator.php",
      data: { rzadki_id: num_rzadki }
    }).done(function(rzadki) {
      $("#kalkulator").html(rzadki);
    });
    setTimeout(raport, 400);
  });
  $(document).on("click", "#heroiczny", function() {
    var num_heroiczny = this.value;
    $.ajax({
      method: "POST",
      url: "./php/oldWeb/kalkulator.php",
      data: { heroiczny_id: num_heroiczny }
    }).done(function(heroiczny) {
      $("#kalkulator").html(heroiczny);
    });
    setTimeout(raport, 400);
  });
  $(document).on("click", "#inne", function() {
    var num_inne = this.value;
    $.ajax({
      method: "POST",
      url: "./php/oldWeb/kalkulator.php",
      data: { inne_id: num_inne }
    }).done(function(inne) {
      $("#kalkulator").html(inne);
    });
  });
  //KONIEC WYSYLANIA Z MIENI
  //DODANIE UZUWANIE JEDNOSTEK ILOSCI
  $(document).on("click", "#upper", function() {
    var obecnie = $("#gracz tr").length - 1;
    var valflag = [];
    var ile = [];
    var bonus_ataku = [];
    var bonus_zycia = [];
    for (let i = 0; i < obecnie; i++) {
      valflag[i] = $("#pierwszy" + i).val() - 1;
      valflag[i + 1] = 0;
      ile[i] = $("#ilosc" + i).val();
      ile[i + 1] = 0;
      bonus_ataku[i] = $("#bonus_ataku" + i).val();
      bonus_ataku[i + 1] = 0;
      bonus_zycia[i] = $("#bonus_zycia" + i).val();
      bonus_zycia[i + 1] = 0;
    }
    $.ajax({
      method: "POST",
      url: "./php/oldWeb/kalkulator.php",
      data: {
        upper: "",
        obecnie: obecnie,
        valflag: valflag,
        ilosc: ile,
        bonus_ataku: bonus_ataku,
        bonus_zycia: bonus_zycia
      }
    }).done(function(upper) {
      $("#dane_gracza").html(upper);
    });
    setTimeout(raport, 400);
  });
  $(document).on("click", "#lower", function() {
    var obecnie = $("#gracz tr").length - 1;
    var valflag = [];
    var ile = [];
    var bonus_ataku = [];
    var bonus_zycia = [];
    for (let i = 0; i < obecnie; i++) {
      valflag[i] = $("#pierwszy" + i).val() - 1;
      ile[i] = $("#ilosc" + i).val();
      bonus_ataku[i] = $("#bonus_ataku" + i).val();
      bonus_zycia[i] = $("#bonus_zycia" + i).val();
    }
    $.ajax({
      method: "POST",
      url: "./php/oldWeb/kalkulator.php",
      data: {
        lower: "",
        obecnie: obecnie,
        valflag: valflag,
        ilosc: ile,
        bonus_ataku: bonus_ataku,
        bonus_zycia: bonus_zycia
      }
    }).done(function(lower) {
      $("#dane_gracza").html(lower);
    });
    setTimeout(raport, 400);
  });
  //warunki z akademii
  $(document).on("click", "#menu ul li ul li", function() {
    var jaki = this.value;
    console.log(jaki);
    $.ajax({
      method: "POST",
      url: "akademia.php",
      data: { aka: jaki }
    }).done(function(aka) {
      $("#wyswietl").html(aka);
    });
  });

  // $(document).on("click", "#contact_button-email", function(event) {
  //   let email = $("#contact_placeholder-email").value;
  //   // console.log(email);
  // });

  $(document).on("click", ".top-menu-button", function() {
    $(".top-menu-button").toggleClass("top-menu-button-activ");
    $(".main-menu").toggleClass("main-menu-activ");
  });
}
