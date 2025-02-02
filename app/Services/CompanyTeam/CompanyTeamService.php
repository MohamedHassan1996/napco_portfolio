<?php

namespace App\Services\CompanyTeam;

use Illuminate\Http\UploadedFile;
use Spatie\QueryBuilder\QueryBuilder;
use App\Services\Upload\UploadService;
use Spatie\QueryBuilder\AllowedFilter;
use App\Models\CompanyTeam\CompanyTeam;
use Illuminate\Support\Facades\Storage;
use App\Filters\CompanyTeam\companyTeamSearchFilter;



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
            ->allowedFilters([
               AllowedFilter::custom('search', new companyTeamSearchFilter()), // Add a custom search filter*/
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

        $companyTeam->is_active = $companyTeamData['isActive'];
        $companyTeam->image = $path;
        $companyTeam->name = $companyTeamData['name'];
        $companyTeam->social_links = $companyTeamData['socialLinks'];
        $companyTeam->job_title = $companyTeamData['jobTitle'];

        $companyTeam->save();

        return $companyTeam;

    }

    public function editCompanyTeam(int $companyTeamId)
    {
        return CompanyTeam::find($companyTeamId);
    }

    public function updateCompanyTeam(array $companyTeamData): CompanyTeam
    {

        $companyTeam = CompanyTeam::find($companyTeamData['companyTeamId']);

        $companyTeam->is_active = $companyTeamData['isActive'];
        $companyTeam->name = $companyTeamData['name'];
        $companyTeam->social_links = $companyTeamData['socialLinks'];
        $companyTeam->job_title = $companyTeamData['jobTitle'];


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
