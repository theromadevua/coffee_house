<!DOCTYPE html>
<html>
<head>
    <title>Google Login Callback</title>
    <meta charset="UTF-8">
</head>
<body>
<script type="text/javascript">
    window.opener.postMessage({
        type: 'google_login_success',
        access_token: @js($accessToken),
        refresh_token: @js($refreshToken),
        token_type: @js($tokenType),
        expires_in: @js($expiresIn)
    }, @js($frontendUrl));
    window.close();
</script>
</body>
</html>