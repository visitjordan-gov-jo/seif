<?php
// -------------------------------------------------
// 1) الحصول على الـ User Agent (نوع المتصفح / النظام)
// -------------------------------------------------
$userAgent = $_SERVER['HTTP_USER_AGENT'];

// -------------------------------------------------
// 2) الحصول على IP الزائر
// -------------------------------------------------
$ip = $_SERVER['REMOTE_ADDR'];

// -------------------------------------------------
// 3) جلب الموقع الجغرافي عبر API خارجي (ip-api.com)
// -------------------------------------------------
$apiUrl = "http://ip-api.com/json/{$ip}?fields=country,regionName,city,query,status,message";

// استخدام @ لمنع ظهور الأخطاء في حال تعذر الاتصال
$response = @file_get_contents($apiUrl);

if ($response) {
    $data = json_decode($response, true);

    // التأكد من نجاح الطلب
    if (isset($data['status']) && $data['status'] === 'success') {
        $country = $data['country'] ?? 'غير معروف';
        $region = $data['regionName'] ?? 'غير معروف';
        $city = $data['city'] ?? 'غير معروف';
        $ipFromApi = $data['query'] ?? $ip; // قد يكون مفيدًا لو أردت طباعته أيضًا
    } else {
        // فشل في استرجاع بيانات الموقع
        $country = 'غير معروف';
        $region = 'غير معروف';
        $city = 'غير معروف';
        $ipFromApi = $ip;
    }
} else {
    // تعذّر الاتصال بالخدمة الخارجية
    $country = 'غير معروف';
    $region = 'غير معروف';
    $city = 'غير معروف';
    $ipFromApi = $ip;
}

?>
<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <title>معلومات عن الزائر</title>
    <style>
        body {
            margin: 20px;
            font-family: sans-serif;
            direction: rtl;
        }
    </style>
</head>

<body>
    <h1>مرحبًا بك!</h1>
    <p>
        <strong>عنوان الـ IP الخاص بك:</strong> <?= htmlspecialchars($ipFromApi, ENT_QUOTES, 'UTF-8'); ?>
    </p>
    <p>
        <strong>نوع المتصفح (User Agent):</strong> <?= htmlspecialchars($userAgent, ENT_QUOTES, 'UTF-8'); ?>
    </p>
    <p>
        <strong>موقعك الجغرافي (تقريبي):</strong>
        البلد: <?= htmlspecialchars($country, ENT_QUOTES, 'UTF-8'); ?> ،
        المنطقة: <?= htmlspecialchars($region, ENT_QUOTES, 'UTF-8'); ?> ،
        المدينة: <?= htmlspecialchars($city, ENT_QUOTES, 'UTF-8'); ?>
    </p>
</body>

</html>
