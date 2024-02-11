<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use App\Models\Course;
use App\Models\Lesson;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;


class SectionsRelationManager extends RelationManager
{
    protected static string $relationship = 'sections';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Sections & Lessons')
                    ->tabs([
                        Tabs\Tab::make('Section')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('description'),
                                Forms\Components\TextInput::make('order')
                                    ->default($this->ownerRecord->sections->count() + 1),
                            ]),
                        Tabs\Tab::make('Lessons')
                            ->schema([

                                Repeater::make('lessons')
                                    ->relationship()
                                    ->schema([
                                        Section::make('lesson infos')
                                            ->schema([
                                                TextInput::make('title')
                                                    ->required(),
                                                Toggle::make('premium')
                                                    ->default(false)
                                                    ->inline(false),

                                            ])->columns(3),
                                        Select::make('type')
                                            ->options([
                                                'video' => 'Video',
                                                'article' => 'Article',
                                                'quiz' => 'Quiz',
                                            ])
                                            ->live()
                                            ->default('video'),
                                        TextInput::make('url')
                                            ->label('Video URL')
                                            ->hidden(function (Get $get) {
                                                return $get('type') !== Lesson::VIDEO;
                                            }),
                                        // Forms\Components\TextInput::make('order')
                                        //     ->default(function () {
                                        //         return $this->ownerRecord->lessons->count() + 1;
                                        //     }),
                                        RichEditor::make('article')
                                            ->label('Article Content')
                                            ->hidden(function (Get $get) {
                                                return $get('type') !== Lesson::ARTICLE;
                                            })->columnSpanFull(),
                                        Repeater::make('questions')
                                            ->relationship()
                                            ->schema([
                                                TextInput::make('content')->required(),
                                                Repeater::make('options')
                                                    ->relationship()
                                                    ->schema([
                                                        TextInput::make('content')
                                                            ->required(),
                                                        Toggle::make('is_correct')
                                                            ->default(false)
                                                            ->inline(false),
                                                    ])->columns(2)
                                                    ->defaultItems(4)
                                                    ->orderColumn('created_at')
                                                    ->collapsible()
                                                    ->collapsed()
                                                    ->reorderable()
                                                    ->itemLabel(fn (array $state): ?string => $state['content'] ?? null),
                                            ])
                                            ->columnSpanFull()
                                            ->collapsible()
                                            ->collapsed()
                                            ->reorderable()
                                            ->itemLabel(fn (array $state): ?string => $state['content'] ?? null)
                                            ->hidden(function (Get $get) {
                                                return $get('type') !== Lesson::QUIZ;
                                            }),
                                    ])
                                    ->columns(2)->defaultItems(1)
                                    ->orderColumn('order')
                                    ->collapsible()
                                    ->collapsed()
                                    ->reorderable()
                                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null),

                            ]),
                    ])->columnSpanFull(),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('order')->badge(),
            ])
            ->reorderable('order')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
