<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <h1 class="text-align:center">Departments Data</h1>
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="p-4">
                    ID
                </th>
                <th scope="col" class="px-6 py-3">
                    Department
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse($departments as $department)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $department->id }}
                </th>
                <td class="px-6 py-4">
                    {{ $department->name }}
                </td>
            </tr>
            @empty
            <tr>
                <td class="text-center p-5 text-black" colspan="3">No data found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>