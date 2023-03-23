@if(isset($crumbs))
<div class="col-lg-12">
@foreach($crumbs as $crumb)
@if(isset($crumb['text']))
<a @if(isset($crumb['href'])) href="{{ route($crumb['href']) }}" @endif>
{{ $crumb['text'] }}
</a>
@if($crumb != end($crumbs))
 >
@endif
@endif
@endforeach
</div>
@endif
