@extends('master')

@section('content')
<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <div class="panel panel-dark">
      <div class="panel-heading"><b>Orden de las noticias</b></div>
      <div class="panel-body">
        <input id="bulletinId" type="hidden" value="{{$bulletinId}}" />
        <?php
          function getRandomColor() {
            $color = 'rgb(';
            $color .= rand(0, 256) . ',';
            $color .= rand(0, 256) . ',';
            $color .= rand(0, 256) . ')';

            return $color;
          }

          function canDisplayNews($clientId, $displayCoyuntura) {
            if ($displayCoyuntura) {
              if ($clientId == 100) {
                return true;
              }
            } else {
              if ($clientId != 100) {
                return true;
              }
            }
            return false;
          }
          /* All this php block contains client exceptions
           * for bulletin header, certain subtitles.
           * This will tend to be buggy. TODO: find a solution
           */
          // Bayer client exception
          $pattern1 = 'bayer';
          $pattern2 = 'b.a.y.e.r';
          $text = $client->name;
          $isBayerClient = false;
          $index1 = strpos(strtolower($text), $pattern1);
          $index2 = strpos(strtolower($text), $pattern2);
          if ($index1 !== false || $index2 !== false) {
            $isBayerClient = true;
          }

          // Minera San Cristobal client exception
          $pattern1 = 'msc';
          $pattern2 = 'san cristobal';
          $pattern3 = 'san cristóbal';
          $pattern4 = 'm.s.c';
          $text = $client->name;

          $index1 = strpos(strtolower($text), $pattern1);
          $index2 = strpos(strtolower($text), $pattern2);
          $index3 = strpos(strtolower($text), $pattern3);
          $index4 = strpos(strtolower($text), $pattern4);

          $isSanCristobalClient = $index1 !== false || $index2 !== false || $index3 !== false || $index4 !== false;
          $hasCoyunturaNews = false;
          foreach ($details as $item) {
            if ($item->news->client_id == 100) {
              $hasCoyunturaNews = true;
              break;
            }
          }
          Session::put('generalIndex', 0);
        ?>
        @include('bulletins.templates.news_order_display', [
          'subtitles' => $subtitles,
          'details' => $details,
          'display_coyuntura' => false,
        ])
        @if ($hasCoyunturaNews)
          <h2 style="font-size: 26px;font-weight: bolder;color: #404040;margin-top:20px;margin-bottom:20px">
            @if ($isSanCristobalClient)
              NOTICIAS C
            @else
              COYUNTURA
            @endif
          </h2>
          @include('bulletins.templates.news_order_display', [
            'subtitles' => $subtitles,
            'details' => $details,
            'display_coyuntura' => true,
          ])
        @endif
        <br>
        <button class="btn btn-success" id="btn-save">
          <i class="fa fa-save"></i>
          Guardar Cambios
        </button>
        &nbsp;&nbsp;
        <a href="{{url('dashboard/bulletins')}}" title="Volver">
          Volver
        </a>
      </div>
    </div>
  </div>
</div>
@stop

@section('scripts')
<script src="{{asset('assets/vendors/js/sortable.min.js')}}"></script>
<script src="{{asset('assets/vendors/js/react.min.js')}}"></script>
<script src="{{asset('assets/js/build.min.js')}}"></script>
<script type="text/javascript">
  function getElements() {
    var result = [];
    var index = 0;
    $('.sortable-list').each(function(index, el) {
      $(el).find('li').each(function(index2, item) {
        console.log(item.dataset.index, item.dataset.id);
        result.push({
          news_order: index++,
          news_id: item.dataset.id
        });
      });
    });
    return result;
  }

  $(document).ready(function() {
    var $http = MyApp.$http;

    $('.sortable-list').each(function(index, el) {
      var sortable = Sortable.create(el, { animation: 150, scroll: true });
    });

    $('#btn-save').on('click', function(e) {
      var newsOrderList = getElements();
      var data = {
        details: newsOrderList
      };
      var bulletinId = document.getElementById('bulletinId').value;
      $http.post('/bulletins/' + bulletinId + '/order', data).then(function(res) {
        window.location = '/dashboard/bulletins';
      });
    });

  });
</script>
@stop
