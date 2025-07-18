<?php

namespace App\Repositories\interfaces;
use App\Models\AppInfo as AppInfoModel;

interface AppInfoRepositoryInterface
{
    public function createAppInfo(array $data) : AppInfoModel;

    public function updateAppInfo(AppInfoModel $appInfo,array $data) : AppInfoModel;

    public function find(string $id) : AppInfoModel;

    public function delete(AppInfoModel $appInfo) : bool;
}
