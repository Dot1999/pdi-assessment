<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <h1 class="text-align:center">Activity Log Data</h1>
    <table class="w-full text-sm text-left text-gray-500 light:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 light:bg-gray-700 light:text-gray-400">
            <tr>
                <th scope="col" class="p-4">
                    ID
                </th>
                <th scope="col" class="px-6 py-3">
                    Event
                </th>
                <th scope="col" class="px-6 py-3">
                    User
                </th>
                <th scope="col" class="px-6 py-3">
                    Old Data
                </th>
                <th scope="col" class="px-6 py-3">
                    New Data
                </th>
                <th scope="col" class="px-6 py-3">
                    Date & Time
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr class="bg-white border-b light:bg-gray-800 light:border-gray-700 hover:bg-gray-50 light:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap light:text-white">
                    {{ $log->id }}
                </th>
                <td class="px-6 py-4">
                    {{ strtoupper($log->event) }}
                </td>
                <td class="px-6 py-4">
                    {{ @$log->causer->name ?: "SYSTEM" }}
                </td>
                @php
                $log_props = json_decode($log->properties)
                @endphp
                <td class="px-6 py-4">
                    @json(@$log_props->attributes ?:"")
                </td>
                <td class="px-6 py-4">
                    @json(@$log_props->old ?: "")
                </td>
                <td class="px-6 py-4">
                    {{\Carbon\Carbon::parse($log->created_at)->format('F j, Y h:ia')}}
                </td>
            </tr>
            @empty
            <tr>
                <td class="text-center p-5 text-black" colspan="6">No data found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>