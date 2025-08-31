<x-filament-widgets::widget>
    <x-filament::section>

        <div x-data="{ tab: 'tab1' }">
            <x-filament::tabs>
                <x-filament::tabs.item
                    alpine-active="tab === 'tab1'"
                    x-on:click="tab = 'tab1'"
                >
                    {{ __('Computers') }}
                </x-filament::tabs.item>
                <x-filament::tabs.item
                    alpine-active="tab === 'tab2'"
                    x-on:click="tab = 'tab2'"
                >
                    {{ __('Openings') }}
                </x-filament::tabs.item>
                <x-filament::tabs.item
                    alpine-active="tab === 'tab3'"
                    x-on:click="tab = 'tab3'"
                >
                    {{ __('Games') }}
                </x-filament::tabs.item>
            </x-filament::tabs>
            <div class="mt-4">
                <div class="grid grid-cols-4 gap-4" x-show="tab === 'tab1'">
                    @if ($computer)
                        <x-filament::button size="xs" color="" wire:click="resetPlayground()">
                            <img class="rounded-full mb-2" src="{{ asset('images/chessbots/'. $computer->avatar) }}" alt="">
                            <p class="text-gray-400">
                                <x-filament::icon icon="heroicon-o-sparkles" class="text-sky-500 inline-block h-3 w-3"/>
                                {{ $computer->rating }}
                            </p>
                        </x-filament::button>
                        <x-filament::fieldset class="col-span-3">
                            <x-slot name="label">
                                <p class="text-gray-400">{{ $computer->name }}</p>
                            </x-slot>
                            <p class="text-gray-400 italic">{{ $computer->message }}</p>
                        </x-filament::fieldset>
                    @else
                        @foreach($computers as $index => $computer)
                            <x-filament::button size="xs" color="gray" wire:click="selectComputer('{{ addslashes($computer->name) }}')" :disabled="$computer->isDisabled($index)" outlined>
                                @if($computer->isDisabled($index))
                                    <x-filament::icon icon="heroicon-o-lock-closed" class="absolute start-1 top-1 h-3 w-3"/>
                                @endif
                                <img class="rounded-full mb-2" src="{{ asset('images/chessbots/'. $computer->avatar) }}" alt="">
                                <p class="text-gray-400">{{ $computer->name }}</p>
                                <p class="text-gray-400">
                                    <x-filament::icon icon="heroicon-o-sparkles" class="text-sky-500 inline-block h-3 w-3"/>
                                    {{ $computer->rating }}
                                </p>
                            </x-filament::button>
                        @endforeach
                    @endif
                </div>
                <div class="grid grid-cols-4 gap-4" x-show="tab === 'tab2'">
                    @if ($opening)
                        <x-filament::button size="xs" color="" wire:click="resetPlayground()">
                            <p class="text-gray-400">{{ $opening->name }}</p>
                            <p class="text-gray-400">
                                <x-filament::icon icon="heroicon-o-sparkles" class="text-sky-500 inline-block h-3 w-3"/>
                                750
                            </p>
                        </x-filament::button>
                        <x-filament::fieldset class="col-span-3">
                            <x-slot name="label">
                                <small class="text-gray-400">{{ $opening->name }}</small>
                            </x-slot>
                            <p class="text-gray-400 italic">{{ $opening->message }}</p>
                        </x-filament::fieldset>
                    @else
                        @foreach($openings as $opening)
                            <x-filament::button class="col-span-4" size="xs" color="gray" wire:click="selectOpening('{{ addslashes($opening->name) }}')" :disabled="$opening->isDisabled()" outlined>
                                @if($opening->isDisabled())
                                    <x-filament::icon icon="heroicon-o-lock-closed" class="absolute start-1 top-1 h-3 w-3"/>
                                @endif
                                <p class="text-gray-400">{{ $opening->name }}</p>
                                <p class="text-gray-400">
                                    <x-filament::icon icon="heroicon-o-sparkles" class="text-sky-500 inline-block h-3 w-3"/>
                                    750
                                </p>
                            </x-filament::button>
                        @endforeach
                    @endif
                </div>
                <div class="grid grid-cols-4 gap-4" x-show="tab === 'tab3'">
                    @if ($game)
                        <x-filament::button size="xs" color="" wire:click="resetPlayground()">
                            <p class="text-gray-400">{{ $game->getOpponent() }}</p>
                            <p class="text-gray-400">
                                <x-filament::icon icon="heroicon-o-sparkles" class="text-sky-500 inline-block h-3 w-3"/>
                                750
                            </p>
                        </x-filament::button>
                        <x-filament::fieldset class="col-span-3">
                            <x-slot name="label">
                                <small class="text-gray-400">{{ $game->getOpponent() }}</small>
                            </x-slot>
                            <p class="text-gray-400 italic">{{ $game->pgn }}</p>
                        </x-filament::fieldset>
                    @else
                        @forelse($games as $game)
                            <x-filament::button class="col-span-4" size="xs" color="gray" wire:click="selectGame({{ $game->id }})" :disabled="$game->isDisabled()" outlined>
                                @if($game->isDisabled())
                                    <x-filament::icon icon="heroicon-o-lock-closed" class="absolute start-1 top-1 h-3 w-3"/>
                                @endif
                                <p class="text-gray-400">{{ $game->getOpponent() }}</p>
                                <p class="text-gray-400">
                                    <x-filament::icon icon="heroicon-o-sparkles" class="text-sky-500 inline-block h-3 w-3"/>
                                    750
                                </p>
                            </x-filament::button>
                        @empty
                            @auth
                                <x-filament::button tag="a" href="{{ route('filament.admin.resources.users.index') }}" color="gray" outlined>
                                    {{ __('Challenge') }}
                                </x-filament::button>
                            @endauth
                            @guest
                                <x-filament::button tag="a" href="{{ route('filament.admin.auth.login') }}" color="gray" outlined>
                                    {{ __('Login') }}
                                </x-filament::button>
                            @endguest
                        @endforelse
                    @endif
                </div>
            </div>
        </div>

    </x-filament::section>
</x-filament-widgets::widget>
