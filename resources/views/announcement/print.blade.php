<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <h1 class="text-align:center">Announcements Data</h1>
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="p-4">
                    ID
                </th>
                <th scope="col" class="px-6 py-3">
                    Title
                </th>
                <th scope="col" class="px-6 py-3">
                    Content
                </th>
                <th scope="col" class="px-6 py-3">
                    Author
                </th>
                <th scope="col" class="px-6 py-3">
                    Department
                </th>
                <th scope="col" class="px-6 py-3">
                    Date posted
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse($announcements as $announcement)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $announcement->id }}
                </th>
                <td class="px-6 py-4">
                    {{ $announcement->title }}
                </td>
                <td class="px-6 py-4">
                    {{ $announcement->content }}
                </td>
                <td class="px-6 py-4">
                    {{ $announcement->user->name }}
                </td>
                <td class="px-6 py-4">
                    {{ implode(", ", $announcement->department->pluck('name')->toArray()) }}
                </td>
                <td class="px-6 py-4">
                    {{\Carbon\Carbon::parse($announcement->created_at)->format("F j, y h:i A")}}
                </td>
            </tr>
            @empty
            <tr>
                <td class="text-center p-5 text-black" colspan="7">No data found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>