@foreach($subtitles as $s)
  <?php $displayed = false; ?>

  @foreach($details as $item)
    <?php
      $firstPicture = null;
      $firstPdf = null;
      $firstNewsFooter = null;
      foreach($item->news->uploads as $upload) {
        $type = strtolower($upload->type);
        if(($type === 'jpg' || $type === 'jpeg' || $type === 'png' || $type === 'gif') && !$firstPicture && !$upload->news_footer) {
          $firstPicture = $upload;
        }
        if(($type === 'pdf') && !$firstPdf) {
          $firstPdf = $upload;
        }
        if ($upload->news_footer && !$firstNewsFooter) {
          $firstNewsFooter = $upload;
        }
      }
    ?>
    @if($item->subtitle === $s->subtitle && canDisplayNews($item->news->client_id, $display_coyuntura))
      @if(!$displayed)
        <h2 style="font-size: 26px;font-weight: bolder;color: #404040;margin-top:20px;margin-bottom:20px">
          {{$s->subtitle}}
        </h2>
        <?php $displayed = true; ?>
      @endif
      @if($item->extra_title)
        <h3 style="margin: 0;padding: 0;display: block;font-family: Helvetica;font-style: normal;font-weight: normal;line-height: 125%;letter-spacing: -.75px;text-align: left;color: #404040 !important;">{{$item->extra_title}}</h3>
      @endif
      <h2 class="null" style="margin: 0;padding: 0;display: block;font-family: Helvetica;font-size: 26px;font-style: normal;font-weight: bold;line-height: 125%;letter-spacing: -.75px;text-align: left;color: #404040 !important;">
      {{$item->title}}
      </h2>
      <h3 style="margin: 0;padding: 0;display: block;font-family: Helvetica;font-style: normal;font-weight: normal;line-height: 125%;letter-spacing: -.75px;text-align: left;color: #404040 !important;">{{$item->media->name}}</h3>
      @if($item->observations)
      <p style="margin: 1em 0;padding: 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #9A9696;font-family: Helvetica;font-size: 15px;line-height: 150%;text-align: left;">
        {{$item->observations}}
      </p>
      @endif
      <p style="margin: 1em 0;padding: 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #606060;font-family: Helvetica;font-size: 15px;line-height: 150%;text-align: left;">
      @if($firstPicture)
        <a href="{{asset('uploads/' . $firstPicture->file_name)}}" target="_blank">
          <img  src="{{asset('uploads/' . $firstPicture->file_name)}}" style="width: 300px;height: 155px;margin: 5px;border: 0;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;float:left;padding-right:10px;" align="left" height="155" width="300" padding="10">
        </a>
      @endif
      {{$item->description}}
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
      @elseif($firstNewsFooter)
        <a href="{{asset('uploads/' . $firstNewsFooter->file_name)}}" target="_blank" style="color:#0082a4;font-family:Helvetica;sans-serif;font-size:15px">
          <img style="width: 25px; height: 25px;" src="{{asset('assets/img/bulletin/screenshot-xl.png')}}" height="25" width="25">
          Ver captura de noticia
        </a>
        <br>
      @endif
      <br>
    @endif
  @endforeach
@endforeach
