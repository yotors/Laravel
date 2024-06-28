<x-layout>
    <x-page-heading>Edit Job</x-page-heading> 
    <form action="/jobs/{{$job->id}}" method="POST" class="pl-[800px]">
        @csrf
        @method('DELETE')
        <x-forms.button>Delete</x-forms.button>
    </form>
    <form method="POST" action="/jobs/{{$job->id}}" class="max-w-2xl mx-auto space-y-6 mb-10">
        @csrf
        @method('PATCH')
        <x-forms.input label="Title" name="title" placeholder="CEO" />
        <x-forms.input label="Salary" name="salary" placeholder="$90,000 USD" />
        <x-forms.input label="Location" name="location" placeholder="Winter Park, Florida" />

        <x-forms.select label="Schedule" name="schedule">
            <option>Part Time</option>
            <option>Full Time</option>
        </x-forms.select>

        <x-forms.input label="URL" name="url" placeholder="https://acme.com/jobs/ceo-wanted" />
        <x-forms.checkbox label="Feature (Costs Extra)" name="featured" />

        <x-forms.divider />

        <x-forms.input label="Tags (comma separated)" name="tags" placeholder="video, education" />

        <x-forms.input label="Job Discription" name="discription" placeholder="This job is ..."/>
        @can('edit', $job)
           <x-forms.button>Update</x-forms.button> 
        @endcan
        
    </form>
</x-layout>