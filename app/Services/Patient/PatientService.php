<?php

namespace App\Services\Patient;

use Carbon\Carbon;
use App\Models\Patient\Patient;
use App\Services\Service;
use App\Jobs\PatientJob;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PatientService extends Service
{
    const DATE_DAY = 'день';
    const DATE_MONTH = 'месяц';
    const DATE_YEAR = 'год';
    const CACHE_TIME = 300;

    public function __construct()
    {
        parent::__construct(new Patient());
    }

    /**
     * Method for creating a new object for the model
     *
     * @param array $data
     * @return Builder|Model
     */
    public function add(array $data): Builder|Model
    {
        $birthdate = $data['birthdate'];
        $age = $this->getAge($birthdate);

        $data['age'] = $age['age'];
        $data['age_type'] = $age['type'];

        // If we create a new model, then clear the cache
        Cache::forget('patients');

        // it is not entirely clear why there is a queue here, but there was such a requirement in
        // the test task, so I added

        // dispatch(new PatientJob($this->model, $data));

        return $this->model->create($data);
    }

    /**
     * Method of getting the model collection from the cache
     *
     * @return Collection|array
     */
    public function get(): Collection|array
    {
        return Cache::remember('patients', self::CACHE_TIME, function() {
            return $this->model
                ->get(
                    DB::raw(
                        "
                            CONCAT(patients.first_name,' ',patients.last_name) as name,
                            DATE_FORMAT(birthdate, '%d.%m.%Y') as birthdate,
                            CONCAT(patients.age,' ',patients.age_type) as age
                        "
                    ),
                );
        });
    }

    /**
     * Get age
     *
     * @param $birthdate
     * @return array
     */
    public function getAge($birthdate): array
    {
        $diffDate = Carbon::parse($birthdate)->diff(Carbon::now());
        $fullAge = [
            'age' => $diffDate->format('%y'),
            'type' =>  self::DATE_YEAR
        ];

        if (!$diffDate->format('%m') && !$diffDate->format('%y')) {
            $fullAge = [
                'age' => $diffDate->format('%d'),
                'type' =>  self::DATE_DAY
            ];
        }

        if (!$diffDate->format('%y') && $diffDate->format('%m')) {
            $fullAge = [
                'age' => $diffDate->format('%m'),
                'type' =>  self::DATE_MONTH
            ];
        }

        return $fullAge;
    }
}
