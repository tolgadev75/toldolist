@props(['type' => 'success', 'message'])

<div {{ $attributes->merge(['class' => 'fixed top-5 right-5 z-50 w-80 transform transition-all duration-300 ease-in-out translate-x-0 opacity-100']) }}>
    <div class="p-4 rounded shadow-md @if($type === 'success') bg-green-100 border-l-4 border-green-500 text-green-700 @else bg-red-100 border-l-4 border-red-500 text-red-700 @endif">
        <div class="flex items-center">
            @if($type === 'success')
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            @endif
            <span>{{ $message }}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-{{ $type === 'success' ? 'green' : 'red' }}-500 hover:text-{{ $type === 'success' ? 'green' : 'red' }}-700 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</div>
