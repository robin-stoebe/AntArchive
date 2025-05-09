<?php
// Current page based on URL
$currentPage = isset($_GET['page']) ? $_GET['page'] : 'home';
$isOverviewActive = $currentPage === 'home';
$isProjectsActive = $currentPage === 'projects' || $currentPage === 'project-detail';
$isSubmitActive = $currentPage === 'submit';
$isNewsActive = $currentPage === 'news';
?>

<nav class="bg-gray-50 border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Mobile menu button -->
        <div class="flex justify-between items-center md:hidden h-12">
            <button id="mobileMenuButton" class="text-gray-500 hover:text-[#4b84c7] focus:outline-none" aria-label="Toggle menu">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <span class="text-gray-700 font-medium">
                <?php if ($isOverviewActive): ?>
                    Overview
                <?php elseif ($isProjectsActive): ?>
                    Projects
                <?php elseif ($isSubmitActive): ?>
                    Submit Project
                <?php elseif ($isNewsActive): ?>
                    News & Events
                <?php else: ?>
                    UCI ICS Capstone
                <?php endif; ?>
            </span>
            <div class="w-6"></div>
        </div>

        <!-- Desktop navigation -->
        <div class="hidden md:flex h-12">
            <div class="flex space-x-8">
                <a href="index.php" class="<?= $isOverviewActive ? 'border-[#4b84c7] text-[#4b84c7]' : 'border-transparent text-gray-500 hover:text-[#4b84c7] hover:border-gray-300' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                    Overview
                </a>
                <a href="index.php?page=projects" class="<?= $isProjectsActive ? 'border-[#4b84c7] text-[#4b84c7]' : 'border-transparent text-gray-500 hover:text-[#4b84c7] hover:border-gray-300' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                    Projects
                </a>
                <a href="index.php?page=news" class="<?= $isNewsActive ? 'border-[#4b84c7] text-[#4b84c7]' : 'border-transparent text-gray-500 hover:text-[#4b84c7] hover:border-gray-300' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                    News & Events
                </a>
                <a href="index.php?page=submit" class="<?= $isSubmitActive ? 'border-[#4b84c7] text-[#4b84c7]' : 'border-transparent text-gray-500 hover:text-[#4b84c7] hover:border-gray-300' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                    Submit Project
                </a>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobileMenu" class="md:hidden hidden">
            <div class="pt-2 pb-3 space-y-1">
                <a href="index.php" class="<?= $isOverviewActive ? 'bg-[#4b84c7] bg-opacity-10 text-[#4b84c7] border-l-4 border-[#4b84c7]' : 'text-gray-500 hover:bg-gray-100 hover:text-[#4b84c7] border-l-4 border-transparent' ?> block pl-3 pr-4 py-2 text-base font-medium transition-colors">
                    Overview
                </a>
                <a href="index.php?page=projects" class="<?= $isProjectsActive ? 'bg-[#4b84c7] bg-opacity-10 text-[#4b84c7] border-l-4 border-[#4b84c7]' : 'text-gray-500 hover:bg-gray-100 hover:text-[#4b84c7] border-l-4 border-transparent' ?> block pl-3 pr-4 py-2 text-base font-medium transition-colors">
                    Projects
                </a>
                <a href="index.php?page=news" class="<?= $isNewsActive ? 'bg-[#4b84c7] bg-opacity-10 text-[#4b84c7] border-l-4 border-[#4b84c7]' : 'text-gray-500 hover:bg-gray-100 hover:text-[#4b84c7] border-l-4 border-transparent' ?> block pl-3 pr-4 py-2 text-base font-medium transition-colors">
                    News & Events
                </a>
                <a href="index.php?page=submit" class="<?= $isSubmitActive ? 'bg-[#4b84c7] bg-opacity-10 text-[#4b84c7] border-l-4 border-[#4b84c7]' : 'text-gray-500 hover:bg-gray-100 hover:text-[#4b84c7] border-l-4 border-transparent' ?> block pl-3 pr-4 py-2 text-base font-medium transition-colors">
                    Submit Project
                </a>
                <a href="index.php?page=login" class="text-gray-500 hover:bg-gray-100 hover:text-[#4b84c7] border-l-4 border-transparent block pl-3 pr-4 py-2 text-base font-medium transition-colors">
                    Staff Login
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
    // Mobile menu toggle
    document.getElementById('mobileMenuButton').addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobileMenu');
        if (mobileMenu.classList.contains('hidden')) {
            mobileMenu.classList.remove('hidden');
            this.innerHTML = '<i class="fas fa-times text-xl"></i>';
        } else {
            mobileMenu.classList.add('hidden');
            this.innerHTML = '<i class="fas fa-bars text-xl"></i>';
        }
    });
</script>
