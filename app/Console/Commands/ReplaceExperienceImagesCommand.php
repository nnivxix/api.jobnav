<?php

namespace App\Console\Commands;

use App\Models\Experience;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use MarkSitko\LaravelUnsplash\Facades\Unsplash;

class ReplaceExperienceImagesCommand extends Command
{
    protected $signature = 'replace-logos:experience';
    protected $description = 'Replace logos experience';

    public function handle()
    {
        $this->info('Fetching all experiences...');
        $experiences = Experience::all();

        $this->info('Fetching random image from Unsplash...');
        $randomLogo = Unsplash::search()
            ->term('logo')
            ->orientation('squarish')
            ->perPage(30)
            ->toJson();

        $this->info('Updating company logo of experiences...');
        $bar = $this->output->createProgressBar($experiences->count());

        foreach ($experiences as $experience) {
            $logo_url = $randomLogo->results[rand(1, 30) - 1]->urls->small;
            $logo_path = 'experiences/logo/' .  Str::random(20) . '.jpg';

            if ($experience->logo && Storage::disk('public')->exists($experience->logo)) {
                Storage::delete('public/' . $experience->logo);
            }
            Storage::disk('public')->put($logo_path, file_get_contents($logo_url));

            $experience->update([
                'logo' => $logo_path,
            ]);

            $bar->advance();
        }
        $bar->finish();
        $this->info("\nDone");
        return Command::SUCCESS;
    }
}
