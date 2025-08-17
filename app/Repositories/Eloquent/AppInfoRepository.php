<?php

namespace App\Repositories\Eloquent;

use App\Models\AppInfo as AppInfoModel;
use App\Repositories\interfaces\AppInfoRepositoryInterface;
use App\Types\AppInfoTypes;

class AppInfoRepository implements AppInfoRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function createAppInfo(array $data) : AppInfoModel
    {
        $appInfo=AppInfoModel::create($data);
        return $appInfo;
    }

    public function updateAppInfo(AppInfoModel $appInfo, array $data): AppInfoModel
    {
        $appInfo->update($data);
        $appInfo->save();
        return $appInfo;
    }

    public function find(string $type): ?AppInfoModel
    {
        $appInfo=AppInfoModel::where('type',$type)->first();
        return $appInfo;
    }

    public function delete(AppInfoModel $appInfo): bool
    {
        return $appInfo->delete();
    }
}
