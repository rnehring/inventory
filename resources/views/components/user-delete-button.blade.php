@props(['userId'])

<button data-userId="{{ $userId }}" type="submit" {{ $attributes->merge(['id', 'name', 'class' => 'text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 py-2 px-2 mr-0 delete-button' ]) }}>
    <svg  data-userId="{{ $userId }}" class="w-4 h-4" fill="white" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path  data-userId="{{ $userId }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M432 32H312l-9.4-18.7A24 24 0 0 0 281.1 0H166.8a23.72 23.72 0 0 0-21.4 13.3L136 32H16A16 16 0 0 0 0 48v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16zM53.2 467a48 48 0 0 0 47.9 45h245.8a48 48 0 0 0 47.9-45L416 128H32z"/></svg>
    <span class="sr-only">Delete User</span>
</button>
