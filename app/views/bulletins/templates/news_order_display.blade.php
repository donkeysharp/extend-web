<?php $generalIndex = Session::get('generalIndex'); ?>
@foreach($subtitles as $s)
  <?php $displayed = false; $ii = 0; $color = getRandomColor(); ?>
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
        <ul class="sortable-list">
      @endif
      <li data-index="{{$generalIndex}}" data-id="{{$item->id}}" style="border-left-color:{{$color}}">{{$item->title}}</li>
      <?php $ii++; $generalIndex++; ?>
    @endif
  @endforeach
  @if($displayed)
    </ul>
    <?php Session::put('generalIndex', $generalIndex); ?>
  @endif
@endforeach
