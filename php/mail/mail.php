<?php
   $naglowki ="";
   // Naglowki mozna sformatowac tez w ten sposob.
   $naglowki ="Reply-to: grzegorz@wp.pl <grzegorz@wp.pl>".PHP_EOL;
   $naglowki .="From: tb@wp.pl <tb@wp.pl>".PHP_EOL;
   $naglowki .="MIME-Version: 1.0".PHP_EOL;
   $naglowki .="Content-type: text/html; charset=UTF-8".PHP_EOL; 

function Email ($mail){
   if(mail('grzegorz@wp.pl', 'Witaj test mail', $mail))
   {
      // echo 'Wiadomość została wysłana';
   }
}