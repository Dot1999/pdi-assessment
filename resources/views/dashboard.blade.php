<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if (auth()->user()->role_id == 1)
                <!--  -->
                <div class="grid grid-cols-4 gap-4 pb-6">
                    <div class="bg-white border rounded-lg shadow light:bg-gray-800 light:border-gray-700 p-4 col-span-2 border text-center">
                        <strong>{{ $employees }}
                            EMPLOYEES</strong>
                    </div>
                    <div class="bg-white border rounded-lg shadow light:bg-gray-800 light:border-gray-700 p-4 col-span-2 border text-center">
                        <strong>{{ $departments }}
                            DEPARTMENTS</strong>
                    </div>
                </div>
                @endif
                <!-- Announcements -->
                @foreach($announcements as $announcement)
                <div class="pb-4">
                    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow light:bg-gray-800 light:border-gray-700">
                        <small class="pt-2">{{$announcement->user->name}} | {{\Carbon\Carbon::parse($announcement->created_at)->format("F j, y h:i A")}}</small>
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 light:text-white">{{$announcement->title}}</h5>
                        <hr class="pb-2">
                        <p class="pb-6 mb-3 font-normal text-gray-700 light:text-gray-400">{{$announcement->content}}</p>
                        <small class="pt-2">Departments: {{ implode(", ", $announcement->department->pluck('name')->toArray()) }}</small>
                    </div>
                </div>
                @endforeach
                {{$announcements->links()}}
            </div>
        </div>
    </div>
</x-app-layout>