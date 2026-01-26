<?php
header("Content-Type:text/html;charset=utf-8");
date_default_timezone_set('Asia/Tokyo');

//---------------------------　必須設定　-----------------------

// サイトのトップページURL
$site_top = "./";

// 管理者のメールアドレス（送信先）
$to = "info@stock-sun.com";

// 送信元メールアドレス
$from = "StockSun Academia <info@stock-sun.com>";

// フォームのメールアドレス入力箇所のname属性の値
$Email = "メールアドレス";

// 送信完了後に移動するページURL
$thanksPage = "./thanks.html";

// GAS Webhook URL（スプレッドシート連携用）
$gas_webhook_url = "https://script.google.com/macros/s/AKfycbzuR5PevtOJ4e8wDMpFCtWIwgAFv-hTJ89xWrBYCKhq2GgZOsOG0tt1uA-eqpnddGAmeQ/exec";
$secret_token = "";

//----------------------------------------------------------------------
//  メイン処理
//----------------------------------------------------------------------

$encode = "UTF-8";
$errm = '';
$empty_flag = 0;

// POST データ取得
$company = isset($_POST['会社名']) ? htmlspecialchars($_POST['会社名'], ENT_QUOTES, $encode) : '';
$name = isset($_POST['お名前']) ? htmlspecialchars($_POST['お名前'], ENT_QUOTES, $encode) : '';
$email = isset($_POST['メールアドレス']) ? htmlspecialchars($_POST['メールアドレス'], ENT_QUOTES, $encode) : '';
$tel = isset($_POST['電話番号']) ? htmlspecialchars($_POST['電話番号'], ENT_QUOTES, $encode) : '';
$purpose = isset($_POST['ご用件']) ? $_POST['ご用件'] : array();
$message = isset($_POST['ご質問・ご相談内容']) ? htmlspecialchars($_POST['ご質問・ご相談内容'], ENT_QUOTES, $encode) : '';
$lp_type = isset($_POST['LP種別']) ? htmlspecialchars($_POST['LP種別'], ENT_QUOTES, $encode) : '通常LP';

// 必須チェック
if (empty($company) || empty($name) || empty($email) || empty($tel) || empty($purpose)) {
    $errm = "必須項目が入力されていません。";
    $empty_flag = 1;
}

// メールアドレス形式チェック
if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errm = "メールアドレスの形式が正しくありません。";
    $empty_flag = 1;
}

