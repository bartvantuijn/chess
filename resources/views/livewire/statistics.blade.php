<div>
    <div class="grid grid-flow-col gap-4 items-center">
        <x-filament::badge color="gray">
            Level {{ $user->level }}
        </x-filament::badge>

        <div class="w-20 rounded-full bg-gray-200 dark:bg-gray-700">
            <div class="max-w-full bg-primary-500 h-3 rounded-full transition-all duration-500" style="width:{{ $user->experienceProgress() }}%;"></div>
        </div>

        <small class="text-gray-400">
            <x-filament::icon icon="heroicon-o-sparkles" class="text-sky-500 inline-block h-5 w-5"/>
            {{ $user->experience . '/' . $user->experienceNeeded() }}
        </small>

        <small class="text-gray-400">
            <x-filament::icon icon="heroicon-o-circle-stack" class="text-amber-500 inline-block h-5 w-5"/>
            {{ $user->coins }}
        </small>
    </div>
</div>
