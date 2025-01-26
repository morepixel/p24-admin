<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InProgressReportResource\Pages;
use App\Models\Report;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Infolist;

class InProgressReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Reports';

    protected static ?string $navigationLabel = 'In Bearbeitung';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', Report::STATUS_IN_PROGRESS)->count();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereIn('status', [
                Report::STATUS_IN_PROGRESS,
                Report::STATUS_COMPLETED,
                4, // Halterabfrage zurück
            ])
            ->with(['address', 'images']); // Eager load both relationships
    }

    public static function form(Form $form): Form
    {
        return ReportResource::form($form);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('createdAt')
                    ->label('Erstellt')
                    ->date('d.m.Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fullPlateCode')
                    ->label('Kennzeichen')
                    ->searchable(['plateCode1', 'plateCode2', 'plateCode3'])
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('plateCode1', $direction)
                            ->orderBy('plateCode2', $direction)
                            ->orderBy('plateCode3', $direction);
                    }),
                Tables\Columns\TextColumn::make('companyName')
                    ->label('Firmenname')
                    ->searchable(),
                Tables\Columns\IconColumn::make('has_images')
                    ->label('Bilder')
                    ->icon('heroicon-o-camera')
                    ->boolean()
                    ->state(fn (Model $record): bool => $record->images()->count() > 0)
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->alignCenter()
                    ->action(
                        Tables\Actions\Action::make('view_images')
                            ->label('Bilder anzeigen')
                            ->modalHeading('Bilder')
                            ->modalSubmitAction(false)
                            ->modalCancelAction(false)
                            ->modalContent(function ($record): HtmlString {
                                $images = $record->images;
                                if ($images->isEmpty()) {
                                    return new HtmlString('<div class="p-4">Keine Bilder vorhanden</div>');
                                }
                                
                                $html = '<div class="grid grid-cols-2 gap-4 p-4">';
                                foreach ($images as $image) {
                                    $html .= sprintf(
                                        '<img src="%s" alt="Bild" class="w-full h-auto rounded-lg shadow-lg">',
                                        (string)$image->url
                                    );
                                }
                                $html .= '</div>';
                                
                                return new HtmlString($html);
                            })
                            ->modalWidth('md')
                    ),
                Tables\Columns\TextColumn::make('firstname')
                    ->label('Vorname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lastname')
                    ->label('Nachname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-Mail')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '0' => 'gray',
                        '1' => 'warning',
                        '2' => 'success',
                        '3' => 'info',
                        '4' => 'success',
                        '5' => 'warning',
                        '6' => 'success',
                        '18' => 'danger',
                        '19' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (Report $record): string => $record->status_label),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '0' => 'gray',
                        '1' => 'warning',
                        '2' => 'success',
                        '3' => 'info',
                        '4' => 'success',
                        '5' => 'warning',
                        '6' => 'success',
                        '18' => 'danger',
                        '19' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (Report $record): string => $record->status_label),
            ])
            ->defaultSort('createdAt', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->slideOver()
                    ->form([
                        Forms\Components\TextInput::make('plateCode1')
                            ->label('Kennzeichen 1')
                            ->required(),
                        Forms\Components\TextInput::make('plateCode2')
                            ->label('Kennzeichen 2'),
                        Forms\Components\TextInput::make('plateCode3')
                            ->label('Kennzeichen 3'),
                        Forms\Components\TextInput::make('halterName')
                            ->label('Name')
                            ->required(),
                        Forms\Components\TextInput::make('halterStreet')
                            ->label('Straße')
                            ->required(),
                        Forms\Components\TextInput::make('halterCity')
                            ->label('Stadt')
                            ->required(),
                        Forms\Components\TextInput::make('halterZip')
                            ->label('PLZ')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options(Report::STATUS_LABELS)
                            ->required(),
                        Forms\Components\Select::make('lawyerapprovalstatus')
                            ->label('Anwalt Status')
                            ->options(Report::LAWYER_APPROVAL_STATUS_LABELS)
                            ->required(),
                    ]),
                Tables\Actions\Action::make('cancel')
                    ->label('Stornieren')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()
                    ->action(function (Report $record) {
                        $record->update(['status' => 19]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInProgressReports::route('/'),
            'edit' => Pages\EditInProgressReport::route('/{record}/edit'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make(3)
                    ->schema([
                        Section::make('Halter')
                            ->description('Halterdaten')
                            ->schema([
                                TextEntry::make('halterName')
                                    ->label('Name'),
                                TextEntry::make('halterStreet')
                                    ->label('Straße'),
                                TextEntry::make('halterZip')
                                    ->label('PLZ'),
                                TextEntry::make('halterCity')
                                    ->label('Stadt'),
                            ])
                            ->columnSpan(2),

                        Grid::make()
                            ->schema([
                                Section::make('Vollmacht')
                                    ->schema([
                                        TextEntry::make('address.poaFile')
                                            ->label('')
                                            ->formatStateUsing(function ($state, $record) {
                                                if (!$record->address || !$record->address->poaFile) {
                                                    return '';
                                                }

                                                $html = '<div class="space-y-2">';
                                                $html .= '<a href="' . $record->address->poaFile . '" target="_blank" class="text-primary-600 hover:text-primary-500">Vollmacht</a>';
                                                
                                                if ($record->address->poaFileUploadedAt) {
                                                    $html .= '<div class="text-sm text-gray-500">Hochgeladen am ' . 
                                                        $record->address->poaFileUploadedAt->format('d.m.Y H:i:s') . 
                                                        '</div>';
                                                }
                                                
                                                $html .= '</div>';
                                                
                                                return new HtmlString($html);
                                            }),
                                    ]),

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
                                                        'thumbnail' => $image->url, // Hier könnten Sie eine Thumbnail-URL verwenden, falls verfügbar
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
                                                    }
                                                }">
                                                    <!-- Thumbnail Slider -->
                                                    <div class="relative">
                                                        <div class="flex space-x-2 overflow-x-auto pb-2">
                                                            <template x-for="(image, index) in images" :key="index">
                                                                <div 
                                                                    class="flex-none cursor-pointer"
                                                                    @click="currentIndex = index; showModal = true"
                                                                >
                                                                    <img 
                                                                        :src="image.thumbnail" 
                                                                        class="h-24 w-24 object-cover rounded-lg hover:opacity-75 transition-opacity"
                                                                        :class="{'ring-2 ring-primary-500': currentIndex === index}"
                                                                    >
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>

                                                    <!-- Modal -->
                                                    <div
                                                        x-show="showModal"
                                                        x-transition
                                                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                                        @click.self="showModal = false"
                                                    >
                                                        <div class="relative bg-white p-4 rounded-lg max-w-3xl max-h-[90vh] overflow-hidden">
                                                            <!-- Close Button -->
                                                            <button 
                                                                @click="showModal = false"
                                                                class="absolute top-2 right-2 text-gray-500 hover:text-gray-700"
                                                            >
                                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>

                                                            <!-- Image -->
                                                            <div class="relative">
                                                                <img 
                                                                    :src="images[currentIndex].url"
                                                                    class="max-h-[80vh] mx-auto"
                                                                >

                                                                <!-- Navigation Buttons -->
                                                                <button 
                                                                    @click="prev"
                                                                    class="absolute left-2 top-1/2 -translate-y-1/2 bg-white rounded-full p-2 shadow-lg hover:bg-gray-100"
                                                                >
                                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                                    </svg>
                                                                </button>
                                                                <button 
                                                                    @click="next"
                                                                    class="absolute right-2 top-1/2 -translate-y-1/2 bg-white rounded-full p-2 shadow-lg hover:bg-gray-100"
                                                                >
                                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                                    </svg>
                                                                </button>
                                                            </div>

                                                            <!-- Image Counter -->
                                                            <div class="text-center mt-2">
                                                                <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                HTML;

                                                return new HtmlString($html);
                                            }),
                                    ]),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }
}
