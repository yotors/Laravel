@props(['size'=>'base', 'tag'])
@php
$class = "bg-white/10 hover:bg-white/25 rounded-xl transition-colors py-1 duration-300";
if ($size === 'base'){
    $class.=" text-sm px-5 ";
}
else {
    $class.=" text-xs px-3";
}
@endphp
<a href="/tags/{{ $tag->name}}" class="{{$class}}">{{  ucwords($tag->name)  }}</a>