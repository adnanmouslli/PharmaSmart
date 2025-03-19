@if ($errors->any())
    <div class="bg-red-50 p-4 rounded-lg mb-6">
        <div class="flex">
            <div class="shrink-0">
                <i class="fas fa-exclamation-circle text-red-400"></i>
            </div>
            <div class="mr-3">
                <h3 class="text-sm font-medium text-red-800">
                    يوجد {{ $errors->count() }} أخطاء في النموذج
                </h3>
                <div class="mt-2 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif