<?php
class UpgradeApi extends Api
{
    public function getVersion()
    {
        $versionInfo = array(
                                'version_code' => 204, //2=ts2,04=第四个版本
                                'upgrade_tips' => '有最新的软件包哦，亲快下载吧~',
                                'download_url' => 'http://hujl.sinaapp.com/ThinkSNS_Android_v1.0.apk',
                                'must_upgrade' => 1,
                            );
        return $versionInfo;
    }
}