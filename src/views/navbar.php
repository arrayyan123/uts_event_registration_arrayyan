    <nav class="sm:bg-none absolute overflow-x-hidden sm:border-none z-40 w-screen px-2 sm:px-4 py-2.5 bg-gray-800 shadow">
        <div class="container flex flex-wrap justify-between items-center mx-auto">
            <a href="home.php" class="flex items-center">
                <div class="rounded-full h-auto w-auto mx-2 bg-white">
                    <img src="../../assets/images/logo_eventure.png" class="w-[45px] h-[45px]" alt="Logo Eventure">
                </div>
                <span class="self-center text-xl font-semibold whitespace-nowrap text-white">
                    Eventure
                </span>
            </a>
            <div class="flex items-center">
                <button
                    id="menu-toggle"
                    type="button"
                    class="inline-flex items-center p-2 ml-3 text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 text-gray-400 hover:bg-gray-700 dark:focus:ring-gray-600 lg:hidden transition-transform duration-300 ease-in-out">
                    <span class="sr-only">Open main menu</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </div>

            <div
                class="hidden w-full lg:block lg:w-auto transition-all duration-500 ease-in-out transform origin-top"
                id="mobile-menu">
                <ul class="flex flex-col mt-4 text-white text-black lg:flex-row lg:space-x-8 lg:mt-0 lg:items-center lg:text-sm md:font-medium gap-[20px]">
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