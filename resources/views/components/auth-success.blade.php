@if (session('success'))
    <div class="bg-green-50 p-4 rounded-lg mb-6">
        <div class="flex">
            <div class="shrink-0">
                <i class="fas fa-check-circle text-green-400"></i>
            </div>
            <div class="mr-3">
                <p class="text-sm font-medium text-green-800">
                    {{ session('success') }}
                </p>
            </div>
        </div>
    </div>
@endif