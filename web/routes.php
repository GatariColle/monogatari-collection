<?php


$app->get('/', function() use ($app) {
    $popular = getPopularTitles();
    $recent = getRecentTitles();
    return render('index.twig',
        array('popular' => $popular, 'recent' => $recent));
});

$app->get('/read/{title_id}', function($title_id) use ($app) {
    $titleInfo = gettitleinfo($title_id);
    if (empty($titleInfo)) {
        return render(__DIR__ . '/views/error_page.twig',
            array('message' => "Страница не найдена"));
    }
    $chaptersList = is_null(getUser()) ? null : getchapterlist($title_id);
    visitcounter($title_id);

    return render('title.twig',
        array('titleInfo' => $titleInfo, 'chaptersList' => $chaptersList));
});


$app->get('/read/{title_id}/{chapter_id}', function($title_id, $chapter_id) use ($app) {

    $user = getUser();
    if (is_null($user))
        return $app->redirect('/login?login_required');


    $titleInfo = gettitleinfo($title_id);
    if ($user['access_rank'] < $titleInfo['title_rank_acceess'])
        throw new Exception("Доступно только с подпиской", "403");
    $chapter = getchapter($title_id, $chapter_id);
    $nextId = chapterchecknext($title_id, $chapter_id);
    $prevId = chaptercheckprevious($title_id, $chapter_id);
    return render('chapter.twig',
        array('titleId' => $title_id,
            'chapter' => $chapter,
            'nextId' => $nextId,
            'prevId' => $prevId));
});

$app->get('/about', function() use ($app) {
    return render('about.twig');
});

$app->get('/login', function() use ($app) {

    start_session();

    if (isset($_SESSION['user']) && $_SESSION['user']['logged_in']) {
        return $app->redirect('/');
    }

    if (isset($_GET['invalid_login'])) {
        return render('login.twig') . "<script>renderMessage('Не удалось войти')</script>";
    } else if (isset($_GET['registration_success'])) {
        return render('login.twig') . "<script>renderMessage('Вы успешно зарегистрировались! Пожалуйста, выполните вход.')</script>";
    } else if (isset($_GET['login_required'])) {
        return render('login.twig') . "<script>renderMessage('Пожалуйста, зарегистрируйтесь или войдите в свою учетную запись, чтобы получить доступ к этой странице')</script>";
    } else {
        return render('login.twig');
    }
});

$app->get('/register', function() use ($app) {
    start_session();

    if (isset($_SESSION['user']) && $_SESSION['user']['logged_in'])
        return $app->redirect('/');

    if (isset($_GET['user_exists']))
        return render('register.twig') . "<script>renderMessage('Имя пользователя занято.')</script>";
    else
        return render('register.twig');
});

$app->get('/logout', function() use ($app) {
    logOut();
    return $app->redirect(getReferer());
});

$app->get('/subscription', function() use ($app) {
    if (is_null(getUser()))
        return $app->redirect('/login?login_required');
    return render('subscription.twig');
});

$app->get('/unsubscribe', function() use ($app) {
    $user = getUser();
    if (is_null(getUser()))
        throw new Exception("Something's wrong.");

    if (unsubscription($user['login']))
        return $app->redirect('subscription');
    else
        throw new Exception("Something's wrong.");

});

$app->post('/subscribe', function() use ($app) {
    $user = getUser();
    if (is_null($user))
        return "No active session found.";

    if ($user['access_rank'] > 1)
        return "Subscription already active";

    else if (subscription($user['login'])) {
        return $app->redirect('/subscription');
    }
});

$app->post('/login', function() use ($app) {

    $login = $_POST['login'];
    $pass = $_POST['password'];

    if (!empty($login) && !empty($pass)) {
        if (logIn($login, $pass)) {
            $app->redirect('/');
        }
    }
    return $app->redirect('/login?invalid_login');
});

$app->post('/register', function () use ($app) {
    $login = $_POST['login'];
    $pass = $_POST['password'];
    $passConfirmation = $_POST['password-confirmation'];

    if (!empty($login) && !empty($pass) && !empty($passConfirmation)) {
        if ($pass != $passConfirmation) {
            return "Passwords mismatch."; // no need to render page here, passwords matching is already implemented on the site
        }                                 // just in case someone will try to send post request from outside :)

        // try to register, if successful, redirect to login page
        if (registration($login, $pass)) {
            return $app->redirect('/login?registration_success');
        }
        return $app->redirect('/register?user_exists');
    }
    return "Not enough data.";
});

$genresList = array("Боевик", "Магия", "Научная фантастика", "Романтика",
    "Сверхъестественное", "Комедия", "Драма", "Фэнтези", "Гарем", "Меха",
    "Игра", "Приключение", "Мистика", "Психологическое", "Историческое",
    "Ужасы", "Школа", "Демоны", "Детектив", "Школьная жизнь", "Этти");

$app->get('/search', function () use ($app, $genresList) {
    $titles = getNTitles(10);
    return render('search.twig', array('data' => $titles, 'genresList' => $genresList));
});

use Symfony\Component\HttpFoundation\Request;
$app->post('/search', function (Request $request) use ($app, $genresList) {

    $query = $request->get('query') ?? ''; // 'if null, consider as an empty string'
    $genres = $request->get('genres'); // can be null
    $app['monolog']->addDebug("query string: $query, and genres: $genres");
    $searchResult = search($query, $genres);
    return render('search.twig', array('data' => $searchResult, 'genresList' => $genresList));
});

$app->get('/error', function() use ($app) {
    throw new Exception("Sample error");
});