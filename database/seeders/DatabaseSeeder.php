<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Discussion;
use App\Models\Media;
use App\Models\PortfolioProject;
use App\Models\Post;
use App\Models\DiscussionReply;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Non-destructive: safe to re-run on a live database without touching edited content or passwords.
        $admin = User::query()->where('email', env('ADMIN_EMAIL', 'admin@rennova.am'))->first();
        if (! $admin) {
            $password = env('ADMIN_PASSWORD') ?: (app()->isProduction() ? Str::random(16) : 'password');
            $admin = User::query()->create([
                'email' => env('ADMIN_EMAIL', 'admin@rennova.am'),
                'name' => 'Администратор Rennova',
                'password' => $password,
            ]);
            $admin->forceFill(['role' => 'admin'])->save();
            $this->command?->info("Админ: {$admin->email} / {$password}");
        }

        foreach ($this->services() as $i => $service) {
            $model = Service::query()->firstOrCreate(['slug' => $service['slug']], $service + ['sort' => $i]);
            if (empty($model->translations) && isset(ContentTranslations::SERVICES[$model->slug])) {
                $model->update(['translations' => ContentTranslations::SERVICES[$model->slug]]);
            }
        }

        foreach ($this->brands() as $i => $brand) {
            $model = Brand::query()->firstOrCreate(
                ['slug' => Str::slug($brand['name'])],
                $brand + ['sort' => $i, 'is_active' => true],
            );
            if (empty($model->translations) && isset(ContentTranslations::BRANDS[$model->slug])) {
                $model->update(['translations' => ContentTranslations::BRANDS[$model->slug]]);
            }
        }

        foreach (Setting::FIELDS as $key => [, $default]) {
            Setting::query()->firstOrCreate(['key' => $key], ['value' => $default]);
        }

        foreach (GrowthContent::PRODUCTS as $brandSlug => $rows) {
            $brand = Brand::query()->where('slug', $brandSlug)->first();
            if (! $brand || $brand->products()->exists()) {
                continue;
            }
            foreach ($rows as $i => $row) {
                [$attrs, $translations] = GrowthContent::product($row);
                $brand->products()->create($attrs + [
                    'slug' => Str::slug($translations['en']['name']),
                    'translations' => $translations,
                    'is_active' => true,
                    'sort' => $i,
                ]);
            }
        }

        foreach (GrowthContent::PLACEHOLDER_SETTINGS as $key => $value) {
            if (blank(Setting::query()->where('key', $key)->value('value'))) {
                Setting::put($key, $value);
            }
        }

        foreach (GrowthContent::DEMO_PROJECTS as $i => $demo) {
            if (PortfolioProject::query()->where('slug', $demo['slug'])->exists()) {
                continue;
            }
            $image = fn (string $side) => Media::storeBytes(file_get_contents(__DIR__."/demo/{$demo['image']}-{$side}.jpg"))->url();
            PortfolioProject::query()->create($demo['ru'] + [
                'slug' => $demo['slug'],
                'category' => $demo['category'],
                'service_id' => Service::query()->where('slug', $demo['service'])->value('id'),
                'landmark' => $demo['landmark'],
                'year' => $demo['year'],
                'area' => $demo['area'],
                'before_image' => $image('before'),
                'after_image' => $image('after'),
                'translations' => ['en' => $demo['en'], 'hy' => $demo['hy']],
                'is_published' => true,
                'is_featured' => true,
                'sort' => $i,
            ]);
        }

        foreach (GrowthContent::POSTS as $post) {
            Post::query()->firstOrCreate(['slug' => $post['slug']], $post['ru'] + [
                'category' => $post['category'],
                'landmark' => $post['landmark'],
                'author_id' => $admin->id,
                'published_at' => now()->subDays($post['days_ago'])->setTime(10, 0),
                'translations' => ['en' => $post['en'], 'hy' => $post['hy']],
            ]);
        }

        if (Discussion::query()->doesntExist()) {
            $this->discussions($admin);
        }
    }

    private function services(): array
    {
        return [
            [
                'slug' => 'rennova-complete', 'title' => 'Rennova Complete', 'category' => 'bundle', 'is_bundle' => true,
                'landmark' => 'burj',
                'excerpt' => 'Полный цикл под ключ: архитектура, дизайн, ремонт, комплектация и финальный клининг. Один договор, одна команда, один срок.',
                'description' => "Мы берём проект целиком: от обмеров и архитектурной концепции до расстановки мебели и уборки перед заселением.\n\nВы получаете персонального менеджера, единую смету и график работ, а мы отвечаем за результат на каждом этапе.",
                'features' => ['Архитектурная концепция и планировка', 'Дизайн-проект с 3D-визуализацией', 'Ремонт под ключ с авторским надзором', 'Комплектация эксклюзивными брендами', 'Финальный клининг перед заселением'],
                'price_from' => 95000, 'price_unit' => 'м²',
            ],
            [
                'slug' => 'arkhitekturnoe-proektirovanie', 'title' => 'Архитектурное проектирование', 'category' => 'architecture',
                'landmark' => 'empire',
                'excerpt' => 'Частные дома, коммерческие объекты и реконструкция: от эскиза до рабочей документации.',
                'description' => 'Разрабатываем концепцию, эскизный и рабочий проект, сопровождаем согласования и строительство.',
                'features' => ['Анализ участка и концепция', 'Эскизный проект', 'Рабочая документация', 'Сопровождение строительства'],
                'price_from' => 12000, 'price_unit' => 'м²',
            ],
            [
                'slug' => 'rekonstruktsiya-fasadov', 'title' => 'Реконструкция и фасады', 'category' => 'architecture',
                'landmark' => 'colosseum',
                'excerpt' => 'Обновляем облик зданий, сохраняя их характер: фасадные решения, перепланировка, усиление конструкций.',
                'description' => 'Обследуем здание, предлагаем варианты реконструкции и ведём проект до сдачи.',
                'features' => ['Обследование конструкций', 'Фасадные решения', 'Перепланировка', 'Согласования'],
                'price_from' => 8000, 'price_unit' => 'м²',
            ],
            [
                'slug' => 'dizain-proekt-interera', 'title' => 'Дизайн-проект интерьера', 'category' => 'design',
                'landmark' => 'opera',
                'excerpt' => 'Интерьер, в котором продумана каждая деталь: планировка, свет, материалы и мебель.',
                'description' => 'Создаём полный дизайн-проект с фотореалистичной визуализацией и комплектом чертежей для строителей.',
                'features' => ['Обмеры и планировочные решения', '3D-визуализация', 'Чертежи для строителей', 'Подбор материалов и мебели'],
                'price_from' => 9000, 'price_unit' => 'м²',
            ],
            [
                'slug' => 'avtorskii-nadzor', 'title' => 'Авторский надзор', 'category' => 'design',
                'landmark' => 'louvre',
                'excerpt' => 'Дизайнер контролирует, чтобы результат совпал с проектом до миллиметра.',
                'description' => 'Регулярные выезды на объект, контроль закупок и ответы на вопросы бригады.',
                'features' => ['Выезды на объект', 'Контроль закупок', 'Корректировки проекта'],
                'price_from' => 150000, 'price_unit' => 'месяц',
            ],
            [
                'slug' => 'remont-pod-klyuch', 'title' => 'Ремонт под ключ', 'category' => 'renovation',
                'landmark' => 'eiffel',
                'excerpt' => 'Черновые и чистовые работы, инженерия и отделка с фиксированной сметой и сроком.',
                'description' => 'Собственные бригады, технадзор и прозрачная отчётность на каждом этапе.',
                'features' => ['Демонтаж и черновые работы', 'Электрика и сантехника', 'Чистовая отделка', 'Гарантия на работы'],
                'price_from' => 45000, 'price_unit' => 'м²',
            ],
            [
                'slug' => 'kosmeticheskii-remont', 'title' => 'Косметический ремонт', 'category' => 'renovation',
                'landmark' => 'bigben',
                'excerpt' => 'Быстро освежить пространство: стены, полы, свет и детали без масштабной стройки.',
                'description' => 'Идеально перед продажей, сдачей в аренду или просто для нового настроения.',
                'features' => ['Покраска и обои', 'Замена напольных покрытий', 'Обновление освещения'],
                'price_from' => 18000, 'price_unit' => 'м²',
            ],
            [
                'slug' => 'uborka-posle-remonta', 'title' => 'Уборка после ремонта', 'category' => 'cleaning',
                'landmark' => 'taj',
                'excerpt' => 'Удаляем строительную пыль, следы клея и краски, чтобы можно было заезжать в тот же день.',
                'description' => 'Профессиональная химия и оборудование, бережное отношение к новым поверхностям.',
                'features' => ['Удаление строительной пыли', 'Мойка окон и фасадов', 'Очистка всех поверхностей'],
                'price_from' => 1200, 'price_unit' => 'м²',
            ],
            [
                'slug' => 'generalnaya-uborka', 'title' => 'Генеральная и регулярная уборка', 'category' => 'cleaning',
                'landmark' => 'opera',
                'excerpt' => 'Квартиры, дома и офисы: разовая генеральная уборка или регулярное обслуживание.',
                'description' => 'Составляем чек-лист под ваш объект и работаем по нему каждый визит.',
                'features' => ['Кухня и санузлы', 'Химчистка мебели', 'Регулярный график'],
                'price_from' => 800, 'price_unit' => 'м²',
            ],
        ];
    }

    /** Placeholder brands — replace with the real partner brands in the admin panel. */
    private function brands(): array
    {
        return [
            ['name' => 'Atelier Nord', 'country' => 'Дания', 'category' => 'Мебель', 'tagline' => 'Скандинавская сдержанность', 'description' => 'Мебель из массива дуба и ясеня ручной работы.', 'is_exclusive' => true, 'is_featured' => true],
            ['name' => 'Pietra Viva', 'country' => 'Италия', 'category' => 'Натуральный камень', 'tagline' => 'Мрамор из Каррары', 'description' => 'Слэбы, мозаика и столешницы из редких пород камня.', 'is_exclusive' => true, 'is_featured' => true],
            ['name' => 'Lumen Haus', 'country' => 'Германия', 'category' => 'Освещение', 'tagline' => 'Архитектурный свет', 'description' => 'Трековые системы и дизайнерские светильники.', 'is_exclusive' => true, 'is_featured' => true],
            ['name' => 'Aqua Forma', 'country' => 'Испания', 'category' => 'Сантехника', 'tagline' => 'Форма воды', 'description' => 'Смесители и керамика для ванных комнат премиум-класса.', 'is_exclusive' => false, 'is_featured' => true],
            ['name' => 'Tessuto', 'country' => 'Италия', 'category' => 'Текстиль', 'tagline' => 'Ткани для интерьера', 'description' => 'Портьерные и мебельные ткани, ковры ручной работы.', 'is_exclusive' => true, 'is_featured' => false],
            ['name' => 'Kronhaus', 'country' => 'Австрия', 'category' => 'Отделочные материалы', 'tagline' => 'Паркет и панели', 'description' => 'Инженерная доска и стеновые панели из натурального шпона.', 'is_exclusive' => false, 'is_featured' => false],
            ['name' => 'Ararat Stone', 'country' => 'Армения', 'category' => 'Натуральный камень', 'tagline' => 'Армянский туф и базальт', 'description' => 'Местный камень для фасадов и интерьеров.', 'is_exclusive' => true, 'is_featured' => true],
            ['name' => 'Smartline', 'country' => 'Швейцария', 'category' => 'Умный дом', 'tagline' => 'Тихая автоматизация', 'description' => 'Системы управления светом, климатом и безопасностью.', 'is_exclusive' => false, 'is_featured' => false],
        ];
    }

    private function discussions(User $admin): void
    {
        $member = User::query()->firstOrCreate(
            ['email' => 'anna@example.com'],
            ['name' => 'Анна Саргсян', 'password' => Str::random(24)],
        );

        $topics = [
            ['design', 'Какой оттенок зелёного выбрать для гостиной?', 'Хочу оливковую стену за диваном, но боюсь, что комната станет темнее. Окна на север. Есть ли удачные примеры?', $member,
                ['Для северной стороны лучше брать тёплый оливковый с жёлтым подтоном и добавить светлый потолок. Можем подобрать образцы на объекте.']],
            ['architecture', 'Панорамное остекление в частном доме: плюсы и минусы', 'Планируем дом под Ереваном. Думаем о панорамных окнах в гостиной. Как быть с летней жарой?', $member,
                ['Помогают глубокие свесы кровли и солнцезащитное стекло. Ориентация фасада важнее площади стекла.']],
            ['renovation', 'Сроки ремонта в новостройке 80 м²', 'Сколько по времени занимает ремонт под ключ, если дизайн-проект уже готов?', $admin, []],
        ];

        foreach ($topics as [$category, $title, $body, $author, $replies]) {
            $d = Discussion::query()->create([
                'user_id' => $author->id, 'category' => $category, 'title' => $title,
                'body' => $body, 'last_activity_at' => now(),
            ]);
            foreach ($replies as $reply) {
                DiscussionReply::query()->create(['discussion_id' => $d->id, 'user_id' => $admin->id, 'body' => $reply]);
            }
        }
    }
}
