<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers\SectionsRelationManager;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-m-code-bracket-square';
    protected static ?string $navigationGroup = 'Course Related';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    Forms\Components\Select::make('created_by')
                        ->label('Creator')
                        ->relationship('creator', 'name')
                        ->default(auth()->id())
                        ->required(),
                    Section::make('Title')
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn (Get $get, Set $set) => $set('slug', Str::slug($get('title')))),
                            TextInput::make('slug')
                                ->required()
                                ->disabled(),
                        ]),
                    Forms\Components\MarkdownEditor::make('description')
                        ->required()
                        ->columnSpanFull(),
                    Select::make('category_id')
                        ->label('Category')
                        ->relationship('category', 'name')
                        ->preload(),
                    Select::make('tags')
                        ->relationship('tags', 'name')
                        ->multiple()
                        ->preload(),

                ]),
                Group::make([
                    Forms\Components\TextInput::make('price')
                        ->required()
                        ->numeric()
                        ->default(0)
                        ->prefix('$'),
                    Forms\Components\TextInput::make('old_price')
                        ->required()
                        ->numeric()
                        ->default(0)
                        ->prefix('$'),
                    Forms\Components\TextInput::make('intro')->label('Intro Video Url'),
                    Forms\Components\Select::make('level')
                        ->options([
                            'beginner' => 'Beginner',
                            'intermediate' => 'Intermediate',
                            'advanced' => 'Advanced',
                        ])
                        ->label('Course Level')
                        ->default('beginner')
                        ->required(),
                    Forms\Components\Toggle::make('premium')
                        ->required(),
                    FileUpload::make('image')
                        ->label('Course Cover Image')
                        ->image()
                        ->directory('courses')
                        ->imageEditor(),

                ])
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->circular(),
                // Tables\Columns\TextColumn::make('creator.name')
                //     ->label('Creator')
                //     ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('lessonsNumber'),

                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('old_price')
                    ->money()
                    ->sortable(),
                TextColumn::make('category.name'),
                Tables\Columns\TextColumn::make('level')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'beginner' => 'success',
                        'intermediate' => 'warning',
                        'advanced' => 'danger',
                    })
                    ->searchable(),
                TextColumn::make('tags.name')
                    ->badge()

                    ->weight(FontWeight::Light)
                    ->color(fn () => Arr::random(Color::all()))
                    ->searchable(),
                Tables\Columns\IconColumn::make('premium')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()

                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SectionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}