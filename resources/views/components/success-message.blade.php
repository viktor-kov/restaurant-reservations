@session('success')
<div
    class="max-w-7xl mx-auto sm:px-6 lg:px-8"
>
    <div
        class="p-4 bg-green-600 border border-green-800 text-white rounded-lg"
    >
        {{ session('success') }}
    </div>
</div>
@endsession
