<?php

namespace Database\Seeders;

/** Starter blog articles and sample catalog items for the showcase brands (ru source + en/hy). */
class GrowthContent
{
    private const PRICE = ['ru' => 'Цена по запросу', 'en' => 'Price on request', 'hy' => 'Գինը՝ ըստ հարցման'];

    public const POSTS = [
        [
            'slug' => 'kak-splanirovat-remont-kvartiry',
            'category' => 'renovation',
            'landmark' => 'cascade',
            'days_ago' => 14,
            'ru' => [
                'title' => 'Как спланировать ремонт квартиры: 7 шагов без сюрпризов',
                'excerpt' => 'Порядок работ, бюджет с запасом и сроки: что решить до того, как в квартиру зайдут строители.',
                'body' => "Хороший ремонт начинается задолго до первого удара перфоратора. Вот порядок, который мы используем в каждом проекте.\n\n## 1. Сформулируйте задачу\nКто будет жить в квартире, как вы проводите день, что не устраивает сейчас. Эти ответы важнее выбора плитки.\n\n## 2. Сделайте обмерный план\nТочные размеры, высоты, расположение стояков и вентиляции. Без них любая смета приблизительна.\n\n## 3. Закажите дизайн-проект\nПланировка, развёртки стен, схемы света и розеток. Проект экономит больше, чем стоит: строители не импровизируют.\n\n## 4. Заложите бюджет с запасом\nДобавьте к смете 10–15% на непредвиденное. В домах вторичного фонда запас лучше увеличить.\n\n## 5. Выберите материалы заранее\nИмпортные материалы везут от 3 до 8 недель. Заказывайте их на этапе черновых работ, а не перед чистовыми.\n\n## 6. Утвердите график\nЧерновые работы, инженерия, штукатурка, стяжка, чистовая отделка, мебель. У каждого этапа должна быть дата.\n\n## 7. Не пропускайте авторский надзор\nДизайнер на объекте следит, чтобы результат совпал с проектом.\n\nХотите понять бюджет? Воспользуйтесь нашим калькулятором или запишитесь на бесплатную консультацию.",
            ],
            'en' => [
                'title' => 'How to plan an apartment renovation: 7 steps with no surprises',
                'excerpt' => 'Sequence of works, a budget with a buffer and a timeline: what to decide before the builders arrive.',
                'body' => "A good renovation starts long before the first hammer drill. Here is the order we follow in every project.\n\n## 1. Define the brief\nWho will live in the apartment, how you spend your day, what does not work now. These answers matter more than the choice of tiles.\n\n## 2. Get a measured survey\nExact dimensions, heights, the position of risers and ventilation. Without them any estimate is a guess.\n\n## 3. Order a design project\nLayout, wall elevations, lighting and socket plans. A project saves more than it costs: builders do not improvise.\n\n## 4. Plan a budget with a buffer\nAdd 10–15% to the estimate for the unexpected. In older buildings the buffer should be larger.\n\n## 5. Choose materials early\nImported materials take 3 to 8 weeks to arrive. Order them during rough works, not right before finishing.\n\n## 6. Approve the schedule\nRough works, engineering, plastering, screed, finishing, furniture. Every stage needs a date.\n\n## 7. Do not skip designer supervision\nA designer on site makes sure the result matches the project.\n\nWant to understand the budget? Try our calculator or book a free consultation.",
            ],
            'hy' => [
                'title' => 'Ինչպես պլանավորել բնակարանի վերանորոգումը՝ 7 քայլ առանց անակնկալների',
                'excerpt' => 'Աշխատանքների հերթականություն, պահուստով բյուջե և ժամկետներ․ ինչ որոշել մինչև շինարարների գալը։',
                'body' => "Լավ վերանորոգումը սկսվում է առաջին պերֆորատորից շատ առաջ։ Ահա այն հերթականությունը, որին հետևում ենք յուրաքանչյուր նախագծում։\n\n## 1. Ձևակերպեք խնդիրը\nՈվ է ապրելու բնակարանում, ինչպես եք անցկացնում օրը, ինչն է հիմա անհարմար։ Այս պատասխաններն ավելի կարևոր են, քան սալիկի ընտրությունը։\n\n## 2. Կատարեք չափագրում\nՃշգրիտ չափեր, բարձրություններ, խողովակների և օդափոխության տեղադրություն։ Առանց դրանց ցանկացած նախահաշիվ մոտավոր է։\n\n## 3. Պատվիրեք դիզայն նախագիծ\nՀատակագիծ, պատերի փռվածքներ, լուսավորության և վարդակների սխեմաներ։ Նախագիծը խնայում է ավելին, քան արժե։\n\n## 4. Նախատեսեք պահուստով բյուջե\nՆախահաշվին ավելացրեք 10–15% չնախատեսված ծախսերի համար։ Հին շենքերում պահուստը պետք է ավելի մեծ լինի։\n\n## 5. Ընտրեք նյութերը նախապես\nՆերմուծվող նյութերը բերվում են 3-ից 8 շաբաթում։ Պատվիրեք դրանք սևագրային աշխատանքների փուլում։\n\n## 6. Հաստատեք ժամանակացույցը\nՍևագրային աշխատանքներ, ինժեներիա, սվաղ, հատակի լցնում, մաքուր հարդարում, կահույք։ Յուրաքանչյուր փուլ պետք է ունենա ամսաթիվ։\n\n## 7. Մի բաց թողեք հեղինակային հսկողությունը\nԴիզայները օբյեկտում հետևում է, որ արդյունքը համապատասխանի նախագծին։\n\nՑանկանու՞մ եք հասկանալ բյուջեն։ Օգտվեք մեր հաշվիչից կամ գրանցվեք անվճար խորհրդատվության։",
            ],
        ],
        [
            'slug' => 'trendy-dizaina-interera',
            'category' => 'trends',
            'landmark' => 'louvre',
            'days_ago' => 7,
            'ru' => [
                'title' => 'Тренды дизайна интерьера: тёплый минимализм и натуральные материалы',
                'excerpt' => 'Почему интерьеры становятся спокойнее, какие оттенки выбирают и где уместен натуральный камень.',
                'body' => "Интерьер всё чаще становится местом восстановления, а не витриной. Поэтому главные тенденции связаны с тишиной и тактильностью.\n\n## Тёплый минимализм\nМеньше предметов, но каждый продуман. Вместо холодного белого выбирают оттенки кости, песка и оливы.\n\n## Натуральные материалы\nДерево с видимой текстурой, травертин, армянский туф, лён. Они стареют красиво и делают пространство живым.\n\n## Мягкий свет\nНесколько сценариев освещения вместо одной люстры: трековые системы, подсветка ниш, тёплые бра.\n\n## Скрытое хранение\nВстроенные шкафы в цвет стен и системы без ручек освобождают пространство.\n\n## Локальные акценты\nКерамика, текстиль и камень местных мастеров добавляют характер, который невозможно купить в каталоге.\n\nНаши дизайнеры помогут подобрать решения под ваш образ жизни и бюджет.",
            ],
            'en' => [
                'title' => 'Interior design trends: warm minimalism and natural materials',
                'excerpt' => 'Why interiors are getting calmer, which shades people choose and where natural stone belongs.',
                'body' => "Home is increasingly a place to recharge rather than a showroom. That is why the main trends are about calm and texture.\n\n## Warm minimalism\nFewer objects, each one considered. Instead of cold white, people choose bone, sand and olive tones.\n\n## Natural materials\nWood with visible grain, travertine, Armenian tuff, linen. They age beautifully and make a space feel alive.\n\n## Soft light\nSeveral lighting scenes instead of one chandelier: track systems, niche lighting, warm wall lamps.\n\n## Hidden storage\nBuilt-in wardrobes in the wall colour and handle-free systems free up space.\n\n## Local accents\nCeramics, textiles and stone from local makers add character you cannot buy from a catalogue.\n\nOur designers will help you find solutions that fit your lifestyle and budget.",
            ],
            'hy' => [
                'title' => 'Ինտերիերի դիզայնի միտումներ՝ ջերմ մինիմալիզմ և բնական նյութեր',
                'excerpt' => 'Ինչու են ինտերիերներն ավելի հանգիստ դառնում, ինչ երանգներ են ընտրում և որտեղ է տեղին բնական քարը։',
                'body' => "Տունն ավելի ու ավելի է դառնում վերականգնվելու վայր, այլ ոչ ցուցափեղկ։ Այդ պատճառով հիմնական միտումները կապված են լռության և շոշափելիության հետ։\n\n## Ջերմ մինիմալիզմ\nՔիչ առարկաներ, բայց յուրաքանչյուրը մտածված։ Սառը սպիտակի փոխարեն ընտրում են ոսկորի, ավազի և ձիթապտղի երանգներ։\n\n## Բնական նյութեր\nՏեսանելի կառուցվածքով փայտ, տրավերտին, հայկական տուֆ, վուշ։ Դրանք գեղեցիկ են ծերանում և տարածքը կենդանի են դարձնում։\n\n## Մեղմ լույս\nԼուսավորության մի քանի սցենար մեկ ջահի փոխարեն՝ ռելսային համակարգեր, խորշերի լուսավորում, ջերմ պատի լամպեր։\n\n## Թաքնված պահեստավորում\nՊատերի գույնով ներկառուցված պահարաններն ու առանց բռնակների համակարգերն ազատում են տարածքը։\n\n## Տեղական շեշտեր\nՏեղացի վարպետների խեցեգործությունը, տեքստիլը և քարը ավելացնում են բնավորություն, որը հնարավոր չէ գնել կատալոգից։\n\nՄեր դիզայներները կօգնեն գտնել լուծումներ՝ ըստ ձեր ապրելակերպի և բյուջեի։",
            ],
        ],
        [
            'slug' => 'uborka-posle-remonta-chek-list',
            'category' => 'cleaning',
            'landmark' => 'eiffel',
            'days_ago' => 2,
            'ru' => [
                'title' => 'Уборка после ремонта: чек-лист перед заселением',
                'excerpt' => 'Строительная пыль оседает неделями. Разбираем, что и в каком порядке нужно очистить.',
                'body' => "После ремонта в квартире остаются пыль, следы клея, затирки и краски. Обычной уборкой их не убрать.\n\n## Сверху вниз\nСначала потолки, светильники и карнизы, затем стены и откосы, в конце пол. Иначе пыль снова осядет на чистые поверхности.\n\n## Окна и профили\nСнимите защитную плёнку, очистите профили от строительных остатков, вымойте стёкла с двух сторон.\n\n## Сантехника и плитка\nСледы затирки убирают специальными средствами, чтобы не повредить эмаль и швы.\n\n## Вентиляция и радиаторы\nПыль скапливается внутри решёток и секций. Их очищают отдельно.\n\n## Мебель и техника\nПротрите фасады изнутри и снаружи, проверьте фильтры кондиционеров.\n\n## Финальная влажная уборка\nДелают через сутки, когда осядет оставшаяся взвесь.\n\nКоманда Rennova проводит такую уборку за один день и привозит профессиональное оборудование.",
            ],
            'en' => [
                'title' => 'Post-renovation cleaning: a checklist before moving in',
                'excerpt' => 'Construction dust settles for weeks. Here is what to clean and in which order.',
                'body' => "After a renovation the apartment is left with dust, traces of glue, grout and paint. Regular cleaning will not remove them.\n\n## Top to bottom\nCeilings, light fittings and cornices first, then walls and reveals, and the floor last. Otherwise dust settles back on clean surfaces.\n\n## Windows and frames\nRemove the protective film, clear the frames of building debris, wash the glass on both sides.\n\n## Plumbing and tiles\nGrout residue is removed with special products so as not to damage the enamel and joints.\n\n## Ventilation and radiators\nDust gathers inside grilles and radiator sections. They are cleaned separately.\n\n## Furniture and appliances\nWipe the fronts inside and out, and check the air conditioner filters.\n\n## Final wet cleaning\nIt is done a day later, once the remaining dust has settled.\n\nThe Rennova team does this cleaning in one day and brings professional equipment.",
            ],
            'hy' => [
                'title' => 'Մաքրում վերանորոգումից հետո․ ստուգաթերթ բնակվելուց առաջ',
                'excerpt' => 'Շինարարական փոշին նստում է շաբաթներով։ Պարզում ենք, թե ինչ և ինչ հերթականությամբ մաքրել։',
                'body' => "Վերանորոգումից հետո բնակարանում մնում են փոշի, սոսնձի, ֆուգայի և ներկի հետքեր։ Սովորական մաքրությամբ դրանք չեն վերանում։\n\n## Վերևից ներքև\nՆախ առաստաղները, լուսատուները և վարագուրաձողերը, հետո պատերը և լանջերը, վերջում հատակը։ Հակառակ դեպքում փոշին նորից կնստի մաքուր մակերեսներին։\n\n## Պատուհաններ և պրոֆիլներ\nՀանեք պաշտպանիչ թաղանթը, մաքրեք պրոֆիլները շինարարական մնացորդներից, լվացեք ապակիները երկու կողմից։\n\n## Սանտեխնիկա և սալիկ\nՖուգայի հետքերը հեռացնում են հատուկ միջոցներով, որպեսզի չվնասեն էմալը և կարերը։\n\n## Օդափոխություն և ռադիատորներ\nՓոշին կուտակվում է ցանցերի և սեկցիաների ներսում։ Դրանք մաքրում են առանձին։\n\n## Կահույք և տեխնիկա\nՍրբեք ճակատները ներսից և դրսից, ստուգեք օդորակիչների ֆիլտրերը։\n\n## Վերջնական խոնավ մաքրում\nԿատարվում է մեկ օր անց, երբ մնացած փոշին նստի։\n\nRennova-ի թիմը նման մաքրում կատարում է մեկ օրում և բերում է պրոֆեսիոնալ սարքավորումներ։",
            ],
        ],
    ];


