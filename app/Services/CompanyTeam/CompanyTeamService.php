<?php

namespace App\Services\CompanyTeam;

use App\Models\CompanyTeam\CompanyTeam;
use App\Services\Upload\UploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CompanyTeamService{

    private $companyTeam;
    private $uploadService;
    public function __construct(CompanyTeam $companyTeam, UploadService $uploadService)
    {
        $this->companyTeam = $companyTeam;
        $this->uploadService = $uploadService;
    }

    public function allCompanyTeams()
    {
        $locale = app()->getLocale(); // Get the current locale

        $companyTeams = QueryBuilder::for(CompanyTeam::class)
            ->withTranslation() // Fetch translations if applicable
            ->allowedFilters([
               /* AllowedFilter::custom('search', new companyTeamSearchTranslatableFilter()), // Add a custom search filter*/
            ])
            ->get();

        return $companyTeams;

    }

    public function createCompanyTeam(array $companyTeamData): CompanyTeam
    {

        $path = null;

        if(isset($companyTeamData['image']) && $companyTeamData['image'] instanceof UploadedFile){
            $path =  $this->uploadService->uploadFile($companyTeamData['image'], 'companyTeams');
        }

        $companyTeam = new CompanyTeam();

        $companyTeam->is_published = $companyTeamData['isPublished'];
        $companyTeam->image = $path;


        if (!empty($companyTeamData['titleAr'])) {
            $companyTeam->translateOrNew('ar')->title = $companyTeamData['titleAr'];
            $companyTeam->translateOrNew('ar')->description = $companyTeamData['descriptionAr'];
        }

        if (!empty($companyTeamData['titleEn'])) {
            $companyTeam->translateOrNew('en')->title = $companyTeamData['titleEn'];
            $companyTeam->translateOrNew('en')->description = $companyTeamData['descriptionEn'];
        }

        $companyTeam->save();

        return $companyTeam;

    }

    public function editCompanyTeam(int $companyTeamId)
    {
        return CompanyTeam::with('translations')->find($companyTeamId);
    }

    public function updateCompanyTeam(array $companyTeamData): CompanyTeam
    {

        $companyTeam = CompanyTeam::find($companyTeamData['companyTeamId']);

        $companyTeam->is_published = $companyTeamData['isPublished'];

        $path = null;

        if(isset($companyTeamData['image']) && $companyTeamData['image'] instanceof UploadedFile){
            $path =  $this->uploadService->uploadFile($companyTeamData['image'], 'companyTeams');
        }

        if($companyTeam->image && $path){
            Storage::disk('public')->delete($companyTeam->image);
        }

        if($path){
            $companyTeam->image = $path;
        }

        if (!empty($companyTeamData['titleAr'])) {
            $companyTeam->translateOrNew('ar')->title = $companyTeamData['titleAr'];
            $companyTeam->translateOrNew('ar')->description = $companyTeamData['descriptionAr'];

        }

        if (!empty($companyTeamData['titleEn'])) {
            $companyTeam->translateOrNew('en')->title = $companyTeamData['titleEn'];
            $companyTeam->translateOrNew('en')->description = $companyTeamData['descriptionEn'];
        }

        $companyTeam->save();

        return $companyTeam;


    }


    public function deleteCompanyTeam(int $companyTeamId)
    {

        $companyTeam  = CompanyTeam::find($companyTeamId);

        if($companyTeam->image){
            Storage::disk('public')->delete($companyTeam->image);
        }

        $companyTeam->delete();

    }


    public function changeStatus(int $companyTeamId, bool $isPublished)
    {
        $companyTeam = CompanyTeam::find($companyTeamId);
        $companyTeam->is_published = $isPublished;
        $companyTeam->save();
    }

}
