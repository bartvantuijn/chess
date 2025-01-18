<x-filament-widgets::widget wire:ignore>
    <x-filament::section>
        <div class="grid gap-4">
            <div class="relative rounded-full bg-gray-200 dark:bg-gray-700">
                <small id="advantageWhite" class="text-xs text-gray-100 absolute start-0 px-2 hidden">0</small>
                <small id="advantageBlack" class="text-xs text-gray-400 absolute end-0 px-2 hidden">0</small>
                <div class="max-w-full bg-primary-500 h-4 rounded-full transition-all duration-500" id="advantageBar" style="width:50%;"></div>
            </div>
            <small id="pgn" class="text-xs text-gray-400 whitespace-nowrap overflow-x-auto">1.</small>
            <div id="board"
                 x-data="{}"
                 x-load-js="[
                    @js(FilamentAsset::getScriptSrc('main'))
                 ]"
            >
            </div>
            <div class="grid grid-flow-col gap-4">
                <x-filament::button id="undoBtn" icon="heroicon-o-chevron-left" size="xs" color="gray" outlined></x-filament::button>
                <x-filament::button id="compVsCompBtn" icon="heroicon-o-cpu-chip" size="xs" color="gray" outlined></x-filament::button>
                <x-filament::button id="resetBtn" icon="heroicon-o-arrow-path" size="xs" color="gray" outlined></x-filament::button>
                <x-filament::button id="redoBtn" icon="heroicon-o-chevron-right" size="xs" color="gray" outlined></x-filament::button>
            </div>
            <small id="status" class="text-center text-gray-400">No check, checkmate, or draw.</small>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
