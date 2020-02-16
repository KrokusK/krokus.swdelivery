<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => getenv('SUPPORT_EMAIL'),
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => getenv('PASSWORD_RESET_TOKEN_EXPIRE'),
    'user.verificationEmailTokenExpire' => getenv('VERIFICATION_EMAIL_TOKEN_EXPIRE'),
];
