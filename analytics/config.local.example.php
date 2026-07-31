<?php
// このファイルは公開root内へ配置せず、ANALYTICS_CONFIG_FILEでroot外の設定を指定してください。
return [
    'site_name' => 'やふそ屋台村 ちょうちん横丁',
    // GA4 管理画面の「プロパティ設定」に表示される数字のみのプロパティ ID。
    'ga4_property_id' => '',
    'ga4_measurement_id' => '',

    // 公開後のサイト URL。末尾の / はどちらでも構いません。
    'site_url' => 'https://yafuso-yataimura.com/',
    'search_console_site_url' => 'https://yafuso-yataimura.com/',

    // php -r "echo password_hash('任意の強いパスワード', PASSWORD_DEFAULT), PHP_EOL;"
    'analytics_username' => '',
    'analytics_password_hash' => '',

    // 必ず公開ディレクトリの外を指定してください。
    'analytics_storage_dir' => '/home/example/.private/yafuso-analytics',
];
