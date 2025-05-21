<?php

namespace App\Http\Controllers;

use App\DTOs\AppInfoDTO;
use App\Http\Requests\AppInfo\CreateAppInfoRequest;
use App\Services\AppInfoService;
use Illuminate\Http\Request;

class AppInfoController extends Controller
{
    public function __construct(
        protected AppInfoService $appInfoService,
    )
    {
    }
    public function createAboutApp(CreateAppInfoRequest $request)
    {
        $appInfoDTO=AppInfoDTO::fromRequest($request);
        return $this->appInfoService->createAboutApp($appInfoDTO);
    }
    public function updateAboutApp(CreateAppInfoRequest $request)
    {
        $appInfoDTO=AppInfoDTO::fromRequest($request);
        return $this->appInfoService->updateAboutApp($appInfoDTO);
    }
    public function getAboutApp()
    {
        return $this->appInfoService->getAboutApp();
    }

    public function deleteAboutApp()
    {
        return $this->appInfoService->deleteAboutApp();
    }

    public function createTermsAndConditions(CreateAppInfoRequest $request)
    {
        $appInfoDTO=AppInfoDTO::fromRequest($request);
        return $this->appInfoService->createTermsAndConditions($appInfoDTO);
    }
    public function updateTermsAndConditions(CreateAppInfoRequest $request)
    {
        $appInfoDTO=AppInfoDTO::fromRequest($request);
        return $this->appInfoService->updateTermsAndConditions($appInfoDTO);
    }
    public function getTermsAndConditions()
    {
        return $this->appInfoService->getTermsAndConditions();
    }

    public function deleteTermsAndConditions()
    {
        return $this->appInfoService->deleteTermsAndConditions();
    }

    public function createPrivacyPolicy(CreateAppInfoRequest $request)
    {
        $appInfoDTO=AppInfoDTO::fromRequest($request);
        return $this->appInfoService->createPrivacyPolicy($appInfoDTO);
    }
    public function updatePrivacyPolicy(CreateAppInfoRequest $request)
    {
        $appInfoDTO=AppInfoDTO::fromRequest($request);
        return $this->appInfoService->updatePrivacyPolicy($appInfoDTO);
    }
    public function getPrivacyPolicy()
    {
        return $this->appInfoService->getPrivacyPolicy();
    }

    public function deletePrivacyPolicy()
    {
        return $this->appInfoService->deletePrivacyPolicy();
    }

}
