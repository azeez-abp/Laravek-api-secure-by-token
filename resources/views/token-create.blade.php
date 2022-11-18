<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Token geneate board') }}
        </h2>
    </x-slot>







    <div class="mt-6">
        <form method="POST" action="{{ route('createToken_') }}">
            @csrf

            <div class="shadow bg-white py-10 sm:overflow-hidden sm:rounded-md" style="padding: 3em;">
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                <div class="space-y-6  px-4 py-5 sm:p-6">
                    <x-input-label for="name" :value="__('Token name')" />
                    <div class="grid  gap-6">

                        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="text" name="name" required />

                    </div>

                    <div class="grid  gap-6">
                        <x-primary-button class="ml-4" style="width: 20%;">

                            {{__('Generate')}}
                        </x-primary-button>
                    </div>

                </div>
            </div>
        </form>
    </div>


</x-app-layout>