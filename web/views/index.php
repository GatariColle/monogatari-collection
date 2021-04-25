<?php
require dirname(__FILE__).'/../../php/functions/functions.php';
function carousel(string $name, array $data = null):void {

    if (empty($data)) {
        echo "Кажется что-то пошло не так при загрузке \"{$name}\".";
        return;
    }
    $cards = null;
    foreach ($data as $poster) {
        $cards .= <<<HTML
        <div class="poster-card">
            <div class="poster-img">
                <img src="{$poster['title_cover']}" alt="{$poster['title_name']} poster">
            </div>
            <div class="poster-annotation">
                <div class="container">
                    <div class="center"><b>{$poster['title_name']}</b></div>
                    <div><p>{$poster['title_description']}</p></div>
                </div>
            </div>
            <a href="/read/{$poster['title_id']}"></a>
            <!--<a href="/read/{$poster['title_id']}"></a>-->
            <!--<a href="/read?title={$poster['title_id']}"></a>-->
        </div>
HTML;

    }

    $carousel = <<<HTML
    <div class="card">
        <h1>$name</h1>
        <div class="horizontal-scrollable">
        $cards
        </div>
    </div>
HTML;
    echo $carousel;
}

?>

<!doctype html>
<html lang="ru">
<head>
    <?php include "header.html"; ?>
</head>
<body>
<div class="drawer">Drawer</div>
<div class="wrapper">
    <div class="topbar"> Top bar</div>
    <div class="content">
        <!-- TODO: Write a function with parameters name - to substitute h1 and data - array of cards -->

        <?php
            // $poster = [imgUrl, titleShortname, titleName, titleAnnotation, titleUrl]
        ?>
        <!-- popular -->
        <?php
//        $data = array(
//            array('imgUrl' => 'https://ruranobe.ru/images/thumb/5/54/OreGairu_v01_a.jpg/240px-OreGairu_v01_a.jpg',
//                'shortname' => 'OreGairU',
//                'titleName' => 'Моя юношеская романтическая комедия оказалась неправильной, как я и предполагал',
//                'annotation' => 'Юность — это ложь. Сплошное зло.   Те из вас, кто радуется юности, лишь обманывают себя и всех вокруг. Вы смотрите на всё сквозь розовые очки. И даже совершая смертельную ошибку, вы считаете её лишь доказательством того, что молоды.  Приведу пример. Вляпавшись в преступление вроде воровства из…',
//                'titleUrl' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'),
//            array('imgUrl' => 'https://ruranobe.ru/images/thumb/3/3e/stnbd_v01_a.jpg/240px-stnbd_v01_a.jpg',
//                'shortname' => 'stnbd',
//                'titleName' => 'Танец клинка элементалистов',
//                'annotation' => 'Контракт с духом – сугубо привилегия невинных дев. Здесь, в Духовной академии Арейсия, собраны юные дворянки, которые проходят элитное обучение, чтобы стать элементалистами. Парень Камито неожиданно попадает в переделку и подглядывает за моющейся ученицей, Клэр. Более того, он заключает контракт с ее желанным духом. Да, Камито – парень-элементалист…',
//                'titleUrl' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'),
//            array('imgUrl' => 'https://ruranobe.ru/images/thumb/5/54/OreGairu_v01_a.jpg/240px-OreGairu_v01_a.jpg',
//                'shortname' => 'OreGairU',
//                'titleName' => 'Моя юношеская романтическая комедия оказалась неправильной, как я и предполагал',
//                'annotation' => 'Юность — это ложь. Сплошное зло.   Те из вас, кто радуется юности, лишь обманывают себя и всех вокруг. Вы смотрите на всё сквозь розовые очки. И даже совершая смертельную ошибку, вы считаете её лишь доказательством того, что молоды.  Приведу пример. Вляпавшись в преступление вроде воровства из…',
//                'titleUrl' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'),
//            array('imgUrl' => 'https://ruranobe.ru/images/thumb/3/3e/stnbd_v01_a.jpg/240px-stnbd_v01_a.jpg',
//                'shortname' => 'stnbd',
//                'titleName' => 'Танец клинка элементалистов',
//                'annotation' => 'Контракт с духом – сугубо привилегия невинных дев. Здесь, в Духовной академии Арейсия, собраны юные дворянки, которые проходят элитное обучение, чтобы стать элементалистами. Парень Камито неожиданно попадает в переделку и подглядывает за моющейся ученицей, Клэр. Более того, он заключает контракт с ее желанным духом. Да, Камито – парень-элементалист…',
//                'titleUrl' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'),
//            array('imgUrl' => 'https://ruranobe.ru/images/thumb/5/54/OreGairu_v01_a.jpg/240px-OreGairu_v01_a.jpg',
//                'shortname' => 'OreGairU',
//                'titleName' => 'Моя юношеская романтическая комедия оказалась неправильной, как я и предполагал',
//                'annotation' => 'Юность — это ложь. Сплошное зло.   Те из вас, кто радуется юности, лишь обманывают себя и всех вокруг. Вы смотрите на всё сквозь розовые очки. И даже совершая смертельную ошибку, вы считаете её лишь доказательством того, что молоды.  Приведу пример. Вляпавшись в преступление вроде воровства из…',
//                'titleUrl' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'),
//            array('imgUrl' => 'https://ruranobe.ru/images/thumb/3/3e/stnbd_v01_a.jpg/240px-stnbd_v01_a.jpg',
//                'shortname' => 'stnbd',
//                'titleName' => 'Танец клинка элементалистов',
//                'annotation' => 'Контракт с духом – сугубо привилегия невинных дев. Здесь, в Духовной академии Арейсия, собраны юные дворянки, которые проходят элитное обучение, чтобы стать элементалистами. Парень Камито неожиданно попадает в переделку и подглядывает за моющейся ученицей, Клэр. Более того, он заключает контракт с ее желанным духом. Да, Камито – парень-элементалист…',
//                'titleUrl' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'),
//        );
//        ?>
        <div class="container">
        <?php carousel("Популярное", gettitlesformainpopular()); ?>

        <?php carousel("Недавнее", gettitlesformainrecent()); ?>
        </div>
        <div class="footer">Footer</div>
    </div>
</div>
<script type="text/javascript" src="/js/main.js"></script>
</body>
</html>