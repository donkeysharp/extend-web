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
    <table border="0" style="background-color: #e2e2e2; width: 100%">
      <tr>
        <td width="100%">
          <center><img src="{{asset('assets/img/bulletin/logo.png')}}" /></center>
          <div class="bulletin-title" style="text-align: center;font-size: 26px;color: #0082a4;font-weight: bold;padding-bottom: 30px;padding-top: 15px;border-bottom: 3px solid #0082a4;font-family: Helvetica, sans-serif;">
            Reporte {{Form::literalDate($date)}} - {{$client->name}}
          </div>
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
                    <span style="font-size: 26px;font-weight: bolder;color: #404040;">{{$s->subtitle}}</span>
                    <?php $displayed = true; ?>
                    <br>
                  @endif
                  <span class="title" style="font-size: 26px;font-weight: bolder;color: #404040;">
                    {{$item->title}}
                  </span>
                  <p style="font-size: 15px;text-align: justify;color: #404040;">
                    @if($firstPicture)
                      <img  src="{{asset('uploads/' . $firstPicture->file_name)}}" class="picture" style="float: left;margin-right: 10px;margin-bottom: 10px;width: 60%;" />
                    @endif
                    {{{$item->description}}}
                  </p>
                  <br>
                  @if($item->web)
                    <a href="{{$item->web}}" target="_blank" style="color:#0082a4;font-family:Helvetica;sans-serif;font-size:15px">
                      <img style="width: 25px; height: 25px;" src="{{asset('assets/img/bulletin/url.png')}}" height="25" width="25" >
                      Ver Nota Completa
                    </a>
                    <br>
                  @endif
                  @if($firstPdf)
                    <a href="{{asset('uploads/' . $firstPdf->file_name)}}" target="_blank" style="color:#0082a4;font-family:Helvetica;sans-serif;font-size:15px">
                      <img style="width: 25px; height: 25px;" src="{{asset('assets/img/bulletin/pdf.jpeg')}}" height="25" width="25">
                      Ver PDF
                    </a>
                    <br>
                  @endif
                  <br>
                  <br>
                @endif
              @endforeach
            @endforeach
          </div>
        </td>
      </tr>
    </table>
  <table border="0" style="background-color: #e2e2e2;width:100%;font-family: Helvetica, sans-serif;">
    <tr>
      <td width="100%" style="padding-top: 15px;padding-bottom: 15px;background-color: #e2e2e2;font-size: 18px;color: #858585;text-align: center;font-family: Helvetica, sans-serif;">
        <span style="font-size: 21px;font-family: Helvetica, sans-serif;">MONITOREO PRENSA <b>EXTEND COMUNICACIONES BOLIVIA</b></span>
        <div style="margin-top: 5px; margin-bottom: 15px;border-bottom: 6px solid #548aae; font-size:3px;">&nbsp;</div>
        <i>
          <center><b>Contácenos: </b>Calacoto, Calle 18 N° 8022 Edificio Parque 18 Piso 2 Of. 2C</center>
          <center><b>Teléfonos: </b>(591-2) 2774373 - 2797733</center>
          <center><b>monitoreo.prensa@extend.com</b></center>
        </i>
      </td>
    </tr>
  </table>
</td>
<td width="22%"></td>
</tr>
    </table>
</body>
</html>
