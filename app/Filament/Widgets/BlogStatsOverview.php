<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Gallery;
use App\Models\Media;
use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BlogStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Published Posts', Post::published()->count())
                ->description('Live on public journal')
                ->color('success'),
            Stat::make('Draft Posts', Post::where('status', 'draft')->count())
                ->description('Unpublished drafts')
                ->color('warning'),
            Stat::make('Categories', Category::count())
                ->color('gray'),
            Stat::make('Media Items', Media::count())
                ->color('info'),
            Stat::make('Galleries', Gallery::count())
                ->color('primary'),
        ];
    }
}