if ($empty_flag == 0) {

    // ご用件を文字列に変換
    $purpose_str = implode(', ', $purpose);

    // 資料ダウンロードが選択されているかチェック
    $has_download = in_array('資料ダウンロード', $purpose);
    // 無料相談が選択されているかチェック
    $has_consultation = in_array('無料相談', $purpose);

    //----------------------------------------------------------------------
    //  ユーザー宛メール送信
    //----------------------------------------------------------------------

    if ($has_download && !$has_consultation) {
        // 資料ダウンロードのみ
        $user_subject = "【StockSun】資料ダウンロードリンクをお送りします";
        $user_body = <<<MAIL
{$name} 様

StockSun Academiaにお問い合わせいただきありがとうございます。

資料ダウンロードのご請求をいただきましたので、
下記URLよりダウンロードをお願いいたします。

▼資料ダウンロードはこちら
https://drive.google.com/file/d/1xGoEt4uRj-anX7_Ht-eDoZx8R0LkIj9n/view?usp=sharing

ご不明点がございましたら、お気軽にお問い合わせください。

━━━━━━━━━━━━━━━━━━
StockSun Academia サポート事務局
https://stock-sun.com/
━━━━━━━━━━━━━━━━━━
MAIL;
    } elseif ($has_consultation && !$has_download) {
        // 無料相談のみ
        $user_subject = "【StockSun】無料相談のご希望ありがとうございます。";
        $user_body = <<<MAIL
{$name} 様

StockSun Academiaにお問い合わせいただきありがとうございます。

無料相談のご希望をいただきましたので、
担当者より2営業日以内にご連絡させていただきます。

【お問い合わせ内容】
会社名：{$company}
お名前：{$name}
メールアドレス：{$email}
電話番号：{$tel}
ご用件：{$purpose_str}
ご質問・ご相談内容：
{$message}

ご不明点がございましたら、お気軽にお問い合わせください。

━━━━━━━━━━━━━━━━━━
StockSun Academia サポート事務局
https://stock-sun.com/
━━━━━━━━━━━━━━━━━━
MAIL;
    } else {
        // 両方選択された場合
        $user_subject = "【StockSun】お問い合わせありがとうございます";
        $user_body = <<<MAIL
{$name} 様

StockSun Academiaにお問い合わせいただきありがとうございます。

資料ダウンロードと無料相談のご希望をいただきました。

▼資料ダウンロードはこちら
https://drive.google.com/file/d/1xGoEt4uRj-anX7_Ht-eDoZx8R0LkIj9n/view?usp=sharing

無料相談については、担当者より2営業日以内にご連絡させていただきます。

【お問い合わせ内容】
会社名：{$company}
お名前：{$name}
メールアドレス：{$email}
電話番号：{$tel}
ご用件：{$purpose_str}
ご質問・ご相談内容：
{$message}

ご不明点がございましたら、お気軽にお問い合わせください。

━━━━━━━━━━━━━━━━━━
StockSun Academia サポート事務局
https://stock-sun.com/
━━━━━━━━━━━━━━━━━━
MAIL;
    }

    // ユーザー宛メール送信
    $user_headers = "From: " . $from . "\r\n";
    $user_headers .= "Reply-To: " . $from . "\r\n";
    $user_headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $user_subject_encoded = "=?UTF-8?B?" . base64_encode($user_subject) . "?=";
    mail($email, $user_subject_encoded, $user_body, $user_headers);

    //----------------------------------------------------------------------
    //  管理者宛メール送信
    //----------------------------------------------------------------------

    $admin_subject = "【SS Academia】新規お問い合わせ";
    $admin_body = <<<MAIL
StockSun Academiaより新規お問い合わせがありました。

-------------------------------
LP種別：{$lp_type}
会社名：{$company}
お名前：{$name}
メールアドレス：{$email}
電話番号：{$tel}
ご用件：{$purpose_str}
ご質問・ご相談内容：
{$message}
-------------------------------

送信日時：{$_SERVER['REQUEST_TIME']}
MAIL;
    $admin_body = str_replace('{$_SERVER[\'REQUEST_TIME\']}', date('Y/m/d H:i:s'), $admin_body);

    $admin_headers = "From: " . $from . "\r\n";
    $admin_headers .= "Reply-To: " . $email . "\r\n";
    $admin_headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $admin_subject_encoded = "=?UTF-8?B?" . base64_encode($admin_subject) . "?=";
    mail($to, $admin_subject_encoded, $admin_body, $admin_headers);

    //----------------------------------------------------------------------
    //  GAS連携（スプレッドシート記録）
    //----------------------------------------------------------------------

    $post_data_for_gas = array(
        'submitted_at' => date('Y-m-d H:i:s'),
        'company' => $company,
        'name' => $name,
        'email' => $email,
        'phone' => "'" . $tel,
        'qualification' => $lp_type,
        'request_type' => $purpose_str,
        'message' => $message
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $gas_webhook_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data_for_gas));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

    $response_gas = curl_exec($ch);
    $curl_error_gas = curl_error($ch);
    curl_close($ch);

    if ($curl_error_gas) {
        error_log("GAS cURL Error: " . $curl_error_gas);
    }

    // サンクスページへリダイレクト
    header("Location: " . $thanksPage);
    exit;

} else {
    // エラー時の処理
    ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>エラー - StockSun Academia</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 50px; }
        .error { color: red; margin: 20px 0; }
        .back-btn { display: inline-block; padding: 10px 20px; background: #333; color: #fff; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>入力エラー</h1>
    <p class="error"><?php echo $errm; ?></p>
    <p>お手数ですが、前のページに戻って入力内容をご確認ください。</p>
    <a href="javascript:history.back()" class="back-btn">前のページに戻る</a>
</body>
</html>
<?php
}
?>
