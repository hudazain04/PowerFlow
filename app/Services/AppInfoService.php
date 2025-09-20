<?php

namespace App\Services;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\DTOs\AppInfoDTO;
use App\Exceptions\ErrorException;
use App\Http\Requests\AppInfo\CreateAppInfoRequest;
use App\Http\Resources\AppInfoResource;
use App\Repositories\interfaces\AppInfoRepositoryInterface;
use App\Types\AppInfoTypes;

class AppInfoService
{
    use ApiResponse;
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected AppInfoRepositoryInterface $appInfoRepository,
    )
    {
        //
    }

    public function createAboutApp(AppInfoDTO $appInfoDTO)
    {
        $appInfo=$this->appInfoRepository->find(AppInfoTypes::AboutApp);
        if ($appInfo)
        {
           return $this->updateAboutApp($appInfoDTO);
        }
        $appInfoDTO->type=AppInfoTypes::AboutApp;
        $appInfo=$this->appInfoRepository->createAppInfo($appInfoDTO->toArray());
        $appInfoDTO=AppInfoDTO::fromModel($appInfo);
        return $this->success(AppInfoResource::make($appInfo),__('AppInfo.AboutAppCreate'),ApiCode::CREATED);

    }

    public function getAboutApp()
    {
        $appInfo=$this->appInfoRepository->find(AppInfoTypes::AboutApp);
        if (! $appInfo)
        {
            throw new ErrorException(__('AppInfo.AboutAppNotFound'),ApiCode::NOT_FOUND);
        }
        $appInfoDTO=AppInfoDTO::fromModel($appInfo);
        return $this->success(AppInfoResource::make($appInfoDTO),__('messages.success'));
    }

    public function updateAboutApp(AppInfoDTO $appInfoDTO)
    {
        $appInfo=$this->appInfoRepository->find(AppInfoTypes::AboutApp);
        if (! $appInfo)
        {
            throw new ErrorException(__('AppInfo.AboutAppNotFound'),ApiCode::NOT_FOUND);
        }
        $appInfoDTO->type=AppInfoTypes::AboutApp;
        $appInfo=$this->appInfoRepository->updateAppInfo($appInfo,$appInfoDTO->toArray());
        $appInfoDTO=AppInfoDTO::fromModel($appInfo);
        return $this->success(AppInfoResource::make($appInfoDTO),__('AppInfo.AboutAppUpdate'));
    }

    public function deleteAboutApp()
    {
        $appInfo=$this->appInfoRepository->find(AppInfoTypes::AboutApp);
        if (! $appInfo)
        {
            throw new ErrorException(__('AppInfo.AboutAppNotFound'),ApiCode::NOT_FOUND);
        }
        $this->appInfoRepository->updateAppInfo($appInfo,['content'=>null]);
        return $this->success(null,__('AppInfo.AboutAppDelete'));
    }

    public function createTermsAndConditions(AppInfoDTO $appInfoDTO)
    {
        $appInfo=$this->appInfoRepository->find(AppInfoTypes::TermsAndConditions);
        if ($appInfo)
        {
            return $this->updateTermsAndConditions($appInfoDTO);
        }
        $appInfoDTO->type=AppInfoTypes::TermsAndConditions;
        $appInfo=$this->appInfoRepository->createAppInfo($appInfoDTO->toArray());
        $appInfoDTO=AppInfoDTO::fromModel($appInfo);
        return $this->success(AppInfoResource::make($appInfoDTO),__('AppInfo.TermsAndConditionsCreate'),ApiCode::CREATED);
    }

    public function getTermsAndConditions()
    {
        $appInfo=$this->appInfoRepository->find(AppInfoTypes::TermsAndConditions);
        if (! $appInfo)
        {
            throw new ErrorException(__('AppInfo.TermsAndConditionsNotFound'),ApiCode::NOT_FOUND);
        }
//        $appInfoDTO=AppInfoDTO::fromModel($appInfo);
        return $this->success(AppInfoResource::make($appInfo),__('messages.success'));
    }

    public function updateTermsAndConditions(AppInfoDTO $appInfoDTO)
    {
        $appInfo=$this->appInfoRepository->find(AppInfoTypes::TermsAndConditions);
        if (! $appInfo)
        {
            throw new ErrorException(__('AppInfo.TermsAndConditionsNotFound'),ApiCode::NOT_FOUND);
        }
        $appInfoDTO->type=AppInfoTypes::TermsAndConditions;
        $appInfo=$this->appInfoRepository->updateAppInfo($appInfo,$appInfoDTO->toArray());
        $appInfoDTO=AppInfoDTO::fromModel($appInfo);
        return $this->success(AppInfoResource::make($appInfoDTO),__('AppInfo.TermsAndConditionsUpdate'));
    }

    public function deleteTermsAndConditions()
    {
        $appInfo=$this->appInfoRepository->find(AppInfoTypes::TermsAndConditions);
        if (! $appInfo)
        {
            throw new ErrorException(__('AppInfo.TermsAndConditionsNotFound'),ApiCode::NOT_FOUND);
        }
        $this->appInfoRepository->updateAppInfo($appInfo,['content'=>null]);
        return $this->success(null,__('AppInfo.TermsAndConditionsDelete'));
    }

    public function createPrivacyPolicy(AppInfoDTO $appInfoDTO)
    {
        $appInfo=$this->appInfoRepository->find(AppInfoTypes::PrivacyPolicy);
        if ($appInfo)
        {
            return  $this->updatePrivacyPolicy($appInfoDTO);
        }
        $appInfoDTO->type=AppInfoTypes::PrivacyPolicy;
        $appInfo=$this->appInfoRepository->createAppInfo($appInfoDTO->toArray());
        $appInfoDTO=AppInfoDTO::fromModel($appInfo);
        return $this->success(AppInfoResource::make($appInfoDTO),__('AppInfo.PrivacyPolicyCreate'),ApiCode::CREATED);
    }

    public function getPrivacyPolicy()
    {
        $appInfo=$this->appInfoRepository->find(AppInfoTypes::PrivacyPolicy);
        if (! $appInfo)
        {
            throw new ErrorException(__('AppInfo.PrivacyPolicyNotFound'),ApiCode::NOT_FOUND);
        }
        $appInfoDTO=AppInfoDTO::fromModel($appInfo);
        return $this->success(AppInfoResource::make($appInfoDTO),__('messages.success'));
    }

    public function updatePrivacyPolicy(AppInfoDTO $appInfoDTO)
    {
        $appInfo=$this->appInfoRepository->find(AppInfoTypes::PrivacyPolicy);
        if (! $appInfo)
        {
            throw new ErrorException(__('AppInfo.PrivacyPolicyNotFound'),ApiCode::NOT_FOUND);
        }
        $appInfoDTO->type=AppInfoTypes::PrivacyPolicy;
        $appInfo=$this->appInfoRepository->updateAppInfo($appInfo,$appInfoDTO->toArray());
        $appInfoDTO=AppInfoDTO::fromModel($appInfo);
        return $this->success(AppInfoResource::make($appInfoDTO),__('AppInfo.PrivacyPolicyUpdate'));
    }

    public function deletePrivacyPolicy()
    {
        $appInfo=$this->appInfoRepository->find(AppInfoTypes::PrivacyPolicy);
        if (! $appInfo)
        {
            throw new ErrorException(__('AppInfo.PrivacyPolicyNotFound'),ApiCode::NOT_FOUND);
        }
        $this->appInfoRepository->updateAppInfo($appInfo,['content'=>null]);
        return $this->success(null,__('AppInfo.PrivacyPolicyDelete'));
    }
}
