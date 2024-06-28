@props(['x'])
<div class="flex flex-col text-center bg-white/5 rounded-xl border border-transparent hover:border-blue-800 group transtiion duration-300 p-5">
<div class="self-start text-sm">
    {{$x->employer->name}}
</div>
<div class="py-8">
    <h3 class=" group-hover:text-blue-500 text-xl transtiion duration-300">
        <a href="/jobs/{{$x->id}}">
            {{$x->title}}
        </a>
    </h3>
    <p class="text-sm mt-4">{{$x->schedule}}: From -{{$x->salary}}</p>
</div>
<div class="flex justify-between items-center mt-auto">
    <div>
        @foreach ($x->tags as $tag)
            <x-tag size="small" :$tag />
        @endforeach
        
    </div>
    <x-employer-logo :employer="$x->employer" :width="42"></x-employer-logo>
</div>
</div>