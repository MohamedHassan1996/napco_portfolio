<?php
namespace App\Filters\Certification;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
class CertificationSearchTranslatableFilter  implements Filter{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where(function ($query) use ($property, $value) {
            foreach (config('app.available_locales', ['en', 'ar']) as $locale) {
                $query->orWhereTranslationLike('title', "%{$value}%", $locale);
            }
        });
    }
}
?>
