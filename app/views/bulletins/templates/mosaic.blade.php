<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Extend Comunicaciones - Boletín Diario de Noticias</title>
</head>
<body style="margin: 0;padding: 0;background-color: #F2F2F2;font-family: Helvetica, sans-serif;color: #404040;">
<table border="0"style="margin-top: 20px;margin-left: auto;margin-right: auto;margin-bottom: 20px;position: relative;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;">
<tr>
<td width="22%">&nbsp;</td>
<td width="56%" style="background-color: #fff; font-family: Helvetica, sans-serif;">
    <table border="0" style="background-color: #ffffff; width: 100%">
      <tr>
        <td width="100%">
        <?php
          $pattern1 = 'bayer';
          $pattern2 = 'b.a.y.e.r';
          $text = $client->name;
          $isBayerClient = false;
          $index1 = strpos(strtolower($text), $pattern1);
          $index2 = strpos(strtolower($text), $pattern2);
          if ($index1 !== false || $index2 !== false) {
            $isBayerClient = true;
          }

          $isSanCristobalClient = false;
          $pattern1 = 'msc';
          $pattern2 = 'san cristobal';
          $pattern3 = 'san cristóbal';
          $pattern4 = 'm.s.c';
          $text = $client->name;

          $index1 = strpos(strtolower($text), $pattern1);
          $index2 = strpos(strtolower($text), $pattern2);
          $index3 = strpos(strtolower($text), $pattern3);
          $index4 = strpos(strtolower($text), $pattern4);

          if ($index1 !== false || $index2 !== false || $index3 !== false || $index4 !== false) {
            $isSanCristobalClient = true;
          }
        ?>
        @if($isBayerClient)
          <img src="{{asset('assets/img/bulletin/logo.png')}}" style="margin-left:20px;" >
          <img src="{{asset('assets/img/bulletin/bayer.png')}}" style="float:right;margin-right:20px;" align="right">
        @else
          <center><img src="{{asset('assets/img/bulletin/logo.png')}}"></center>
        @endif
          <div class="bulletin-title" style="text-align:center;font-size:22px;color:#0082a4;font-weight:bold;padding-bottom:10px;padding-top:15px;border-bottom:3px solid #c10a28;font-family:Helvetica, sans-serif;">
            Reporte
            <br>
            <span style="font-size: 28px; font-family:Helvetica, sans-serif">{{Form::literalDate($date)}}</span>
            <br>
            <span style="font-size: 30px; font-family:Helvetica, sans-serif">{{$client->name}}</span>
          </div>
          <div style="border-bottom: 3px solid #1E1E9B; font-size:3px;">&nbsp;</div>
          <div style="border-bottom: 6px solid #0082a4; font-size:3px;">&nbsp;</div>
        </td>
      </tr>
    </table>
    <table border="0">
      <tr>
        <td  cellpadding="0" style="padding-top:0px;padding-right:20px;padding-bottom:0px;padding-left:20px;margin-top:27px;">
          <div style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box; font-family: Helvetica, sans-serif;color: #404040;">
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
                    <h2 style="font-size: 26px;font-weight: bolder;color: #404040;">
                    @if ($isSanCristobalClient && strcmp($s->subtitle, 'COYUNTURA') === 0)
                      Noticias C
                    @else
                      {{$s->subtitle}}
                    @endif
                    </h2>
                    <?php $displayed = true; ?>
                  @endif
                  <h2 class="null" style="margin: 0;padding: 0;display: block;font-family: Helvetica;font-size: 26px;font-style: normal;font-weight: bold;line-height: 125%;letter-spacing: -.75px;text-align: left;color: #404040 !important;">
                  {{$item->title}}
                  </h2>
                  <h3 style="margin: 0;padding: 0;display: block;font-family: Helvetica;font-style: normal;font-weight: normal;line-height: 125%;letter-spacing: -.75px;text-align: left;color: #404040 !important;">{{$item->media->name}}</h3>
                  <p style="margin: 1em 0;padding: 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #606060;font-family: Helvetica;font-size: 15px;line-height: 150%;text-align: left;">
                  @if($firstPicture)
                    <a href="{{asset('uploads/' . $firstPicture->file_name)}}" target="_blank">
                      <img  src="{{asset('uploads/' . $firstPicture->file_name)}}" style="width: 300px;height: 155px;margin: 5px;border: 0;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;float:left;padding-right:10px;" align="left" height="155" width="300" padding="10">
                    </a>
                  @endif
                  {{{$item->description}}}
                  </p>
                  @if($item->web)
                    <a href="{{$item->web}}" target="_blank" style="color:#0082a4;font-family:Helvetica;sans-serif;font-size:14px">
                      <img style="width: 25px; height: 25px;" src="{{asset('assets/img/bulletin/url.png')}}" height="25" width="25" >
                      Ver Nota Completa
                    </a>
                    <br>
                  @endif
                  @if (count($item->news->urls) > 0)
                    <span style="font-size: 14px;font-weight: bolder;color:#606060">
                      Otros medios
                    </span>
                    <br>
                    @foreach($item->news->urls as $_url)
                      <a href="{{$_url->url}}" target="_blank" style="color:#0082a4;font-family:Helvetica;sans-serif;font-size:12px">
                      @if (strlen($_url->url) > 50)
                        {{substr($_url->url, 0, 50)}}...
                      @else
                        {{$_url->url}}
                      @endif
                      </a><br>
                    @endforeach
                  @endif
                  @if($firstPdf)
                    <a href="{{asset('uploads/' . $firstPdf->file_name)}}" target="_blank" style="color:#0082a4;font-family:Helvetica;sans-serif;font-size:15px">
                      <img style="width: 25px; height: 25px;" src="{{asset('assets/img/bulletin/pdf.jpeg')}}" height="25" width="25">
                      Ver PDF
                    </a>
                    <br>
                  @endif
                  <br>
                @endif
              @endforeach
            @endforeach
          </div>
        </td>
      </tr>
    </table>
  <table border="0" style="background-color: #ffffff;width:100%;font-family: Helvetica, sans-serif;">
    <tr>
      <td width="100%" cellpadding="0" style="padding-top: 15px;padding-bottom: 15px;background-color: #ffffff;font-size: 18px;color: #858585;text-align: center;font-family: Helvetica, sans-serif;">
        <div style="border-bottom: 3px solid #c10a28;padding-bottom:8px;">
          <span style="font-size: 21px;font-family: Helvetica, sans-serif;">
            MONITOREO PRENSA
          </span>
          <br>
          <span style="font-size: 21px;font-family: Helvetica, sans-serif;">
            <b>EXTEND COMUNICACIONES BOLIVIA</b>
          </span>
        </div>
        <div style="margin-top: 0px; margin-bottom: 15px;border-bottom: 6px solid #548aae; font-size:3px;">&nbsp;</div>
        <i>
          <center style="font-size: 16px;">
            Calacoto, Calle 18 N° 8022 Edificio "Parque 18" Piso 2 Of. 2C. Telf. (591-2) 2774373 - <b>La Paz</b>
          </center>
          <center style="font-size: 16px;">
            Calle Saavedra esq. Cochabamba, Torre Empresarial CAINCO, piso 14 Of. 4 Telf. (591-3) 3111236 - <b>Santa Cruz</b>
          </center>
          <center style="font-size: 16px;">
            <b>www.extend.com.bo</b>
          </center>
          <center style="font-size: 16px;">
            <b>monitoreo.prensa@extend.com.bo</b>
          </center>
        </i>
        <div style="float:right">
          <span style="font-size: 14px;font-family: Helvetica, sans-serif;">
            <b>Siguenos en:</b>
            &nbsp;
            <a href="#">
              <img src="{{asset('assets/img/bulletin/twitter.png')}}" style="width:18px;height:18px" width="18" height="18"></a>&nbsp;
            <a href="#">
              <img src="{{asset('assets/img/bulletin/face.png')}}" style="width:18px;height:18px" width="18" height="18"></a>&nbsp;
            <a href="#">
              <img src="{{asset('assets/img/bulletin/instagram.png')}}" style="width:18px;height:18px" width="18" height="18"></a>&nbsp;
            <a href="#">
              <img src="{{asset('assets/img/bulletin/linkedin.png')}}" style="width:18px;height:18px" width="18" height="18"></a>&nbsp;
            <a href="#">
              <img src="{{asset('assets/img/bulletin/gplus.png')}}" style="width:18px;height:18px" width="18" height="18"></a>&nbsp;
            <a href="#">
              <img src="{{asset('assets/img/bulletin/snapchat.png')}}" style="width:18px;height:18px" width="18" height="18"></a>&nbsp;
          </span>
        </div>
      </td>
    </tr>
  </table>
</td>
<td width="22%"></td>
</tr>
    </table>
</body>
</html>
