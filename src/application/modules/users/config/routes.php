<?php

return [
    #Авторизация, регистрация, восстановление и подтверждение учетных записей
    '<action:(login|logout|register)>'  => 'users/auth/<action>',
    'recovery'                          => 'users/recovery',
    'recovery/<action>'                 => 'users/recovery/<action>',
    'verification'                      => 'users/verification',
    'verification/<action>'             => 'users/verification/<action>',
    'profile'                           => 'users/profile'
];