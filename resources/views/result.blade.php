<x-layout>
    @foreach ($jobs as $job)
        <x-job-card-wide :$job/>
    @endforeach
</x-layout>