    /** Placeholder settings: filled only while the setting is still empty. */
    public const PLACEHOLDER_SETTINGS = [
        'whatsapp' => '+374 00 000 000',
        'telegram' => '+374 00 000 000',
        'viber' => '+374 00 000 000',
    ];

    /** Demo portfolio projects with generated before/after pictures (database/seeders/demo). */
    public const DEMO_PROJECTS = [
        [
            'slug' => 'demo-kvartira-kentron', 'category' => 'renovation', 'service' => 'remont-pod-klyuch',
            'image' => 'apartment', 'landmark' => 'cascade', 'year' => 2025, 'area' => 85,
            'ru' => ['title' => 'Квартира в Кентроне (демо-проект)', 'location' => 'Ереван, Кентрон', 'duration' => '4 месяца',
                'summary' => 'Демонстрационный пример. Замените его реальным проектом в админпанели.',
                'description' => "Это пример того, как выглядит страница проекта.\n\nОпишите здесь задачу клиента, что было сделано и какие материалы использовались. Загрузите реальные фото «до» и «после», и слайдер сравнения появится автоматически."],
            'en' => ['title' => 'Apartment in Kentron (demo project)', 'location' => 'Yerevan, Kentron', 'duration' => '4 months',
                'summary' => 'A demonstration example. Replace it with a real project in the admin panel.',
                'description' => "This is an example of what a project page looks like.\n\nDescribe the client's task, what was done and which materials were used. Upload real before and after photos and the comparison slider will appear automatically."],
            'hy' => ['title' => 'Բնակարան Կենտրոնում (ցուցադրական նախագիծ)', 'location' => 'Երևան, Կենտրոն', 'duration' => '4 ամիս',
                'summary' => 'Ցուցադրական օրինակ։ Փոխարինեք այն իրական նախագծով ադմինիստրատորի վահանակում։',
                'description' => "Սա օրինակ է, թե ինչպես է երևում նախագծի էջը։\n\nՆկարագրեք հաճախորդի խնդիրը, ինչ է արվել և ինչ նյութեր են օգտագործվել։ Վերբեռնեք իրական «առաջ» և «հետո» լուսանկարները, և համեմատման սահիչը կհայտնվի ավտոմատ կերպով։"],
        ],
        [
            'slug' => 'demo-kukhnya-arabkir', 'category' => 'design', 'service' => 'dizain-proekt-interera',
            'image' => 'kitchen', 'landmark' => 'louvre', 'year' => 2025, 'area' => 22,
            'ru' => ['title' => 'Кухня в Арабкире (демо-проект)', 'location' => 'Ереван, Арабкир', 'duration' => '6 недель',
                'summary' => 'Демонстрационный пример. Замените его реальным проектом в админпанели.',
                'description' => "Пример проекта дизайна интерьера.\n\nЗдесь можно рассказать о планировке, выборе фасадов и освещения."],
            'en' => ['title' => 'Kitchen in Arabkir (demo project)', 'location' => 'Yerevan, Arabkir', 'duration' => '6 weeks',
                'summary' => 'A demonstration example. Replace it with a real project in the admin panel.',
                'description' => "An example interior design project.\n\nHere you can describe the layout and the choice of fronts and lighting."],
            'hy' => ['title' => 'Խոհանոց Արաբկիրում (ցուցադրական նախագիծ)', 'location' => 'Երևան, Արաբկիր', 'duration' => '6 շաբաթ',
                'summary' => 'Ցուցադրական օրինակ։ Փոխարինեք այն իրական նախագծով ադմինիստրատորի վահանակում։',
                'description' => "Ինտերիերի դիզայնի նախագծի օրինակ։\n\nԱյստեղ կարող եք պատմել հատակագծի, ճակատների և լուսավորության ընտրության մասին։"],
        ],
        [
            'slug' => 'demo-dom-dilijan', 'category' => 'architecture', 'service' => 'rekonstruktsiya-fasadov',
            'image' => 'facade', 'landmark' => 'bigben', 'year' => 2024, 'area' => 240,
            'ru' => ['title' => 'Дом в Дилижане (демо-проект)', 'location' => 'Дилижан', 'duration' => '8 месяцев',
                'summary' => 'Демонстрационный пример. Замените его реальным проектом в админпанели.',
                'description' => "Пример проекта реконструкции фасада.\n\nОпишите исходное состояние здания, архитектурное решение и материалы."],
            'en' => ['title' => 'House in Dilijan (demo project)', 'location' => 'Dilijan', 'duration' => '8 months',
                'summary' => 'A demonstration example. Replace it with a real project in the admin panel.',
                'description' => "An example facade reconstruction project.\n\nDescribe the original state of the building, the architectural solution and the materials."],
            'hy' => ['title' => 'Տուն Դիլիջանում (ցուցադրական նախագիծ)', 'location' => 'Դիլիջան', 'duration' => '8 ամիս',
                'summary' => 'Ցուցադրական օրինակ։ Փոխարինեք այն իրական նախագծով ադմինիստրատորի վահանակում։',
                'description' => "Ճակատի վերակառուցման նախագծի օրինակ։\n\nՆկարագրեք շենքի սկզբնական վիճակը, ճարտարապետական լուծումը և նյութերը։"],
        ],
    ];

