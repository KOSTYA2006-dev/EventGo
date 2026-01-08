<?php

return [
    // Банковские реквизиты для СБП
    'vtb_account' => env('VTB_ACCOUNT', '40802810506640007313'),
    'vtb_bik' => env('VTB_BIK', '044525411'),
    'vtb_inn' => env('VTB_INN', '616404172802'),
    'vtb_kpp' => env('VTB_KPP', ''),
    'vtb_correspondent_account' => env('VTB_CORRESPONDENT_ACCOUNT', '30101810145250000411'),
    'recipient_name' => env('RECIPIENT_NAME', 'ИП ЛАЗАРЕВА СВЕТЛАНА ИГОРЕВНА'),
    'bank_name' => env('BANK_NAME', 'ФИЛИАЛ "ЦЕНТРАЛЬНЫЙ" БАНКА ВТБ (ПАО)'),
    'ogrnip' => env('OGRNIP', '316616400101234'),
    
    // Настройки ЮKassa
    'yookassa' => [
        'shop_id' => env('YOOKASSA_SHOP_ID'),
        'secret_key' => env('YOOKASSA_SECRET_KEY'),
        'test_mode' => env('YOOKASSA_TEST_MODE', true),
    ],
    
    // Использовать ЮKassa для оплаты (true) или ручную оплату (false)
    'use_yookassa' => env('USE_YOOKASSA', true),
];

