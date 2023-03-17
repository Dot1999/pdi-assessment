<div class="relative w-full h-full max-w-2xl md:h-auto">
    <form action="{{route('announcement.store')}}" method="post">
        @csrf
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow light:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-start justify-between p-4 border-b rounded-t light:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 light:text-white">
                    Add Announcement
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center light:hover:bg-gray-600 light:hover:text-white" data-modal-hide="common-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-6 space-y-6">
                <div class="mb-6">
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Title</label>
                    <input type="text" name="title" id="title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-blue-500 light:focus:border-blue-500" required>
                </div>
                <div class="mb-6">
                    <label for="content" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Content</label>
                    <textarea id="content" name="content" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-blue-500 light:focus:border-blue-500" required placeholder="Write your announcement here..."></textarea>
                </div>
                <div class="mb-6">
                    <h6>Departments</h6>
                    <div class="flex" id="department-checkboxes">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex items-center mr-4">
                                <input id="checkbox-all" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 light:focus:ring-blue-600 light:ring-offset-gray-800 focus:ring-2 light:bg-gray-700 light:border-gray-600">
                                <label for="checkbox-all" class="uppercase ml-2 text-sm font-medium text-gray-900 light:text-gray-300">All</label>
                            </div>
                            @foreach ($departments as $department)
                            <div class="flex items-center mr-4">
                                <input name="department_id[]" id="checkbox-{{$department->name}}" type="checkbox" value="{{$department->id}}" class="department-check w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 light:focus:ring-blue-600 light:ring-offset-gray-800 focus:ring-2 light:bg-gray-700 light:border-gray-600">
                                <label for="checkbox-{{$department->name}}" class="uppercase ml-2 text-sm font-medium text-gray-900 light:text-gray-300">{{$department->name}}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b light:border-gray-600">
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 text-sm focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-lg px-6 py-2 text-center light:bg-blue-600 light:hover:bg-blue-700 light:focus:ring-blue-800">Post</button>
                <button data-modal-hide="common-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 light:bg-gray-700 light:text-gray-300 light:border-gray-500 light:hover:text-white light:hover:bg-gray-600 light:focus:ring-gray-600">Cancel</button>
            </div>
        </div>
    </form>
</div>