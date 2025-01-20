<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center">
            <div class="flex-1">
                <h2 class="font-semibold text-gray-950 dark:text-white">
                    {{ now()->hour < 12 ? __('Good morning') : (now()->hour < 18 ? __('Good afternoon') : __('Good evening')) }}
                </h2>

                <p class="text-sm">
                    <small class="text-gray-500 dark:text-gray-400">
                        {{ now()->toFormattedDateString() }}
                    </small>
                </p>
            </div>
            <div class="flex-1">
                <p class="text-sm">
                    <h2 class="text-gray-500 dark:text-gray-400 text-end">
                        <span x-data="{ time: new Date().toLocaleTimeString() }" x-init="
                            setInterval(() => {
                                time = new Date().toLocaleTimeString(); // Update elke seconde
                            }, 1000);">
                            <span x-text="time"></span>
                        </span>
                    </h2>
                </p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
