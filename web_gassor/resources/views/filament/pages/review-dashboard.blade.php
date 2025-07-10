<x-filament-panels::page>
    {{-- Header Stats --}}
    @if($this->getHeaderWidgets())
        <x-filament-widgets::widgets
            {{-- :widgets="$this->getHeaderWidgets()" --}}
            :columns="$this->getHeaderWidgetsColumns()"
            :data="$this->getWidgetData()"
        />
    @endif

    {{-- Main Widgets --}}
    @if($this->getWidgets())
        <x-filament-widgets::widgets
            :widgets="$this->getWidgets()"
            :data="$this->getWidgetData()"
        />
    @endif
</x-filament-panels::page>
