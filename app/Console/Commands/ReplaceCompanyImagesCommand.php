<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use MarkSitko\LaravelUnsplash\Facades\Unsplash;

class ReplaceCompanyImagesCommand extends Command
{
    protected $signature = 'replace-images:company';
    protected $description = 'Replace company images';

    public function handle()
    {
        $this->info('Fetching all company...');
        $companies = Company::all();

        $this->info('Fetching random image from Unsplash...');
        $randomLogo = Unsplash::search()
            ->term('logo')
            ->orientation('squarish')
            ->perPage(30)
            ->toJson();
        $randomBuildings = Unsplash::search()
            ->term('building')
            ->orientation('landscape')
            ->color('black_and_white')
            ->perPage(30)
            ->toJson();

        $this->info('Updating company logo and cover...');
        $bar = $this->output->createProgressBar($companies->count());

        foreach ($companies as $company) {
            $logo_url = $randomLogo->results[rand(1, 30) - 1]->urls->small;
            $logo_path = 'companies/avatars/' .  Str::random(20) . '.jpg';

            if ($company->avatar && Storage::disk('public')->exists($company->avatar)) {
                Storage::delete('public/' . $company->avatar);
            }
            Storage::disk('public')->put($logo_path, file_get_contents($logo_url));

            $building_url = $randomBuildings->results[rand(1, 30) - 1]->urls->small;
            $building_path = 'companies/covers/' .  Str::random(20) . '.jpg';

            if ($company->cover && Storage::disk('public')->exists($company->cover)) {
                Storage::delete('public/' . $company->cover);
            }
            Storage::disk('public')->put($building_path, file_get_contents($building_url));

            $company->update([
                'avatar' => $logo_path,
                'cover' => $building_path,
            ]);

            $bar->advance();
        }
        $bar->finish();
        return Command::SUCCESS;
    }
}
