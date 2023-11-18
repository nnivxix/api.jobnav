<?php

namespace App\Console\Commands;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use MarkSitko\LaravelUnsplash\Facades\Unsplash;

class ImageUnsplash extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'replace-image:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Replace all user avatar with Unsplash image';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Fetching all users...');
        $profiles = Profile::all();

        $this->info('Fetching random image from Unsplash...');
        $randomPeople = Unsplash::randomPhoto()
            ->orientation('portrait')
            ->term('people')
            ->count(30)
            ->toJson();

        $coverImages = Unsplash::randomPhoto()
            ->orientation('landscape')
            ->term('nature')
            ->randomPhoto()
            ->count(30)
            ->toJson();

        $this->info('Updating user avatar...');
        $bar = $this->output->createProgressBar($profiles->count());

        foreach ($profiles as $index => $profile) {
            $avatar_url = $randomPeople[rand(1, 30) - 1]->urls->small;
            $avatar_path = 'users/avatars/' .  Str::random(40) . '.jpg';
            Storage::disk('public')->put($avatar_path, file_get_contents($avatar_url));

            $cover = $coverImages[rand(1, 30) - 1]->urls->regular;
            $cover_path = 'users/covers/' .  Str::random(40) . '.jpg';
            Storage::disk('public')->put($cover_path, file_get_contents($cover));

            $profile->update([
                'avatar' => $avatar_path,
                'cover' => $cover_path,
            ]);

            $bar->advance();
        }

        $bar->finish();

        $this->info("\nDone!");
    }
}
