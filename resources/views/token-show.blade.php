<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Generated token') }} <span class="msg text-red-600"></span>
        </h2>
    </x-slot>


    <div class="flex  justify-center content-center" style="position: absolute;
    left: 50%;
    right: 50%;
    top: 63%;">

        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">

            <span class="token"> {{$tokens}} </span>

            <span class="cp" title="copy cur">
                <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
            </span>
        </h2>

    </div>
    <p>Copy the code and save it. You can't see this again</p>
    <a href="/dashboard">dashboard</a>
</x-app-layout>
<script>
    document.querySelector('.cp').addEventListener('click', function() {
        let tk = document.querySelector(".token");
        navigator.clipboard.writeText(tk.innerText)
        document.querySelector(".msg").innerHTML = `Text Copied`
        setTimeout(() => {
            document.querySelector(".msg").innerHTML = ``
        }, 3000)
    })
</script>