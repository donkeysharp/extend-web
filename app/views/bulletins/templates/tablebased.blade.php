<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Extend Comunicaciones - Boletín Diario de Noticias</title>
  <style type="text/css" media="screen">
    body {
      margin: 0;
      padding: 0;
      background-color: #F2F2F2;
      font-family: sans-serif;
      color: #404040;
    }
    h1 {
      margin-top: 5px;
      margin-bottom: 5px;
    }
    .container {
      width: 650px;
      margin-top: 20px;
      margin-left: auto;
      margin-right: auto;
      margin-bottom: 20px;
      background-color: #fff;
      position: relative;
      -webkit-box-sizing: border-box;
           -moz-box-sizing: border-box;
                box-sizing: border-box;
    }
    @media (max-width: 768px) {
      .container {
        margin-top: 10px;
        width: 400px;
      }
      .news-list .picture {
        width: 150px;
        display: hidden;
      }
    }
    @media (min-width: 768px) {
      .container {
        width: 650px;
      }
    }
    @media (min-width: 992px) {
      .container {
        width: 650px;
      }
    }
    @media (min-width: 1200px) {
      .container {
        width: 650px;
      }
    }
    .logo {
      clear: left;
      background-color: #e2e2e2;
    }
    .bulletin-title {
      text-align: center;
      font-size: 26px;
      color: #0082a4;
      font-weight: bold;
      padding-bottom: 30px;
      padding-top: 15px;
      border-bottom: 3px solid #0082a4;
    }
    .news-list {
      padding: 0 20px 0 20px;
      margin-top: 27px;
      -webkit-box-sizing: border-box;
           -moz-box-sizing: border-box;
                box-sizing: border-box;
    }
    .news-list span {
      font-size: 26px;
      font-weight: bolder;
      text-align: left;
    }
    .news-list p {
      font-size: 15px;
      text-align: justify;
    }
    .news-list span.title {
      font-size: 26px;
      font-weight: bolder;
    }
    .news-list .picture {
      float: left;
      width: 300px;
      margin-right: 10px;
      margin-bottom: 10px
    }
     @media (max-width: 768px) {
      .news-list .picture {
        width: 200px;
        display: hidden;
      }
    }
    .footer{
      padding-top: 15px;
      padding-bottom: 15px;
      background-color: #e2e2e2;
      font-size: 18px;
      color: #858585;
      text-align: center;
    }
  </style>
</head>
<body style=" margin: 0;padding: 0;background-color: #F2F2F2;font-family: sans-serif;color: #404040;">
<div class="container" style="width: 650px;margin-top: 20px;margin-left: auto;margin-right: auto;margin-bottom: 20px;background-color: #fff;position: relative;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;"
    >
  <div class="logo" style="clear: left; background-color: #e2e2e2;">
    <center><img src="{{asset('assets/img/bulletin/logo.png')}}" /></center>
    <div class="bulletin-title" style="text-align: center;font-size: 26px;color: #0082a4;font-weight: bold;padding-bottom: 30px;padding-top: 15px;border-bottom: 3px solid #0082a4;">
      Reporte {{Form::literalDate($date)}} - {{$client->name}}
    </div>
    <div style="border-bottom: 6px solid #0082a4; font-size:3px;">&nbsp;</div>
  </div>
  <div class="news-list" style="padding: 0 20px 0 20px;margin-top: 27px;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;">
    @foreach($subtitles as $s)
      <?php $displayed = false; ?>
      @foreach($details as $item)
        <?php
          $firstPicture = null;
          $firstPdf = null;
          foreach($item->news->uploads as $upload) {
            $type = strtolower($upload->type);
            if(($type === 'jpg' || $type === 'jpeg' || $type === 'png' || $type === 'gif') && !$firstPicture) {
              $firstPicture = $upload;
            }
            if(($type === 'pdf') && !$firstPdf) {
              $firstPdf = $upload;
            }
          }
        ?>
        @if($item->subtitle === $s->subtitle)
          @if(!$displayed)
            <h1 style="margin-top: 5px;margin-bottom: 5px;">{{$s->subtitle}}</h1>
            <?php $displayed = true; ?>
          @endif
          <span class="title" style="font-size: 26px;font-weight: bolder;">
            {{$item->title}}
          </span>
          <p style="font-size: 15px;text-align: justify;">
            @if($firstPicture)
              <img  src="{{asset('uploads/' . $firstPicture->file_name)}}" class="picture" style="float: left;width: 300px;margin-right: 10px;margin-bottom: 10px" />
            @endif
            {{{$item->description}}}
          </p>
          @if($item->web)
            <a href="{{$item->web}}" target="_blank">
              <img style="width: 25px; height: 25px" src="{{asset('assets/img/bulletin/url.png')}}" />
              Ver Nota Completa
            </a>
            <br>
          @endif
          <br>
          @if($firstPdf)
            <a href="{{asset('uploads/' . $firstPdf->file_name)}}" target="_blank">
              <img style="width: 25px; height: 25px" src="{{asset('assets/img/bulletin/pdf.jpeg')}}" />
              Ver PDF
            </a>
            <br>
          @endif
          <br>
        @endif
      @endforeach
    @endforeach
  </div>
  <div class="footer" style=" padding-top: 15px;padding-bottom: 15px;background-color: #e2e2e2;font-size: 18px;color: #858585;text-align: center;">
    <span style="font-size: 21px">MONITOREO PRENSA <b>EXTEND COMUNICACIONES BOLIVIA</b></span>
    <div style="margin-top: 5px; margin-bottom: 15px;border-bottom: 6px solid #548aae; font-size:3px;">&nbsp;</div>
    <i>
      <center><b>Contácenos: </b>Calacoto, Calle 18 N° 8022 Edificio Parque 18 Piso 2 Of. 2C</center>
      <center><b>Teléfonos: </b>(591-2) 2774373 - 2797733</center>
      <center><b>monitoreo.prensa@extend.com</b></center>
    </i>
  </div>
</div>
</body>
</html>
