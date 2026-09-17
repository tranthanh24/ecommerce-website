<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>

<body>
  <p>Nhấn vào liên kết bên dưới để xác nhận email của bạn:</p>
  <a href="{{ route('news.verify', $subscriber->verified_token) }}">Click here</a>
</body>

</html>
