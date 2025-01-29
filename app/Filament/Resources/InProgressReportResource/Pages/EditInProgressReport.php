<?php

namespace App\Filament\Resources\InProgressReportResource\Pages;

use App\Filament\Resources\InProgressReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Infolist;
use Illuminate\Support\HtmlString;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class EditInProgressReport extends EditRecord
{
    protected static string $resource = InProgressReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Bilder')
                    ->schema([
                        TextEntry::make('images')
                            ->label('')
                            ->formatStateUsing(function ($state, $record) {
                                if ($record->images->isEmpty()) {
                                    return new HtmlString('<div class="text-gray-500">Keine Bilder vorhanden</div>');
                                }

                                $images = $record->images->map(function ($image) {
                                    return [
                                        'url' => $image->url,
                                        'thumbnail' => $image->url,
                                    ];
                                })->values()->toArray();

                                $imagesJson = json_encode($images);

                                $html = <<<HTML
                                <div x-data="{ 
                                    images: {$imagesJson},
                                    currentIndex: 0,
                                    showModal: false,
                                    next() {
                                        this.currentIndex = (this.currentIndex + 1) % this.images.length;
                                    },
                                    prev() {
                                        this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
                                    },
                                    handleKeydown(e) {
                                        if (!this.showModal) return;
                                        if (e.key === 'ArrowRight') this.next();
                                        if (e.key === 'ArrowLeft') this.prev();
                                        if (e.key === 'Escape') this.showModal = false;
                                    }
                                }" 
                                @keydown.window="handleKeydown"
                                class="relative"
                                >
                                    <!-- Thumbnail Grid -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <template x-for="(image, index) in images" :key="index">
                                            <div>
                                                <img 
                                                    :src="image.thumbnail" 
                                                    @click="currentIndex = index; showModal = true"
                                                    class="w-full h-48 object-cover rounded-lg cursor-pointer hover:opacity-75 transition-opacity"
                                                    :alt="'Bild ' + (index + 1)"
                                                >
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Modal -->
                                    <div
                                        x-show="showModal"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition ease-in duration-300"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0"
                                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75"
                                        @click.self="showModal = false"
                                    >
                                        <div class="relative max-w-4xl max-h-screen p-4">
                                            <img 
                                                :src="images[currentIndex].url" 
                                                class="max-w-full max-h-[80vh] object-contain"
                                                :alt="'Bild ' + (currentIndex + 1)"
                                            >
                                            
                                            <!-- Navigation Buttons -->
                                            <button 
                                                @click="prev"
                                                class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-r"
                                            >
                                                ←
                                            </button>
                                            <button 
                                                @click="next"
                                                class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-l"
                                            >
                                                →
                                            </button>
                                            
                                            <!-- Close Button -->
                                            <button 
                                                @click="showModal = false"
                                                class="absolute top-0 right-0 m-4 text-white bg-black bg-opacity-50 rounded-full p-2"
                                            >
                                                ×
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                HTML;

                                return new HtmlString($html);
                            })
                            ->columnSpan(3)
                    ])
                    ->columnSpan(3)
            ]);
    }
}
