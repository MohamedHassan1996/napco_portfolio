<?php
namespace App\Filters\CompanyTeam;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class companyTeamSearchFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        return $query->where(function ($query) use ($value) {
            $query->where('name', 'like', '%' . $value . '%')
                ->orWhere('job_title', 'like', '%' . $value . '%');
        });
    }
}
