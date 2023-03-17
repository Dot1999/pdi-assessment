<div class="relative w-full h-full max-w-2xl md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow light:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-start justify-between p-4 border-b rounded-t light:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 light:text-white">
                    {{$user->name}} Permissions
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
                    @foreach($user->formatted_permissions as $module => $module_permissions)
                    <h6><strong style="text-transform: capitalize;">{{$module}}</strong></h6>
                    <div class="flex">
                        @foreach($module_permissions as $permission)
                        <div class="flex items-center mr-4">
                            <label for="{{$module}}-checkbox-{{$permission}}" class="uppercase ml-2 text-sm font-medium text-gray-900 light:text-gray-300">{{$permission}}</label>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b light:border-gray-600">
                <button data-modal-hide="common-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 light:bg-gray-700 light:text-gray-300 light:border-gray-500 light:hover:text-white light:hover:bg-gray-600 light:focus:ring-gray-600">Close</button>
            </div>
        </div>
</div>