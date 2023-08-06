<?php

namespace App\Http\Controllers\Api\Patient;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Patient\StoreRequest;
use App\Services\Patient\PatientService;
use Illuminate\Http\JsonResponse;

class PatientController extends BaseController
{
    public function __construct()
    {
        parent::__construct(new PatientService());
    }

    /**
     * Adding a patient
     *
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function add(StoreRequest $request): JsonResponse
    {
        $patient = $this->service->add($request->validated());

        if ($patient) {
            return $this->apiResponseSuccess($patient);
        }

        return $this->apiResponseError(['Failed to add patient']);
    }

    /**
     * Get patients
     *
     * @return JsonResponse
     */
    public function get(): JsonResponse
    {
        $patients = $this->service->get();

        if ($patients) {
            return $this->apiResponseSuccess($patients);
        }

        return $this->apiResponseError(['Patients not found']);
    }
}