    /** Sample catalog items keyed by brand slug: [ru, en, hy] each with name, category, description. */
    public const PRODUCTS = [
        'atelier-nord' => [
            [['Обеденный стол Fjord', 'Столы', 'Массив дуба, масло-воск, длина 180–240 см.'], ['Fjord dining table', 'Tables', 'Solid oak, hardwax oil, 180–240 cm long.'], ['Fjord ճաշասեղան', 'Սեղաններ', 'Կաղնու զանգված, յուղ-մոմ, երկարությունը՝ 180–240 սմ։']],
            [['Кресло Lounge Ash', 'Кресла', 'Каркас из ясеня, обивка из шерсти или кожи.'], ['Lounge Ash armchair', 'Armchairs', 'Ash frame, wool or leather upholstery.'], ['Lounge Ash բազկաթոռ', 'Բազկաթոռներ', 'Հացենու կմախք, բրդյա կամ կաշվե պաստառ։']],
        ],
        'pietra-viva' => [
            [['Слэб Calacatta Oro', 'Мрамор', 'Белый мрамор с золотистыми прожилками, толщина 20 и 30 мм.'], ['Calacatta Oro slab', 'Marble', 'White marble with golden veining, 20 and 30 mm thick.'], ['Calacatta Oro սալ', 'Մարմար', 'Սպիտակ մարմար՝ ոսկեգույն երակներով, հաստությունը՝ 20 և 30 մմ։']],
            [['Мозаика Terrazzo Mini', 'Мозаика', 'Мраморная крошка на сетке для ванных и кухонь.'], ['Terrazzo Mini mosaic', 'Mosaic', 'Marble chips on mesh for bathrooms and kitchens.'], ['Terrazzo Mini խճանկար', 'Խճանկար', 'Մարմարե փշրանք ցանցի վրա՝ լոգասենյակների և խոհանոցների համար։']],
        ],
        'lumen-haus' => [
            [['Трековая система Line 48', 'Трековый свет', 'Магнитный трек и модули для акцентного и общего света.'], ['Line 48 track system', 'Track lighting', 'Magnetic track and modules for accent and general light.'], ['Line 48 ռելսային համակարգ', 'Ռելսային լույս', 'Մագնիսական ռելս և մոդուլներ՝ շեշտադրված և ընդհանուր լույսի համար։']],
            [['Подвесной светильник Halo', 'Люстры', 'Кольцо из алюминия с рассеянным тёплым светом.'], ['Halo pendant', 'Pendants', 'Aluminium ring with diffused warm light.'], ['Halo կախովի լուսատու', 'Ջահեր', 'Ալյումինե օղակ՝ ցրված ջերմ լույսով։']],
        ],
        'aqua-forma' => [
            [['Смеситель Onda', 'Смесители', 'Латунь, покрытие матовый чёрный или брашированное золото.'], ['Onda mixer', 'Mixers', 'Brass, matte black or brushed gold finish.'], ['Onda խառնիչ', 'Խառնիչներ', 'Արույր, փայլատ սև կամ խոզանակված ոսկեգույն ծածկույթ։']],
            [['Раковина Pietra Round', 'Раковины', 'Накладная раковина из литого камня.'], ['Pietra Round basin', 'Basins', 'Countertop basin made of cast stone.'], ['Pietra Round լվացարան', 'Լվացարաններ', 'Վերադրվող լվացարան՝ ձուլված քարից։']],
        ],
        'tessuto' => [
            [['Ткань Lino Naturale', 'Портьерные ткани', 'Итальянский лён плотной выделки, 12 оттенков.'], ['Lino Naturale fabric', 'Curtain fabrics', 'Dense Italian linen in 12 shades.'], ['Lino Naturale կտոր', 'Վարագույրի կտորներ', 'Խիտ իտալական վուշ՝ 12 երանգով։']],
            [['Ковёр ручной работы Atelier', 'Ковры', 'Шерсть и шёлк, размер под заказ.'], ['Atelier hand-made rug', 'Rugs', 'Wool and silk, made to size.'], ['Atelier ձեռագործ գորգ', 'Գորգեր', 'Բուրդ և մետաքս, չափսը՝ պատվերով։']],
        ],
        'kronhaus' => [
            [['Инженерная доска Alpine Oak', 'Паркет', 'Дуб, верхний слой 4 мм, укладка ёлочкой или палубой.'], ['Alpine Oak engineered board', 'Parquet', 'Oak, 4 mm top layer, herringbone or plank laying.'], ['Alpine Oak ինժեներական տախտակ', 'Մանրահատակ', 'Կաղնի, 4 մմ վերին շերտ, «եղևնաձև» կամ տախտակամածային դասավորություն։']],
            [['Стеновые панели Veneer Line', 'Стеновые панели', 'Натуральный шпон ореха и дуба, скрытый монтаж.'], ['Veneer Line wall panels', 'Wall panels', 'Natural walnut and oak veneer, concealed fixing.'], ['Veneer Line պատի վահանակներ', 'Պատի վահանակներ', 'Ընկույզի և կաղնու բնական շպոն, թաքնված մոնտաժ։']],
        ],
        'ararat-stone' => [
            [['Туф розовый Артик', 'Туф', 'Облицовочная плита для фасадов и интерьеров.'], ['Artik pink tuff', 'Tuff', 'Cladding slab for facades and interiors.'], ['Արթիկի վարդագույն տուֆ', 'Տուֆ', 'Երեսապատման սալ՝ ճակատների և ինտերիերների համար։']],
            [['Базальт тёмный', 'Базальт', 'Плитка и ступени, пиленая или термообработанная поверхность.'], ['Dark basalt', 'Basalt', 'Tiles and steps, sawn or flamed surface.'], ['Մուգ բազալտ', 'Բազալտ', 'Սալիկներ և աստիճաններ, սղոցված կամ ջերմամշակված մակերես։']],
        ],
        'smartline' => [
            [['Панель управления Touch Pro', 'Управление', 'Сенсорная панель для света, климата и штор.'], ['Touch Pro control panel', 'Control', 'Touch panel for lighting, climate and blinds.'], ['Touch Pro կառավարման վահանակ', 'Կառավարում', 'Սենսորային վահանակ՝ լույսի, կլիմայի և վարագույրների համար։']],
            [['Датчик климата Air', 'Датчики', 'Температура, влажность и CO₂ в одном корпусе.'], ['Air climate sensor', 'Sensors', 'Temperature, humidity and CO₂ in one housing.'], ['Air կլիմայի տվիչ', 'Տվիչներ', 'Ջերմաստիճան, խոնավություն և CO₂ մեկ իրանում։']],
        ],
    ];

    /** @return array{0: array, 1: array} ru attributes and translations for a product row */
    public static function product(array $row): array
    {
        [$ru, $en, $hy] = $row;
        $pack = fn (array $v, string $l) => ['name' => $v[0], 'category' => $v[1], 'description' => $v[2], 'price_note' => self::PRICE[$l]];

        return [
            ['name' => $ru[0], 'category' => $ru[1], 'description' => $ru[2], 'price_note' => self::PRICE['ru']],
            ['en' => $pack($en, 'en'), 'hy' => $pack($hy, 'hy')],
        ];
    }
}
