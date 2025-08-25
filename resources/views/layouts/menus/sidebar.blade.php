<aside id="default-sidebar"
    class="fixed top-[60px] left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
    aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-gray-50 dark:bg-gray-800">
        <ul class="space-y-2 font-medium">

            @if (auth()->user()->role == 'admin')
                <li>
                    <a href="{{ route('users.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                            <path
                                d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Users</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('packages.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 18 20">
                            <path
                                d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Packages</span>
                    </a>
                </li>
                <li>
                <li>
                    <a href="{{ route('class.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M256 368a16 16 0 0 1-7.94-2.11L108 285.84a8 8 0 0 0-12 6.94V368a16 16 0 0 0 8.23 14l144 80a16 16 0 0 0 15.54 0l144-80a16 16 0 0 0 8.23-14v-75.22a8 8 0 0 0-12-6.94l-140.06 80.05A16 16 0 0 1 256 368" />
                            <path fill="currentColor"
                                d="M495.92 190.5v-.11a16 16 0 0 0-8-12.28l-224-128a16 16 0 0 0-15.88 0l-224 128a16 16 0 0 0 0 27.78l224 128a16 16 0 0 0 15.88 0L461 221.28a2 2 0 0 1 3 1.74v144.53c0 8.61 6.62 16 15.23 16.43A16 16 0 0 0 496 368V192a15 15 0 0 0-.08-1.5" />
                        </svg>

                        <span class="flex-1 ms-3 whitespace-nowrap">Class</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('transactions.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 22 21">
                            <path
                                d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                            <path
                                d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                        </svg>
                        <span class="ms-3">Transactions</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('transactions-class.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 14 14">
                            <path fill="currentColor" fill-rule="evenodd"
                                d="M12.402 8.976H7.259a2.278 2.278 0 0 0-.193-4.547h-1.68A3.095 3.095 0 0 0 4.609 0h7.793a1.35 1.35 0 0 1 1.348 1.35v6.279c0 .744-.604 1.348-1.348 1.348ZM2.898 4.431a1.848 1.848 0 1 0 0-3.695a1.848 1.848 0 0 0 0 3.695m5.195 2.276c0-.568-.46-1.028-1.027-1.028H2.899a2.65 2.65 0 0 0-2.65 2.65v1.205c0 .532.432.963.964.963h.172l.282 2.61A1 1 0 0 0 2.66 14h.502a1 1 0 0 0 .99-.862l.753-5.404h2.16c.567 0 1.027-.46 1.027-1.027Z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="ms-3">Class Transactions</span>
                    </a>
                </li>
            @else
                <li>
                    <a href="{{ route('users.transactions.index', ['userId' => auth()?->user()?->id]) }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 20 18">
                            <path
                                d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">My Account Users</span>
                    </a>
                </li>
            @endif
            </li>
        </ul>
    </div>
</aside>
