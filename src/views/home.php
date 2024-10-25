<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/../../models/event.php';
require_once __DIR__ . '/../../models/user.php';
require_once __DIR__ . '/../../models/db.php';

$event = new Event();
$events = $event->getAllEvents(); 

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

function getStatusClass($status) {
    switch ($status) {
        case 'open':
            return 'blue-500';
        case 'close':
            return 'orange-500';
        case 'cancel':
            return 'red-500';
        default:
            return 'gray-500';
    }
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if (!empty($search)) {
    $query = "SELECT * FROM events WHERE event_name LIKE :search OR description LIKE :search AND status != 'cancel'";
    $stmt = $db->prepare($query);
    $searchTerm = "%" . $search . "%";
    $stmt->bindParam(':search', $searchTerm);
    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $events = $event->getAllEvents(); 
}

$query = "SELECT * FROM banner_promote";
$stmt = $db->prepare($query);
$stmt->execute();
$banners = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Event Registration</title>
    <link rel="icon" type="image/x-icon" href="../../assets/images/favicon.ico">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/6.5.95/css/materialdesignicons.min.css">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="../css/output.css?v=<?php echo time(); ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        swiper-container {
        width: 100%;
        height: auto;
        }

        @media only screen and (min-width: 768px) {
            swiper-container {
                height: 40%;
            }
        }

        swiper-slide {
        text-align: center;
        font-size: 18px;
        background: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        }

        .hidden {
            display: none;
        }


    </style>
</head>
<body>
    <div class="bg-gray-100">
        <div class="h-screen flex overflow-hidden bg-gray-200">
            <!-- Content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Navbar -->
                <nav class="sm:bg-none bg-white absolute sm:border-none z-40 w-full px-2 sm:px-4 py-2.5 rounded dark:bg-gray-800 shadow">
                    <div class="container flex flex-wrap justify-between items-center mx-auto">
                        <a href="home.php" class="flex items-center">
                            <div class="rounded-full h-auto w-auto mx-2 bg-white">
                                <img src="../../assets/images/logo_eventure.png" class="w-[90px] h-[90px]" alt="">
                            </div>
                            <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">
                                Eventure
                            </span>
                        </a>
                        <div class="flex items-center">
                            <button
                                id="menu-toggle"
                                type="button"
                                class="inline-flex items-center p-2 ml-3 text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600 lg:hidden transition-transform duration-300 ease-in-out"
                            >
                                <span class="sr-only">Open main menu</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16m-7 6h7"
                                    />
                                </svg>
                            </button>
                        </div>

                        <div
                            class="hidden w-full lg:block lg:w-auto transition-all duration-500 ease-in-out transform origin-top"
                            id="mobile-menu"
                        >
                            <ul class="flex flex-col mt-4 dark:text-white text-black lg:flex-row lg:space-x-8 lg:mt-0 lg:items-center lg:text-sm md:font-medium gap-[20px]">
                                <form method="GET" action="home.php" class="flex flex-row text-black gap-2 items-center lg:w-auto w-full">
                                    <input type="text" name="search" placeholder="Search events..." class="border px-4 py-2 rounded-lg w-full sm:w-1/2 lg:w-2/3" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Search</button>
                                </form>
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <li>
                                        <a href="#landing-page" class="block hover:text-indigo-400" aria-current="page">Home</a>
                                    </li>
                                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'organizer'): ?>
                                        <li class=""><a href="dashboard.php" class="block hover:text-indigo-400">Dashboard</a></li>
                                    <?php endif; ?>
                                    <li class=""><a href="profile.php" class="block hover:text-indigo-400">Profile</a></li>
                                    <li class=""><a href="../../controllers/auth_controller.php?logout=true" class="block hover:text-indigo-400">Logout</a></li>
                                <?php else: ?>
                                    <li class=""><a href="login.php" class="block hover:text-indigo-400">Login</a></li>
                                    <li class=""><a href="register.php" class="block hover:text-indigo-400">Register</a></li>
                                <?php endif; ?>
                                <div class="user-info flex flex-row items-center gap-5">
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <?php 
                                            $userInfo = $user->getUserById($_SESSION['user_id']);
                                            $profilePic = isset($userInfo['profile_pic']) ? htmlspecialchars($userInfo['profile_pic']) : 'default.png';
                                            $userName = isset($userInfo['name']) ? htmlspecialchars($userInfo['name']) : 'Guest';
                                        ?>
                                        <img src="../../uploads/profilepic/<?php echo $profilePic; ?>?<?php echo time(); ?>" alt="Profile Picture" width="40" height="40" class="rounded-full mb-2">
                                        <span><?php echo $userName; ?></span>
                                    <?php else: ?>
                                        <img src="../../uploads/profilepic/default.png" alt="Profile Picture" width="40" height="40" class="rounded-full mb-2">
                                        <span>Guest</span>
                                    <?php endif; ?>
                                </div>
                            </ul>
                        </div>
                    </div>
                </nav>
                <!--Navbar end-->
                <!-- Content Body -->
                <div class="flex-1 overflow-auto m">
                    <div id="landing-page" class="relative bg-white h-screen w-screen">
                        <div class="absolute inset-0 bg-gray-600 bg-opacity-60 z-10"></div>
                        <img src="../../assets/images/umn_build.jpg" class="absolute inset-0 h-full w-full object-cover z-0" alt="UMN Building">
                        
                        <div class="absolute inset-0 flex flex-col items-center justify-center z-20">
                            <h1 class="text-white text-4xl font-bold opacity-0 transition-opacity duration-700" id="overlay-title">Eventure</h1>
                            <p class="lg:w-2/3 mx-auto opacity-0 transition-opacity duration-700 text-white leading-relaxed text-center" id="overlay-text">Gabungan dari "event" dan "adventure," menggambarkan perjalanan menyenangkan dan penuh pengalaman dalam mengelola atau menghadiri acara.</p>
                        </div>
                    </div>
                    <!-- Gallery banner dengan animasi sebagai backdrop -->
                    <section id="about-eventure" class="text-gray-600 body-font relative overflow-hidden"> 
                        <div class="container px-5 py-24 mx-auto">
                            <div class="flex flex-col text-center w-full mb-20">
                                <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">Eventure</h1>
                                <p class="lg:w-2/3 mx-auto leading-relaxed text-base">Gabungan dari "event" dan "adventure," menggambarkan perjalanan menyenangkan dan penuh pengalaman dalam mengelola atau menghadiri acara.</p>
                            </div>
                            <!-- Section animasi sebagai backdrop -->
                            <div class="function-based-values-demo absolute top-1/2 inset-0 z-0 opacity-35">
                                <?php 
                                $colors = ['bg-red-500', 'bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-purple-500'];
                                $someValue = 100; 
                                foreach ($banners as $index => $banner): 
                                    $imagePath = "../../uploads/banner_carousel/" . htmlspecialchars($banner['image_path']);
                                ?>
                                    <div 
                                        class="el w-12 h-12 <?php echo $colors[$index % count($colors)]; ?> m-1 rounded-md" 
                                        data-x="<?php echo $someValue; ?>" 
                                        data-color="<?php echo $colors[$index % count($colors)]; ?>"
                                        style="background-size: cover;"
                                    ></div>
                                    <?php $someValue += 120;  ?>
                                <?php endforeach; ?>
                            </div>
                            <?php $totalBanners = count($banners); ?>
                            <!-- Konten galeri banner -->
                            <div id="banner-gallery" class="flex flex-wrap -m-4 relative z-10">
                                <?php foreach ($banners as $index => $banner): ?>
                                    <div class="w-full lg:w-1/3 sm:w-1/2 p-4 banner-item <?php echo ($index >= 6) ? 'hidden' : ''; ?>">
                                        <div class="flex relative">
                                            <img alt="gallery" class="absolute inset-0 w-full h-full object-cover object-center" src="../../uploads/banner_carousel/<?php echo htmlspecialchars($banner['image_path']); ?>">
                                            <div class="px-8 py-10 relative z-10 w-full border-4 border-gray-200 bg-white opacity-0 hover:opacity-100">
                                                <h2 class="tracking-widest text-sm title-font font-medium text-indigo-500 mb-1"><?php echo isset($banner['subtitle']) ? htmlspecialchars($banner['subtitle']) : 'No subtitle available'; ?></h2>
                                                <p class="leading-relaxed truncate"><?php echo isset($banner['description']) ? htmlspecialchars($banner['description']) : 'No description available'; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="text-center relative z-30 mt-6">
                                <button id="loadMoreBtn" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Load More</button>
                                <button id="loadLessBtn" class="bg-gray-500 text-white px-4 py-2 rounded-lg hidden">Load Less</button>
                            </div>
                        </div>
                    </section>
                    <swiper-container id="content-event" class="mySwiper" pagination="true" pagination-clickable="true" navigation="true" space-between="30" centered-slides="true" autoplay-delay="2500" autoplay-disable-on-interaction="false">
                        <?php foreach ($banners as $banner): ?>
                            <swiper-slide>
                                <img src="../../uploads/banner_carousel/<?php echo htmlspecialchars($banner['image_path']); ?>" alt="Banner" class="object-cover w-full h-full">
                            </swiper-slide>
                        <?php endforeach; ?>
                    </swiper-container>
                    <h2 class="text-2xl mt-4 ml-4 font-semibold mb-4">Upcoming Events</h2>
                    <div class="events-list grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 w-full">
                        <?php foreach ($events as $event): ?>
                            <?php if ($event['status'] === 'cancel') continue; ?>
                            <div class="event-item p-2 relative mx-auto w-full">
                                <div class="shadow p-4 rounded-lg bg-white flex flex-col h-full">
                                    <div class="flex justify-center relative rounded-lg overflow-hidden h-52">
                                        <div class="transition-transform duration-500 transform ease-in-out hover:scale-110 w-full">
                                            <div class="absolute inset-0">
                                                <img src="../../uploads/<?php echo htmlspecialchars($event['banner']); ?>" alt="<?php echo htmlspecialchars($event['event_name']); ?>" class="mt-2 w-full h-48 object-cover">
                                            </div>
                                            <div class="absolute flex justify-center bottom-0 mb-3 w-full">
                                                <div class="flex bg-white px-4 py-1 space-x-10 rounded-lg overflow-hidden shadow">
                                                    <p class="flex items-center font-medium text-gray-800">
                                                        <ion-icon class="font-medium text-[25px] mr-1" name="calendar-outline"></ion-icon>
                                                        <?php echo htmlspecialchars($event['start_date']); ?>
                                                    </p>
                                                    <p class="flex items-center font-medium text-gray-800">
                                                        <ion-icon class="font-medium text-[25px] mr-1" name="cash-outline"></ion-icon>
                                                        <?php echo htmlspecialchars($event['price']); ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="absolute top-0 left-0 inline-flex mt-3 ml-3 px-3 py-2 z-10 p-3 rounded-[20px] text-sm font-medium text-white select-none <?php echo 'bg-' . getStatusClass($event['status']); ?>">
                                                <h3 class="text-white"><?php echo htmlspecialchars(ucfirst($event['status'])); ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <h2 class="font-medium text-base md:text-lg text-gray-800 line-clamp-1" title="New York">
                                            <?php echo htmlspecialchars($event['event_name']);?>
                                        </h2>
                                        <p class="mt-2 text-sm text-gray-800 line-clamp-1" title="New York, NY 10004, United States">
                                            <?php echo htmlspecialchars($event['location']); ?>
                                        </p>
                                    </div>
                                    <div class="grid grid-cols-2 grid-rows-2 gap-4 mt-8">
                                        <p class="inline-flex flex-col xl:flex-row xl:items-center text-gray-800">
                                            <ion-icon class="mr-1" name="calendar-outline"></ion-icon>
                                            <span class="mt-2 xl:mt-0">
                                                <?php echo htmlspecialchars($event['start_date']); ?>
                                            </span>
                                        </p>
                                        <p class="inline-flex flex-col xl:flex-row xl:items-center text-gray-800">
                                            <ion-icon class="mr-1" name="calendar-outline"></ion-icon>
                                            <span class="mt-2 xl:mt-0">
                                                <?php echo htmlspecialchars($event['end_date']); ?>
                                            </span>
                                        </p>
                                        <p class="inline-flex flex-col xl:flex-row xl:items-center text-gray-800">
                                            <ion-icon class="mr-1" name="cash-outline"></ion-icon>
                                            <span class="mt-2 xl:mt-0">
                                                <?php echo htmlspecialchars($event['price']); ?>
                                            </span>
                                        </p>
                                        <p class="inline-flex flex-col xl:flex-row xl:items-center text-gray-800">
                                            <ion-icon class="mr-1" name="business-outline"></ion-icon>
                                            <span class="mt-2 xl:mt-0 truncate">
                                                <?php echo htmlspecialchars($event['location']); ?>
                                            </span>
                                        </p>
                                    </div>
                                    <a href="event_detail.php?id=<?php echo $event['id']; ?>" class="block mt-2 text-indigo-500 hover:underline">View Details</a>
                                    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'user' && ($event['status']) === 'open'): ?>
                                        <a href="register_participant.php?event_id=<?php echo $event['id']; ?>" class="block mt-2 text-indigo-500 hover:underline">Register</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <section class="text-gray-600 body-font">
                        <div class="container px-5 py-24 mx-auto">
                            <div class="flex flex-wrap w-full mb-20">
                            <div class="lg:w-1/2 w-full mb-6 lg:mb-0">
                                <h1 class="sm:text-3xl text-2xl font-medium title-font mb-2 text-gray-900">Our Team Member</h1>
                                <div class="h-1 w-20 bg-indigo-500 rounded"></div>
                            </div>
                            <p class="lg:w-1/2 w-full leading-relaxed text-gray-500">Kami dari kelompok 3 Web Programming membuat sebuah aplikasi yang inovatif, user friendly, dan memiliki keunggulan fitur lainnya</p>
                            </div>
                            <div class="flex flex-wrap -m-4">
                                <div class="xl:w-1/4 md:w-1/2 p-4">
                                    <div class="bg-gray-100 h-full p-6 rounded-lg">
                                        <img class="h-40 rounded w-full object-cover object-center mb-6" src="../../assets/images/arrayyan.JPG" alt="content">
                                        <h3 class="tracking-widest text-indigo-500 text-xs font-medium title-font">Full Stack</h3>
                                        <h2 class="text-lg text-gray-900 font-medium title-font mb-4">Muhammad Arrayyan Aprilyanto</h2>
                                        <p class="leading-relaxed text-base">Seorang pengembang full stack memiliki kemampuan untuk mengerjakan semua lapisan aplikasi, baik sisi front end (antarmuka pengguna) maupun back end (server, basis data, dan logika aplikasi). Mereka dapat mengembangkan aplikasi secara menyeluruh dari awal hingga akhir.</p>
                                    </div>
                                </div>
                                <div class="xl:w-1/4 md:w-1/2 p-4">
                                    <div class="bg-gray-100 h-full p-6 rounded-lg">
                                        <img class="h-40 rounded w-full object-cover object-center mb-6" src="../../assets/images/savero.JPG" alt="content">
                                        <h3 class="tracking-widest text-indigo-500 text-xs font-medium title-font">Semi-Full Stack</h3>
                                        <h2 class="text-lg text-gray-900 font-medium title-font mb-4">Savero Madajaya</h2>
                                        <p class="leading-relaxed text-base">Istilah ini biasanya merujuk pada pengembang yang memiliki keterampilan di kedua sisi (front end dan back end) tetapi mungkin tidak sepenuhnya ahli di salah satu sisi. Mereka dapat menangani sebagian besar tugas, tetapi mungkin memerlukan bantuan untuk tugas yang lebih kompleks di salah satu sisi.</p>
                                    </div>
                                </div>
                                <div class="xl:w-1/4 md:w-1/2 p-4">
                                    <div class="bg-gray-100 h-full p-6 rounded-lg">
                                        <img class="h-40 rounded w-full object-cover object-center mb-6" src="../../assets/images/aryasatya.JPG" alt="content">
                                        <h3 class="tracking-widest text-indigo-500 text-xs font-medium title-font">Front-End Dev</h3>
                                        <h2 class="text-lg text-gray-900 font-medium title-font mb-4">Muhammad Aryasatya Triputra</h2>
                                        <p class="leading-relaxed text-base">Pengembang front end fokus pada pengembangan antarmuka pengguna dari aplikasi. Mereka bekerja dengan teknologi seperti HTML, CSS, dan JavaScript untuk menciptakan pengalaman pengguna yang menarik dan responsif.</p>
                                    </div>
                                </div>
                                <div class="xl:w-1/4 md:w-1/2 p-4">
                                    <div class="bg-gray-100 h-full p-6 rounded-lg">
                                        <img class="h-40 rounded w-full object-cover object-top mb-6" src="../../assets/images/fahry.jpg" alt="content">
                                        <h3 class="tracking-widest text-indigo-500 text-xs font-medium title-font">Front-End Dev</h3>
                                        <h2 class="text-lg text-gray-900 font-medium title-font mb-4">Fahry Prathama</h2>
                                        <p class="leading-relaxed text-base">Pengembang front end fokus pada pengembangan antarmuka pengguna dari aplikasi. Mereka bekerja dengan teknologi seperti HTML, CSS, dan JavaScript untuk menciptakan pengalaman pengguna yang menarik dan responsif.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- component -->
                    <footer class="bg-blue-100/80 font-sans dark:bg-gray-900">
                        <div class="container px-6 py-12 mx-auto">
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 sm:gap-y-10 lg:grid-cols-4">
                                <div class="sm:col-span-2">
                                    <h1 class="max-w-lg text-xl font-semibold tracking-tight text-gray-800 xl:text-2xl dark:text-white">Search Events</h1>
                                    <form method="GET" action="home.php">
                                        <div class="flex flex-col mx-auto mt-6 space-y-3 md:space-y-0 md:flex-row">
                                            <input name="search" 
                                            value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                                            type="text" class="px-4 py-2 text-gray-700 bg-white border rounded-md dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 focus:ring-blue-300" placeholder="Search Event Here" />
                                    
                                            <button type="submit" class="w-full px-6 py-2.5 text-sm font-medium tracking-wider text-white transition-colors duration-300 transform md:w-auto md:mx-4 focus:outline-none bg-gray-800 rounded-lg hover:bg-gray-700 focus:ring focus:ring-gray-300 focus:ring-opacity-80">
                                                Search
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <div>
                                    <p class="font-semibold text-gray-800 dark:text-white">Quick Link</p>

                                    <div class="flex flex-col items-start mt-5 space-y-2">
                                        <a href="#landing-page" class="text-gray-600 transition-colors duration-300 dark:text-gray-300 dark:hover:text-blue-400 hover:underline hover:cursor-pointer hover:text-blue-500">Home</a>
                                        <a href="#about-eventure4" class="text-gray-600 transition-colors duration-300 dark:text-gray-300 dark:hover:text-blue-400 hover:underline hover:cursor-pointer hover:text-blue-500">About Us</a>
                                        <a href="" class="text-gray-600 transition-colors duration-300 dark:text-gray-300 dark:hover:text-blue-400 hover:underline hover:cursor-pointer hover:text-blue-500"></a>
                                    </div>
                                </div>

                                <div>
                                    <p class="font-semibold text-gray-800 dark:text-white">Eventure Features</p>

                                    <div class="flex flex-col items-start mt-5 space-y-2">
                                        <p class="text-gray-600 transition-colors duration-300 dark:text-gray-300 dark:hover:text-blue-400 hover:underline hover:cursor-pointer hover:text-blue-500">Retail & E-Commerce</p>
                                        <p class="text-gray-600 transition-colors duration-300 dark:text-gray-300 dark:hover:text-blue-400 hover:underline hover:cursor-pointer hover:text-blue-500">Information Technology</p>
                                        <p class="text-gray-600 transition-colors duration-300 dark:text-gray-300 dark:hover:text-blue-400 hover:underline hover:cursor-pointer hover:text-blue-500">Finance & Insurance</p>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-6 border-gray-200 md:my-8 dark:border-gray-700 h-2" />
                            <p class="font-sans p-8 text-start dark:text-white md:text-center md:text-lg md:p-4">© 2024 Eventure Inc. All rights reserved.</p>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </div>
    <script type="module">
        import anime from '../../node_modules/animejs/lib/anime.es.js';

        anime({
            targets: '.function-based-values-demo .el',
            translateX: function(el) {
                return el.getAttribute('data-x');
            },
            translateY: function(el, i) {
                return 50 + (-50 * i);
            },
            scale: function(el, i, l) {
                return (l - i) + 0.25;
            },
            rotate: function() { return anime.random(-360, 360); },
            borderRadius: function() { return ['50%', anime.random(10, 35) + '%']; },
            duration: function() { return anime.random(1200, 1800); },
            delay: function() { return anime.random(0, 400); },
            backgroundColor: function(el) {
                return el.getAttribute('data-color');
            },
            backgroundImage: function(el) {
                return 'url("' + el.getAttribute('data-image') + '")'; 
            },
            direction: 'alternate',
            loop: true,
            complete: function(anim) {
                setTimeout(function() {
                    anim.restart();
                }, 2000);
            }
        });

    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const loadMoreBtn = document.getElementById('loadMoreBtn');
        const loadLessBtn = document.getElementById('loadLessBtn');
        const banners = document.querySelectorAll('.banner-item');
        let currentlyVisible = 6;

        loadMoreBtn.addEventListener('click', function () {
            const hiddenBanners = Array.from(banners).slice(currentlyVisible, currentlyVisible + 6);
            
            hiddenBanners.forEach(banner => {
                banner.classList.remove('hidden');
            });

            currentlyVisible += hiddenBanners.length;
            if (currentlyVisible >= banners.length) {
                loadMoreBtn.style.display = 'none';
            }
            loadLessBtn.classList.remove('hidden');
        });

        loadLessBtn.addEventListener('click', function () {
            banners.forEach((banner, index) => {
                if (index >= 6) {
                    banner.classList.add('hidden');
                }
            });
            currentlyVisible = 6; 
            loadMoreBtn.style.display = 'inline-block';
            loadLessBtn.classList.add('hidden');
        });
    });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const links = document.querySelectorAll('a[href^="#"]');

            links.forEach(link => {
                link.addEventListener("click", function (e) {
                    e.preventDefault(); 
                    const targetId = this.getAttribute("href");
                    const targetElement = document.querySelector(targetId);

                    if (targetElement) {
                        targetElement.scrollIntoView({
                            behavior: "smooth"
                        });
                    }
                });
            });
        });
    </script>
    <script>
        const titleElement = document.getElementById('overlay-title');
        const textElement = document.getElementById('overlay-text');
        let hasScrolledToAboutEventure = false; 
        let isSearching = false; 

        window.onload = () => {
            titleElement.classList.remove('opacity-0');
            titleElement.classList.add('opacity-100');
            textElement.classList.remove('opacity-0');
            textElement.classList.add('opacity-100');
            setTimeout(() => {
                if (!hasScrolledToAboutEventure && !isSearching) {
                    const aboutEventure = document.getElementById('about-eventure');
                    if (aboutEventure) {
                        aboutEventure.scrollIntoView({ behavior: 'smooth' });
                        hasScrolledToAboutEventure = true;
                    }
                }
            }, 1000);
        };

        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('search') && urlParams.get('search').trim() !== '') {
                isSearching = true; 
                const contentEvent = document.getElementById('content-event');
                if (contentEvent) {
                    contentEvent.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    </script>
    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        menuToggle.addEventListener('click', function () {
            mobileMenu.classList.toggle('hidden');
            mobileMenu.classList.toggle('opacity-0');
            mobileMenu.classList.toggle('scale-y-0');

            setTimeout(() => {
                mobileMenu.classList.toggle('opacity-100'); 
                mobileMenu.classList.toggle('scale-y-100'); 
            }, 10);
        });
        let startTime = Date.now();

        function logActivity() {
            let endTime = Date.now();
            let timeSpent = Math.round((endTime - startTime) / 1000); 

            let xhr = new XMLHttpRequest();
            xhr.open('POST', 'log_activity.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('time_spent=' + timeSpent + '&page=' + encodeURIComponent(window.location.pathname));
        }

        window.addEventListener('beforeunload', logActivity);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
</body>
</html>
 