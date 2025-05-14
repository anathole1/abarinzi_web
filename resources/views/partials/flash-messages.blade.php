@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-700 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
@endif

@if(session('warning'))
    <div class="mb-4 p-4 bg-yellow-100 border border-yellow-300 text-yellow-700 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('warning') }}</span>
    </div>
@endif

@if(session('info'))
    <div class="mb-4 p-4 bg-blue-100 border border-blue-300 text-blue-700 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('info') }}</span>
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded relative" role="alert">
        <strong class="font-bold">Oops! Something went wrong.</strong>
        <ul class="mt-1 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif