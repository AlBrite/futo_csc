@php
    $classes = \App\Models\AcademicSet::with(['students', 'advisor'])->get();
    // dd($classes);
@endphp
<x-template nav="classes" title="Admin - Classes">
    <x-wrapper>
      <div class="flex justify-end">
        <a href="{{route('admin.add-class')}}" class="btn btn-white">Create New Class</a>
      </div>
        <div class="responsive-table no-zebra">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Top Students</th>
                        <th>Advisor</th>
                        <th>Total Students</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($classes as $class)
                        <tr>
                            <td class="font-[500]">
                                {{ $class->name }}
                            </td>
                            <td>
                                <div class="py-0.5 justify-center flex -space-x-2 overflow-hidden">
                                    @foreach ($class->students as $n => $student)
                                        <img class="hover:z-10 inline-block h-6 w-6 object-cover rounded-full ring-2 ring-white"
                                            src="{{ $student->user->picture() }}" alt="{{ $student->user->name }}" />
                                        @php
                                            if ($n == 2) {
                                                break;
                                            }
                                        @endphp
                                    @endforeach
                                </div>

                            </td>
                            <td>{{ $class->advisor->user->name }}</td>
                            <td>{{ $class->students()->count() }}</td>
                        </tr>
                    @endforeach
                </tbody>
        </div>
    </x-wrapper>

</x-template>
