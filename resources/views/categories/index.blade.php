@extends('app')

@section('content')

<div class="list-group panel">
    @if($categories)
        @foreach($categories as $item)
            {!! Helper::renderNode($item) !!}
        @endforeach
    @else
        <h1>Node not found!</h1>
    @endif
</div>

@endsection
