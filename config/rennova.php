<?php

return [
    // Site languages: code => switcher label. Russian is the source language.
    'locales' => [
        'hy' => 'Հայ',
        'ru' => 'Рус',
        'en' => 'Eng',
    ],

    // Fallbacks for the social links; the admin panel settings take precedence.
    'facebook_url' => env('FACEBOOK_URL', ''),
    'instagram_url' => env('INSTAGRAM_URL', ''),

    // One-time setup route: /setup/{token} runs migrations and seeds when SETUP_TOKEN is set.
    'setup_token' => env('SETUP_TOKEN'),

    // Cost calculator. Base rates per m² come from the services' "price from" (editable in the admin);
    // these multipliers and extras refine the estimate. Labels are Russian source strings.
    'calculator' => [
        'property' => [
            'apartment' => ['Квартира', 1.0],
            'house' => ['Частный дом', 1.15],
            'office' => ['Офис или коммерция', 1.1],
        ],
        'condition' => [
            'new' => ['Новостройка без отделки', 1.0],
            'secondary' => ['Вторичное жильё', 1.2],
            'good' => ['Хорошее состояние, освежить', 0.85],
        ],
        'extras' => [
            // key => [label, price per m², categories it applies to]
            'demolition' => ['Демонтаж старой отделки', 3500, ['renovation', 'bundle']],
            'electrics' => ['Замена электрики', 6000, ['renovation', 'bundle']],
            'plumbing' => ['Замена сантехники и труб', 5000, ['renovation', 'bundle']],
            'floor_heating' => ['Тёплый пол', 7000, ['renovation', 'bundle']],
            'visualization' => ['3D-визуализация всех помещений', 2500, ['design']],
            'supervision' => ['Авторский надзор', 3000, ['design', 'architecture']],
            'windows' => ['Мойка окон и фасадного остекления', 400, ['cleaning']],
            'furniture_cleaning' => ['Химчистка мебели и ковров', 350, ['cleaning']],
        ],
        'urgent' => 1.2,   // "Срочно" multiplier
        'spread' => 1.25,  // upper bound of the range = estimate × spread
        'min_area' => 5,
        'max_area' => 5000,
    ],

    // Consultation booking.
    'booking' => [
        'timezone' => 'Asia/Yerevan',
        'days_ahead' => 21,
        'weekdays' => [1, 2, 3, 4, 5, 6], // ISO: 1 = Monday … 7 = Sunday
        'times' => ['10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'],
    ],

    // Landmarks used as design motifs across the site.
    'landmarks' => [
        'eiffel' => ['Эйфелева башня', 'Париж', 1889, 'Кружево из 18 000 металлических деталей: инженерия как искусство.'],
        'burj' => ['Бурдж-Халифа', 'Дубай', 2010, 'Ступенчатая форма, вдохновлённая цветком пустыни, гасит ветровые нагрузки.'],
        'opera' => ['Сиднейский оперный театр', 'Сидней', 1973, 'Паруса-оболочки, собранные из сегментов одной сферы.'],
        'colosseum' => ['Колизей', 'Рим', '80 н. э.', 'Ритм арок, который определил облик стадионов на два тысячелетия вперёд.'],
        'taj' => ['Тадж-Махал', 'Агра', 1653, 'Идеальная симметрия и игра света на белом мраморе.'],
        'empire' => ['Эмпайр-стейт-билдинг', 'Нью-Йорк', 1931, 'Эталон ар-деко: уступы, вертикали и точность.'],
        'louvre' => ['Пирамида Лувра', 'Париж', 1989, 'Прозрачная геометрия, соединяющая классику и модернизм.'],
        'bigben' => ['Биг-Бен', 'Лондон', 1859, 'Неоготика, в которой каждая деталь работает на вертикаль.'],
        'cascade' => ['Каскад', 'Ереван', 1980, 'Террасы из армянского туфа, поднимающие город к небу.'],
    ],
];
