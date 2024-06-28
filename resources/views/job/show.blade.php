<x-layout>
        <div class="flex flex-col items-center gap-10 mb-10">
                <x-employer-logo :employer="$job->employer"/>
                <h1>{{$job->employer->name}}</h1>
                <x-forms.form method="GET" action="/jobs/{{$job->id}}/edit">
                @can('edit', $job)
                    <button class="self-end">Edit</button>
                @endcan
                </x-forms.form>
        </div>
        <div class="flex flex-col pl-[100px] gap-10 mb-10">
                <p><strong class="pr-5">Job title :  </strong> {{$job->title}}</p>
                <p><strong class="pr-5">Job salary :  </strong>  {{$job->salary}}</p>
                <p><strong class="pr-5">Job location :  </strong> {{$job->location}}</p>
                <p><strong class="pr-5">Job type :  </strong> {{$job->schedule}}</p>
                <p><strong class="pr-5">Job url :  </strong> <a href={{$job->url}}> {{$job->employer->name}}</a></p>
                <p><strong class="pr-5">Job description :  </strong> <br>{{$job->discription}}</p>
        </div>
</x-layout>