<?php

namespace App\Services\Certification;

use App\Models\Certification\Certification;
use App\Services\Upload\UploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CertificationService{

    private $certification;
    private $uploadService;
    public function __construct(Certification $certification, UploadService $uploadService)
    {
        $this->certification = $certification;
        $this->uploadService = $uploadService;
    }

    public function allCertifications()
    {
        $locale = app()->getLocale(); // Get the current locale

        $certifications = QueryBuilder::for(Certification::class)
            ->withTranslation() // Fetch translations if applicable
            ->allowedFilters([
               /* AllowedFilter::custom('search', new BlogSearchTranslatableFilter()), // Add a custom search filter*/
            ])
            ->get();

        return $certifications;

    }

    public function createBlog(array $certificationData): Certification
    {

        $path = null;

        if(isset($certificationData['image']) && $certificationData['image'] instanceof UploadedFile){
            $path =  $this->uploadService->uploadFile($certificationData['image'], 'certifications');
        }

        $certification = new Certification();

        $certification->is_published = $certificationData['isPublished'];
        $certification->image = $path;


        if (!empty($certificationData['titleAr'])) {
            $certification->translateOrNew('ar')->title = $certificationData['titleAr'];
            $certification->translateOrNew('ar')->description = $certificationData['descriptionAr'];
        }

        if (!empty($certificationData['titleEn'])) {
            $certification->translateOrNew('en')->title = $certificationData['titleEn'];
            $certification->translateOrNew('en')->description = $certificationData['descriptionEn'];
        }

        $certification->save();

        return $certification;

    }

    public function editBlog(int $blogId)
    {
        return Certification::with('translations')->find($blogId);
    }

    public function updateBlog(array $certificationData): Certification
    {

        $certification = Certification::find($certificationData['blogId']);

        $certification->is_published = $certificationData['isPublished'];

        $path = null;

        if(isset($certificationData['image']) && $certificationData['image'] instanceof UploadedFile){
            $path =  $this->uploadService->uploadFile($certificationData['image'], 'certifications');
        }

        if($certification->image && $path){
            Storage::disk('public')->delete($certification->image);
        }

        if($path){
            $certification->image = $path;
        }

        if (!empty($certificationData['titleAr'])) {
            $certification->translateOrNew('ar')->title = $certificationData['titleAr'];
            $certification->translateOrNew('ar')->description = $certificationData['descriptionAr'];

        }

        if (!empty($certificationData['titleEn'])) {
            $certification->translateOrNew('en')->title = $certificationData['titleEn'];
            $certification->translateOrNew('en')->description = $certificationData['descriptionEn'];
        }

        $certification->save();

        return $certification;


    }


    public function deleteBlog(int $blogId)
    {

        $certification  = Certification::find($blogId);

        if($certification->image){
            Storage::disk('public')->delete($certification->image);
        }

        $certification->delete();

    }


    public function changeStatus(int $blogId, bool $isPublished)
    {
        $certification = Certification::find($blogId);
        $certification->is_published = $isPublished;
        $certification->save();
    }

}
