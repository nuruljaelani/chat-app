<div class="fixed hidden inset-0 overflow-y-auto" id="{{ $modalId }}">
    <!-- The whole future lies in uncertainty: live immediately. - Seneca -->
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-gray-700/50 inset-0 fixed transition-opacity" id="close"></div>
        <div
            class="inline-block p-4 align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle w-full md:max-w-md">
            <form id="form-profile" action="{{route('user.update')}}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-8">
                <input type="text" name="name" id="name" class="bg-white rounded-md p-2 focus:outline-none ring-1" />
                <input type="file" name="avatar" id="avatar" class="bg-white rounded-md p-2 focus:outline-none ring-1" />
                <button type="submit" class="bg-orange-200 text-orange-800 font-medium rounded-lg p-2" id="btn-update">
                    Save
                </button>
            </form>
        </div>
    </div>
</div>
