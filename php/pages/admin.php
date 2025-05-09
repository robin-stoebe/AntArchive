<?php
// No need to check authentication here since it's already checked in index.php

// Mock data for statistics
$stats = [
    'totalProjects' => 124,
    'activeProjects' => 23,
    'featuredProjects' => 5,
    'awardWinners' => 12,
    'newsEvents' => 15,
    'activeProfessors' => 18
];

// Mock data for recent activity
$recentActivity = [
    [
        'type' => 'news',
        'time' => 'Today at 11:45 AM',
        'description' => 'New event added: "Spring Expo 2024 Registration"'
    ],
    [
        'type' => 'project',
        'time' => 'Today at 10:23 AM',
        'description' => 'New project submitted: "AI-Powered Health Monitoring"'
    ],
    [
        'type' => 'approval',
        'time' => 'Yesterday at 2:45 PM',
        'description' => 'Project approved: "Blockchain for Supply Chain"'
    ]
];
?>

<main class="min-h-screen flex flex-col">
    <!-- Main content area -->
    <div class="flex-1 p-6 bg-gray-50">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold mb-6">Admin Dashboard</h1>

            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Welcome to the Admin View</h2>
                <p class="text-gray-600 mb-4">
                    This is the admin dashboard for the UCI ICS Capstone Project Archive. Here you can manage all aspects of
                    the system.
                </p>
            </div>

            <!-- Admin Actions Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Featured Projects & Awards Card -->
                <a href="index.php?page=admin-featured-projects" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-start">
                        <div class="bg-yellow-100 p-3 rounded-full mr-4 flex items-center justify-center w-12 h-12">
                            <i class="fas fa-trophy text-yellow-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Featured Projects & Awards</h3>
                            <p class="text-gray-600">Manage featured projects and award winners.</p>
                        </div>
                    </div>
                </a>

                <!-- Manage Projects Card -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="flex items-start">
                        <div class="bg-blue-100 p-3 rounded-full mr-4 flex items-center justify-center w-12 h-12">
                            <i class="fas fa-file-alt text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Manage Projects</h3>
                            <p class="text-gray-600">Add, edit, or remove projects from the archive.</p>
                        </div>
                    </div>
                </div>

                <!-- User Management Card -->
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="flex items-start">
                        <div class="bg-green-100 p-3 rounded-full mr-4 flex items-center justify-center w-12 h-12">
                            <i class="fas fa-users text-green-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-2">User Management</h3>
                            <p class="text-gray-600">Manage professors, students, and admin accounts.</p>
                        </div>
                    </div>
                </div>

                <!-- News & Events Management Card -->
                <a href="index.php?page=admin-news" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-start">
                        <div class="bg-purple-100 p-3 rounded-full mr-4 flex items-center justify-center w-12 h-12">
                            <i class="fas fa-newspaper text-purple-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-2">News & Events</h3>
                            <p class="text-gray-600">Manage news items and upcoming events.</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- System Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-3">System Statistics</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Projects:</span>
                            <span class="font-medium"><?php echo $stats['totalProjects']; ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Active Professors:</span>
                            <span class="font-medium"><?php echo $stats['activeProfessors']; ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Pending Approvals:</span>
                            <span class="font-medium">3</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Featured Projects:</span>
                            <span class="font-medium"><?php echo $stats['featuredProjects']; ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Award Winners:</span>
                            <span class="font-medium"><?php echo $stats['awardWinners']; ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">News & Events:</span>
                            <span class="font-medium"><?php echo $stats['newsEvents']; ?></span>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-3">Recent Activity</h3>
                    <div class="space-y-3">
                        <?php foreach ($recentActivity as $activity): ?>
                            <div class="border-l-4 
                                <?php 
                                    if ($activity['type'] === 'gallery') echo 'border-purple-500';
                                    elseif ($activity['type'] === 'project') echo 'border-blue-500';
                                    elseif ($activity['type'] === 'approval') echo 'border-green-500';
                                ?> 
                                pl-3 py-1">
                                <p class="text-sm text-gray-600"><?php echo $activity['time']; ?></p>
                                <p class="font-medium"><?php echo $activity['description']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Add Font Awesome for icons -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js" integrity="sha512-GWzVrcGlo0TxTRvz9ttioyYJ+Wwk9Ck0G81D+eO63BaqHaJ3YZX9wuqjwgfcV/MrB2PhaVX9DkYVhbFpStnqpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
