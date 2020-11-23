<?php

return [
    // General error messages
    'ERROR_0001' => '入力内容に誤りがあります。',

    // A01 (Login)
    'LOGIN_T' => 'ログイン',
    'LOGIN_T_001' => 'Web情報閲覧サービス管理',
    'LOGIN_L_001' => 'サインイン', // unused
    'LOGIN_L_003' => 'ログイン状態を保存', // unused
    'LOGIN_P_001' => 'ログインID',
    'LOGIN_P_002' => 'パスワード',
    'LOGIN_B_001' => 'ログイン', // unused
    'LOGIN_E_001' => 'Login ID must not exceed 10 characters', // unused
    'LOGIN_E_002' => 'Password must not exceed 15 characters', // unused
    'LOGIN_E_003' => 'Wrong login ID/password', // unused
    'LOGIN_E_004' => 'You are not authorized for this function', // unused

    // Layout texts
    'SIDEBAR_LI_001' => 'ダッシュボード',
    'SIDEBAR_LI_002' => 'ユーザ管理',
    'SIDEBAR_LI_003' => '得意先管理',
    'SIDEBAR_LI_004' => '権限管理',
    'SIDEBAR_LI_005' => 'お知らせ管理',
    'SIDEBAR_LI_006' => '掲示板管理',
    'SIDEBAR_LI_007' => '出荷情報',
    'SIDEBAR_LI_008' => '請求情報',
    'SIDEBAR_LI_009' => 'スケジュール管理',
    'SIDEBAR_LI_010' => 'メッセージ管理',
    'MYPAGE_L_001' => 'マイページ',
    'LOGOUT_L_001' => 'ログアウト',
    'LOGOUT_L_002' => 'Log out of the site?',
    'LOGOUT_B_001' => 'はい',
    'LOGOUT_B_002' => 'いいえ',
    'FOOTER_COPYRIGHT' => 'Copyright &#169; TAKII &amp; CO.,LTD.',
    'SIMPLE_SEARCH_LABEL' => '検索',

    // Dashboard texts
    'DASHBOARD_L_001' => '請求管理',
    'DASHBOARD_L_002' => '出荷管理',

    // User list page texts
    'USER_T_001' => '登録',
    'USER_T_002' => '更新',
    'USER_B_001' => '一括削除',
    'USER_B_002' => '新期ユーザー登録',
    'USER_B_003' => 'クリア',
    'USER_B_004' => '検索',
    'USER_B_005' => 'はい',
    'USER_B_006' => 'いいえ',
    'USER_B_007' => '保存',
    'USER_B_008' => 'キャンセル',
    'USER_L_001' => '検索',
    'USER_L_012' => 'ユーザー名',
    'USER_L_003' => '編集',
    'USER_L_004' => 'ログインID',
    'USER_L_005' => 'メールアドレス',
    'USER_L_006' => '最終ログイン日時',
    'USER_L_007' => 'ユーザーステータス',
    'USER_L_008' => '得意先ID',
    'USER_L_009' => 'システム管理者フラグ',
    'USER_L_010' => '削除',
    'USER_L_011' => '詳細検索',
    'USER_L_012' => 'ユーザー名', // duplicate (USER_L_012)
    'USER_L_013' => 'ログインID',
    'USER_L_014' => 'ユーザーステータス', // duplicate (USER_L_007)
    'USER_L_015' => 'メールアドレス', // duplicate (USER_L_005)
    'USER_L_016' => 'ユーザー削除',
    'USER_L_017' => 'ユーザー名', // duplicate (USER_L_012)
    'USER_L_018' => 'ログインID',
    'USER_L_019' => '得意先ID', // duplicate (USER_L_008)
    'USER_L_020' => 'メールアドレス', // duplicate (USER_L_005)
    'USER_L_021' => 'パスワード',
    'USER_L_022' => '権限ID',
    'USER_L_023' => 'システム管理者フラグ', // duplicate (USER_L_009)
    'USER_L_024' => 'ユーザーステータス', // duplicate (USER_L_007)
    'USER_L_025' => '作成日',
    'USER_L_026' => '作成者ユーザーID',
    'USER_L_027' => 'パスワード（確認）',
    'USER_L_028' => '最終ログイン日時',
    'USER_D_001' => '管理者ユーザー',
    'USER_D_002' => '得意先ユーザー',
    'USER_DLG_001' => 'Do you want to delete user [user_id]?',
    'USER_DLG_002' => 'Do you want to delete selected user/s?',
    'USER_M_001' => 'No user selected',
    'USER_E_001' => 'User not found',
    'USER_E_002' => 'Name must be between 4 to 50 characters',
    'USER_E_003' => 'Password must be between 7 to 15 characters',
    'USER_E_004' => 'Password must be between 7 to 15 characters',
    'USER_E_005' => 'Customer ID is required for adminstrator users',
    'USER_E_006' => 'Login ID must be between 4 to 10 characters',
    'USER_E_007' => 'Email must be between 6 to 255 characters',
    'USER_E_008' => 'Passwords don\'t match',
    'USER_E_009' => 'User not found',
    'USER_FL_001' => 'User successfully deleted',
    'USER_FL_002' => 'Error occured in trying to delete user information',
    'USER_FL_003' => 'Users successfully deleted',
    'USER_FL_004' => 'Error occured trying to search for user',
    'USER_FL_005' => 'User successfully added',
    'USER_FL_006' => 'Error occured adding of new user',
    'USER_FL_007' => 'Error occured updating of user',
    'USER_FL_008' => 'User successfully updated',

    // Customer texts
    'CUSTOMER_T_001' => '新期得意先登録',
    'CUSTOMER_T_002' => '更新',
    'CUSTOMER_L_001' => '詳細検索',
    'CUSTOMER_L_002' => '得意先コード',
    'CUSTOMER_L_003' => '得意先名',
    'CUSTOMER_L_004' => '都道府県',
    'CUSTOMER_L_005' => '住所1/住所2',
    'CUSTOMER_L_006' => '電話番号',
    'CUSTOMER_L_007' => '得意先コード',
    'CUSTOMER_L_008' => '得意先名',
    'CUSTOMER_L_009' => '得意先編集',
    'CUSTOMER_L_010' => '都道府県',
    'CUSTOMER_L_011' => '住所1',
    'CUSTOMER_L_012' => '住所2',
    'CUSTOMER_L_013' => '電話番号',
    'CUSTOMER_L_014' => '売上口座区分 1',
    'CUSTOMER_L_015' => '売上口座区分 2',
    'CUSTOMER_L_016' => '売上口座区分 3',
    'CUSTOMER_L_017' => '送り先編集',
    'CUSTOMER_L_018' => '削除',
    'CUSTOMER_L_019' => '得意先コード',
    'CUSTOMER_L_020' => '得意先名（カナ）',
    'CUSTOMER_L_021' => '得意先名（漢字）',
    'CUSTOMER_L_022' => '郵便番号',
    'CUSTOMER_L_023' => '都道府県',
    'CUSTOMER_L_024' => '住所1',
    'CUSTOMER_L_025' => '住所2',
    'CUSTOMER_L_026' => '気付先漢字',
    'CUSTOMER_L_027' => 'TEL',
    'CUSTOMER_L_028' => 'FAX',
    'CUSTOMER_L_029' => '売上口座区分 1',
    'CUSTOMER_L_030' => '売上口座区分 2',
    'CUSTOMER_L_031' => '売上口座区分 3',
    'CUSTOMER_L_032' => '売上口座区分 4',
    'CUSTOMER_L_033' => '売上口座区分 5',
    'CUSTOMER_L_034' => '売上口座区分 6',
    'CUSTOMER_L_035' => '売上口座区分 7',
    'CUSTOMER_L_036' => '売上口座区分 8',
    'CUSTOMER_L_037' => '基幹システム連携フラグ',
    'CUSTOMER_E_001' => 'Code must be between 1 to 7 characters',
    'CUSTOMER_E_002' => 'Name must be between 1 to 255 characters',
    'CUSTOMER_E_003' => 'Name must be between 1 to 255 characters',
    'CUSTOMER_E_004' => 'Postal Code must be numeric and at 7 characters',
    'CUSTOMER_E_005' => 'Invalid value for Prefecture',
    'CUSTOMER_E_006' => 'Address must be between 1 to 255 characters',
    'CUSTOMER_E_007' => 'Address must be between 1 to 255 characters',
    'CUSTOMER_E_008' => 'Attention must be between1 to 255 characters',
    'CUSTOMER_E_009' => 'Tel must be between 4 to 20 characters',
    'CUSTOMER_E_010' => 'Fax must be between 4 to 20 characters',
    'CUSTOMER_E_011' => 'Sales Type 1 must be numeric and must not have more than 2 digits',
    'CUSTOMER_E_012' => 'Sales Type 2 must be numeric and must not have more than 2 digits',
    'CUSTOMER_E_013' => 'Sales Type 3 must be numeric and must not have more than 2 digits',
    'CUSTOMER_E_014' => 'Sales Type 4 must be numeric and must not have more than 2 digits',
    'CUSTOMER_E_015' => 'Sales Type 5 must be numeric and must not have more than 2 digits',
    'CUSTOMER_E_016' => 'Sales Type 6 must be numeric and must not have more than 2 digits',
    'CUSTOMER_E_017' => 'Sales Type 7 must be numeric and must not have more than 2 digits',
    'CUSTOMER_E_018' => 'Sales Type 8 must be numeric and must not have more than 2 digits',
    'CUSTOMER_E_019' => 'Invalid value for Core System Flag',
    'CUSTOMER_E_020' => 'Customer not found',
    'CUSTOMER_E_021' => 'Invalid value for Customer ID',
    'CUSTOMER_B_001' => 'クリア',
    'CUSTOMER_B_002' => '検索',
    'CUSTOMER_B_003' => '一括削除',
    'CUSTOMER_B_004' => '新期ユーザー登録',
    'CUSTOMER_B_005' => 'はい',
    'CUSTOMER_B_006' => 'いいえ',
    'CUSTOMER_B_007' => '保存',
    'CUSTOMER_B_008' => 'キャンセル',
    'CUSTOMER_FL_001' => 'Error occured trying to search for customer',
    'CUSTOMER_FL_002' => 'Customer successfully added',
    'CUSTOMER_FL_003' => 'Error occured adding of new customer',
    'CUSTOMER_FL_004' => 'Customer successfully updated',
    'CUSTOMER_FL_005' => 'Error occured updating of customer',
    'CUSTOMER_DLG_001' => 'Do you want to delete customer [customer_id]?',
    'CUSTOMER_DLG_002' => 'Do you want to delete selected customer/s?',
    'CUSTOMER_M_001' => 'No customer selected',

    // Miscellaneous texts
    'DATA_TABLE_EMPTY_TEXT' => 'データはありません。',
    'DATA_TABLE_INFO_TEXT' => ' _TOTAL_ 件中 _START_ から _END_ まで表示',
    'DATA_TABLE_INFO_EMPTY_TEXT' => ' 0 件中 0 から 0 まで表示',
    'DATA_TABLE_LENGTH_TEXT' => '教示件数 _MENU_',
    'DATA_TABLE_PAGINATE_FIRST' => '先頭',
    'DATA_TABLE_PAGINATE_PREVIOUS' => '前',
    'DATA_TABLE_PAGINATE_NEXT' => '次',
    'DATA_TABLE_PAGINATE_LAST' => '最終',
];
