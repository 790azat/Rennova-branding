<?php

namespace Database\Seeders;

/** English and Armenian versions of the seeded services and brands, keyed by slug. */
class ContentTranslations
{
    public const SERVICES = [
        'rennova-complete' => [
            'en' => [
                'title' => 'Rennova Complete',
                'excerpt' => 'The full turnkey cycle: architecture, design, renovation, furnishing and final cleaning. One contract, one team, one deadline.',
                'description' => "We take on the whole project: from measurements and the architectural concept to placing the furniture and cleaning before move-in.\n\nYou get a personal manager, a single estimate and schedule, and we are accountable for the result at every stage.",
                'features' => ['Architectural concept and layout', 'Interior design project with 3D visualisation', 'Turnkey renovation with designer supervision', 'Furnishing with exclusive brands', 'Final cleaning before move-in'],
            ],
            'hy' => [
                'title' => 'Rennova Complete',
                'excerpt' => 'Ամբողջական ցիկլ բանալիով՝ ճարտարապետություն, դիզայն, վերանորոգում, կահավորում և վերջնական մաքրում։ Մեկ պայմանագիր, մեկ թիմ, մեկ ժամկետ։',
                'description' => "Մենք ստանձնում ենք ամբողջ նախագիծը՝ չափագրումից և ճարտարապետական հայեցակարգից մինչև կահույքի տեղադրում և մաքրում՝ բնակվելուց առաջ։\n\nԴուք ստանում եք անձնական մենեջեր, միասնական նախահաշիվ և աշխատանքային ժամանակացույց, իսկ մենք պատասխանատու ենք արդյունքի համար յուրաքանչյուր փուլում։",
                'features' => ['Ճարտարապետական հայեցակարգ և հատակագիծ', 'Դիզայն նախագիծ 3D վիզուալիզացիայով', 'Վերանորոգում բանալիով՝ հեղինակային հսկողությամբ', 'Կահավորում էքսկլյուզիվ բրենդներով', 'Վերջնական մաքրում բնակվելուց առաջ'],
            ],
        ],
        'arkhitekturnoe-proektirovanie' => [
            'en' => [
                'title' => 'Architectural design',
                'excerpt' => 'Private houses, commercial buildings and reconstruction: from sketch to working drawings.',
                'description' => 'We develop the concept, schematic and detailed design, and support approvals and construction.',
                'features' => ['Site analysis and concept', 'Schematic design', 'Working drawings', 'Construction support'],
            ],
            'hy' => [
                'title' => 'Ճարտարապետական նախագծում',
                'excerpt' => 'Առանձնատներ, առևտրային օբյեկտներ և վերակառուցում՝ էսքիզից մինչև աշխատանքային նախագիծ։',
                'description' => 'Մշակում ենք հայեցակարգը, էսքիզային և աշխատանքային նախագիծը, ուղեկցում ենք համաձայնեցումները և շինարարությունը։',
                'features' => ['Տարածքի վերլուծություն և հայեցակարգ', 'Էսքիզային նախագիծ', 'Աշխատանքային փաստաթղթեր', 'Շինարարության ուղեկցում'],
            ],
        ],
        'rekonstruktsiya-fasadov' => [
            'en' => [
                'title' => 'Reconstruction and facades',
                'excerpt' => 'We refresh buildings while keeping their character: facade solutions, re-planning, structural strengthening.',
                'description' => 'We survey the building, propose reconstruction options and run the project through to handover.',
                'features' => ['Structural survey', 'Facade solutions', 'Re-planning', 'Approvals'],
            ],
            'hy' => [
                'title' => 'Վերակառուցում և ճակատներ',
                'excerpt' => 'Թարմացնում ենք շենքերի տեսքը՝ պահպանելով դրանց բնույթը՝ ճակատային լուծումներ, վերահատակագծում, կոնստրուկցիաների ամրացում։',
                'description' => 'Ուսումնասիրում ենք շենքը, առաջարկում վերակառուցման տարբերակներ և նախագիծը տանում մինչև հանձնում։',
                'features' => ['Կոնստրուկցիաների հետազոտում', 'Ճակատային լուծումներ', 'Վերահատակագծում', 'Համաձայնեցումներ'],
            ],
        ],
        'dizain-proekt-interera' => [
            'en' => [
                'title' => 'Interior design project',
                'excerpt' => 'An interior where every detail is considered: layout, lighting, materials and furniture.',
                'description' => 'We create a complete design project with photorealistic visualisation and a full set of drawings for the builders.',
                'features' => ['Measurements and layout solutions', '3D visualisation', 'Drawings for builders', 'Selection of materials and furniture'],
            ],
            'hy' => [
                'title' => 'Ինտերիերի դիզայն նախագիծ',
                'excerpt' => 'Ինտերիեր, որտեղ մտածված է ամեն մանրուք՝ հատակագիծ, լույս, նյութեր և կահույք։',
                'description' => 'Ստեղծում ենք ամբողջական դիզայն նախագիծ՝ ֆոտոռեալիստիկ վիզուալիզացիայով և շինարարների համար գծագրերի ամբողջական փաթեթով։',
                'features' => ['Չափագրում և հատակագծային լուծումներ', '3D վիզուալիզացիա', 'Գծագրեր շինարարների համար', 'Նյութերի և կահույքի ընտրություն'],
            ],
        ],
        'avtorskii-nadzor' => [
            'en' => [
                'title' => 'Designer supervision',
                'excerpt' => 'The designer makes sure the result matches the project to the millimetre.',
                'description' => 'Regular site visits, purchase control and answers to the crew’s questions.',
                'features' => ['Site visits', 'Purchase control', 'Project adjustments'],
            ],
            'hy' => [
                'title' => 'Հեղինակային հսկողություն',
                'excerpt' => 'Դիզայները հետևում է, որ արդյունքը համապատասխանի նախագծին մինչև միլիմետր։',
                'description' => 'Պարբերական այցեր օբյեկտ, գնումների վերահսկում և պատասխաններ բրիգադի հարցերին։',
                'features' => ['Այցեր օբյեկտ', 'Գնումների վերահսկում', 'Նախագծի ճշգրտումներ'],
            ],
        ],
        'remont-pod-klyuch' => [
            'en' => [
                'title' => 'Turnkey renovation',
                'excerpt' => 'Rough and finishing works, engineering systems and decoration with a fixed estimate and deadline.',
                'description' => 'Our own crews, technical supervision and transparent reporting at every stage.',
                'features' => ['Demolition and rough works', 'Electrics and plumbing', 'Finishing', 'Warranty on works'],
            ],
            'hy' => [
                'title' => 'Վերանորոգում բանալիով',
                'excerpt' => 'Սևագիր և մաքուր աշխատանքներ, ինժեներական համակարգեր և հարդարում՝ ֆիքսված նախահաշվով և ժամկետով։',
                'description' => 'Սեփական բրիգադներ, տեխնիկական հսկողություն և թափանցիկ հաշվետվություն յուրաքանչյուր փուլում։',
                'features' => ['Ապամոնտաժ և սևագիր աշխատանքներ', 'Էլեկտրականություն և սանտեխնիկա', 'Մաքուր հարդարում', 'Երաշխիք աշխատանքների համար'],
            ],
        ],
        'kosmeticheskii-remont' => [
            'en' => [
                'title' => 'Cosmetic renovation',
                'excerpt' => 'Refresh a space quickly: walls, floors, lighting and details without major construction.',
                'description' => 'Ideal before a sale, a rental or simply for a new mood.',
                'features' => ['Painting and wallpaper', 'New floor coverings', 'Updated lighting'],
            ],
            'hy' => [
                'title' => 'Կոսմետիկ վերանորոգում',
                'excerpt' => 'Արագ թարմացնել տարածքը՝ պատեր, հատակ, լույս և մանրուքներ՝ առանց մեծ շինարարության։',
                'description' => 'Իդեալական է վաճառքից կամ վարձակալության տալուց առաջ, կամ պարզապես նոր տրամադրության համար։',
                'features' => ['Ներկում և պաստառներ', 'Հատակածածկի փոխարինում', 'Լուսավորության թարմացում'],
            ],
        ],
        'uborka-posle-remonta' => [
            'en' => [
                'title' => 'Post-renovation cleaning',
                'excerpt' => 'We remove construction dust, glue and paint marks so you can move in the same day.',
                'description' => 'Professional products and equipment, careful treatment of new surfaces.',
                'features' => ['Construction dust removal', 'Window and facade washing', 'Cleaning of all surfaces'],
            ],
            'hy' => [
                'title' => 'Մաքրում վերանորոգումից հետո',
                'excerpt' => 'Հեռացնում ենք շինարարական փոշին, սոսնձի և ներկի հետքերը, որպեսզի կարողանաք բնակվել նույն օրը։',
                'description' => 'Պրոֆեսիոնալ միջոցներ և սարքավորումներ, զգույշ վերաբերմունք նոր մակերեսների նկատմամբ։',
                'features' => ['Շինարարական փոշու հեռացում', 'Պատուհանների և ճակատների լվացում', 'Բոլոր մակերեսների մաքրում'],
            ],
        ],
        'generalnaya-uborka' => [
            'en' => [
                'title' => 'Deep and regular cleaning',
                'excerpt' => 'Apartments, houses and offices: a one-off deep clean or regular service.',
                'description' => 'We draw up a checklist for your property and follow it on every visit.',
                'features' => ['Kitchen and bathrooms', 'Upholstery cleaning', 'Regular schedule'],
            ],
            'hy' => [
                'title' => 'Գլխավոր և պարբերական մաքրում',
                'excerpt' => 'Բնակարաններ, տներ և գրասենյակներ՝ միանվագ գլխավոր մաքրում կամ պարբերական սպասարկում։',
                'description' => 'Կազմում ենք ստուգաթերթ ձեր օբյեկտի համար և աշխատում ենք դրանով յուրաքանչյուր այցի ժամանակ։',
                'features' => ['Խոհանոց և սանհանգույցներ', 'Կահույքի քիմմաքրում', 'Պարբերական գրաֆիկ'],
            ],
        ],
    ];

