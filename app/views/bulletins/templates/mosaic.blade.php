<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>*|MC:SUBJECT|*</title>
  <style type="text/css" media="screen">
    body {
      margin: 0;
      padding: 0;
      background-color: #F2F2F2;
      font-family: sans-serif;
      color: #404040;
    }
    .container {
      width: 600px;
      margin-top: 20px;
      margin-left: auto;
      margin-right: auto;
      margin-bottom: 20px;
      background-color: #fff;
      position: relative;
      padding: 20px;
      -webkit-box-sizing: border-box;
           -moz-box-sizing: border-box;
                box-sizing: border-box;
    }
    @media (max-width: 768px) {
      .container {
        margin-top: 10px;
        width: 400px;
      }
    }
    @media (min-width: 768px) {
      .container {
        width: 600px;
      }
    }
    @media (min-width: 992px) {
      .container {
        width: 600px;
      }
    }
    @media (min-width: 1200px) {
      .container {
        width: 600px;
      }
    }
    .logo {
      margin-top: 30px;
      clear: left;
    }
    .bulletin-title {
      text-align: center;
      font-size: 26px;
      color: #404040;
      font-weight: bold;
      padding-bottom: 30px;
      padding-top: 15px;
      border-bottom: 3px solid #777777;
    }
    .top-link {
      font-size: 11px;
      color: #666;
    }
    .footer{
      font-size: 11px;
      color: #666;
      text-align: center;
    }
    .news-list {
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
    .news-list img {
      float: left;
      width: 300px;
      height: 155px;
      margin-right: 10px;
      margin-bottom: 10px
    }
  </style>
</head>
<body>
<div class="container">
  <div class="top-link" style="float: left">Boletín diario de noticias</div>
  <div class="top-link" style="float: right">Ver en Navegador</div>
  <div class="logo">
    <img src="{{asset('assets/img/bulletin/logo.png')}}" />
  </div>
  <div class="bulletin-title">
    Reporte {{Form::literalDate($date)}} - {{$details[0]->news->client->name}}
  </div>
  <div class="news-list">
    @foreach($details as $item)
      <?php
        $firstPicture = null;
        $firstPdf = null;
        $firstUrl = count($item->news->urls) > 0 ? $item->news->urls[0]->url : null;
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
      <span>Noticias</span>
      <p>
        @if($firstPicture)
          <img src="{{asset('uploads/' . $firstPicture->file_name)}}" style="float: left" />
        @endif
        <span class="title">{{$item->title}}</span><br><br>
        {{{$item->description}}}
      </p>
      @if($firstUrl)
        <a href="{{$firstUrl}}" target="_blank">
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
    @endforeach
  </div>
  <div class="footer">
    <p>
    Calacoto, Calle 18 N° 8022, Edificio ¨Parque 18¨, Piso 2 - Oficina 2C | 2774373 - 2797733 - 76763800 | redesociales@extend.com.bo
    </p>
  </div>
</div>
</body>
</html>
