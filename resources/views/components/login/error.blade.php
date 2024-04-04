<div class="rounded-md bg-red-50/80 p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <x-icon name="far-circle-xmark" class='w-8 text-red-400' />
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-red-800">Login Failed</h3>
            <div class="mt-2 text-sm text-red-700">
                {{ $message }}
            </div>
        </div>
    </div>
</div>