    public const BRANDS = [
        'atelier-nord' => [
            'en' => ['country' => 'Denmark', 'category' => 'Furniture', 'tagline' => 'Scandinavian restraint', 'description' => 'Handmade solid oak and ash furniture.'],
            'hy' => ['country' => 'Դանիա', 'category' => 'Կահույք', 'tagline' => 'Սկանդինավյան զսպվածություն', 'description' => 'Ձեռագործ կահույք կաղնու և հացենու զանգվածից։'],
        ],
        'pietra-viva' => [
            'en' => ['country' => 'Italy', 'category' => 'Natural stone', 'tagline' => 'Carrara marble', 'description' => 'Slabs, mosaics and countertops in rare stones.'],
            'hy' => ['country' => 'Իտալիա', 'category' => 'Բնական քար', 'tagline' => 'Կարարայի մարմար', 'description' => 'Սալեր, խճանկար և սեղանի երեսներ հազվագյուտ քարատեսակներից։'],
        ],
        'lumen-haus' => [
            'en' => ['country' => 'Germany', 'category' => 'Lighting', 'tagline' => 'Architectural light', 'description' => 'Track systems and designer luminaires.'],
            'hy' => ['country' => 'Գերմանիա', 'category' => 'Լուսավորություն', 'tagline' => 'Ճարտարապետական լույս', 'description' => 'Տրեկային համակարգեր և դիզայներական լուսատուներ։'],
        ],
        'aqua-forma' => [
            'en' => ['country' => 'Spain', 'category' => 'Plumbing', 'tagline' => 'The shape of water', 'description' => 'Premium taps and ceramics for bathrooms.'],
            'hy' => ['country' => 'Իսպանիա', 'category' => 'Սանտեխնիկա', 'tagline' => 'Ջրի ձևը', 'description' => 'Պրեմիում դասի ծորակներ և կերամիկա լոգասենյակների համար։'],
        ],
        'tessuto' => [
            'en' => ['country' => 'Italy', 'category' => 'Textiles', 'tagline' => 'Interior fabrics', 'description' => 'Curtain and upholstery fabrics, handmade rugs.'],
            'hy' => ['country' => 'Իտալիա', 'category' => 'Տեքստիլ', 'tagline' => 'Գործվածքներ ինտերիերի համար', 'description' => 'Վարագույրի և կահույքի գործվածքներ, ձեռագործ գորգեր։'],
        ],
        'kronhaus' => [
            'en' => ['country' => 'Austria', 'category' => 'Finishing materials', 'tagline' => 'Parquet and panels', 'description' => 'Engineered boards and wall panels in natural veneer.'],
            'hy' => ['country' => 'Ավստրիա', 'category' => 'Հարդարման նյութեր', 'tagline' => 'Մանրահատակ և պանելներ', 'description' => 'Ինժեներական տախտակ և պատի պանելներ բնական շպոնից։'],
        ],
        'ararat-stone' => [
            'en' => ['country' => 'Armenia', 'category' => 'Natural stone', 'tagline' => 'Armenian tuff and basalt', 'description' => 'Local stone for facades and interiors.'],
            'hy' => ['country' => 'Հայաստան', 'category' => 'Բնական քար', 'tagline' => 'Հայկական տուֆ և բազալտ', 'description' => 'Տեղական քար ճակատների և ինտերիերների համար։'],
        ],
        'smartline' => [
            'en' => ['country' => 'Switzerland', 'category' => 'Smart home', 'tagline' => 'Quiet automation', 'description' => 'Control systems for lighting, climate and security.'],
            'hy' => ['country' => 'Շվեյցարիա', 'category' => 'Խելացի տուն', 'tagline' => 'Անաղմուկ ավտոմատացում', 'description' => 'Լույսի, կլիմայի և անվտանգության կառավարման համակարգեր։'],
        ],
    ];
}
