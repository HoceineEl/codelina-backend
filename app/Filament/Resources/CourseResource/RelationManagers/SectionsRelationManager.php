<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Alaouy\Youtube\Facades\Youtube;
use App\Models\Course;
use App\Models\Lesson;
use DateInterval;
use Filament\Forms;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
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
                                    ->disabled()
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
                                        TextInput::make('video_id')
                                            ->label('Video ID')
                                            ->hidden(
                                                fn (Get $get) => $get('type') !== Lesson::VIDEO
                                            )
                                            ->afterStateUpdated(function (Get $get, Set $set) {
                                                $video = Youtube::getVideoInfo($get('video_id'));
                                                $duration = 0;
                                                $duration = $this->getMinutes($video->contentDetails->duration);

                                                $set('duration', $duration);
                                            })
                                            ->live(),
                                        TextInput::make('duration')
                                            ->label('Lesson duration')
                                            ->readOnly()
                                            ->default(0)
                                            ->hidden(
                                                fn (Get $get) => $get('type') == Lesson::QUIZ
                                            ),
                                        MarkdownEditor::make('article')
                                            ->label('Article Content')
                                            ->hidden(fn (Get $get) => $get('type') !== Lesson::ARTICLE)
                                            ->live()
                                            ->afterStateUpdated(function (Get $get, Set $set) {
                                                $words = str_word_count($get('video_id'));
                                                $duration = round($words / 200, 2);
                                                $set('duration', $duration);
                                            })
                                            ->columnSpanFull(),
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
                                                    ->itemLabel(fn (array $state): ?string => $state['content'] . ($state['is_correct'] ? 'Correct' : '') ?? null),
                                            ])
                                            ->columnSpanFull()
                                            ->collapsible()
                                            ->collapsed()
                                            ->reorderable()
                                            ->itemLabel(fn (array $state): ?string => $state['content']  ?? null)
                                            ->hidden(function (Get $get) {
                                                return $get('type') !== Lesson::QUIZ;
                                            }),
                                    ])
                                    ->columns(2)
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
    public function getMinutes($duration)
    {

        // Parse the duration string
        preg_match('/PT(\d+H)?(\d+M)?(\d+S)?/', $duration, $matches);

        // Extract hours, minutes, and seconds
        $hours = isset($matches[1]) ? intval(str_replace('H', '', $matches[1])) : 0;
        $minutes = isset($matches[2]) ? intval(str_replace('M', '', $matches[2])) : 0;
        $seconds = isset($matches[3]) ? intval(str_replace('S', '', $matches[3])) : 0;

        // Calculate the total duration in minutes
        $totalMinutes = $hours * 60 + $minutes + round($seconds / 60);

        return $totalMinutes;
    }
}