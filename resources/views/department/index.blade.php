<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Departments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="flex justify-between p-3">
                    @if (auth()->user()->canAccess('department-create'))
                    <button data-modal-backdrop="static" data-modal-url="{{route('department.create')}}" type="button" class="text-white bg-blue-700 hover:bg-blue-800 text-lg focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-lg px-6 py-2 text-center light:bg-blue-600 light:hover:bg-blue-700 light:focus:ring-blue-800">Add Department</button>
                    @endif
                    <div class="flex align-center">
                        <button onclick="print('{{route('department.print')}}/?search_query={{request('search')}}')" type="button" class="text-white bg-blue-700 hover:bg-blue-800 text-lg focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-lg px-6 py-2 text-center light:bg-blue-600 light:hover:bg-blue-700 light:focus:ring-blue-800">Print</button>
                        @if (auth()->user()->canAccess('department-export'))
                        <form action="{{route('department.export')}}/?search_query={{request('search')}}" method="POST" class="flex px-2">
                            @csrf
                            <div class="flex">
                                <label for="states" class="sr-only">Choose a state</label>
                                <select name="format" id="states" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg border-l-gray-100 light:border-l-gray-700 border-l-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-blue-500 light:focus:border-blue-500">
                                    {{-- <option selected value="none">File format</option> --}}
                                    <option selected value="CSV">CSV</option>
                                    <option value="XLSX">EXCEL</option>
                                    <option value="PDF">PDF</option>
                                    <option value="TXT">TXT</option>
                                </select>
                                <button id="states-button" data-dropdown-toggle="dropdown-states" class="flex-shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-gray-500 bg-gray-100 border border-gray-300 rounded-r-lg hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 light:bg-gray-700 light:hover:bg-gray-600 light:focus:ring-gray-700 light:text-white light:border-gray-600" type="submit">
                                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 4.5l15 15m0 0V8.25m0 11.25H8.25"></path>
                                    </svg>
                                    Download
                                </button>
                            </div>
                        </form>
                        @endif
                        <form>
                            <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only light:text-white">Search</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 light:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="search" value="{{request('search')}}" name="search" id="default-search" style="padding-right: 6rem;" class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-blue-500 light:focus:border-blue-500" placeholder="Search">
                                <button type="submit" class="text-white absolute right-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 light:bg-blue-600 light:hover:bg-blue-700 light:focus:ring-blue-800">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <div class="flex justify-between p-3"></div>
                    <table class="w-full text-sm text-left text-gray-500 light:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 light:bg-gray-700 light:text-gray-400">
                            <tr>
                                <th scope="col" class="p-4">
                                    ID
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Department
                                </th>
                                @if (auth()->user()->canAccess('department-update') || auth()->user()->canAccess('department-delete'))
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($departments as $department)
                            <tr class="bg-white border-b light:bg-gray-800 light:border-gray-700 hover:bg-gray-50 light:hover:bg-gray-600">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap light:text-white">
                                    {{ $department->id }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $department->name }}
                                </td>
                                @if (auth()->user()->canAccess('department-update') || auth()->user()->canAccess('department-delete'))
                                <td class="flex items-center px-6 py-4 space-x-3">
                                    @if (auth()->user()->canAccess('department-update'))<button type="button" data-modal-url="{{route('department.edit', ['department' => $department->id])}}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-full text-sm px-5 py-2.5 mr-2 mb-2 light:bg-gray-800 light:text-white light:border-gray-600 light:hover:bg-gray-700 light:hover:border-gray-600 light:focus:ring-gray-700">Edit</button>@endif
                                    @if (auth()->user()->canAccess('department-delete'))<button type="button" data-modal-url="{{route('department.delete-modal', ['id' => $department->id])}}" class="text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 light:bg-red-600 light:hover:bg-red-700 light:focus:ring-red-900">Delete</button>@endif
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td class="text-center p-5 text-black" colspan="{{(auth()->user()->canAccess('department-update') || auth()->user()->canAccess('department-delete')) ? 3 : 2}}">No data found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $departments->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-app-layout>