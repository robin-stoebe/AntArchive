<?php
// No need to check authentication here since it's already checked in index.php

// Get active tab from query parameter or default to overview
$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'overview';

// Sample data for demonstration
$pendingProjects = [
    [
        'id' => 1,
        'title' => 'Smart Health Monitoring System',
        'submittedBy' => 'Alice Johnson',
        'submittedDate' => '2025-05-10',
        'course' => 'CS 180A',
        'teamSize' => 4,
    ],
    [
        'id' => 2,
        'title' => 'Campus Navigation App',
        'submittedBy' => 'Bob Williams',
        'submittedDate' => '2025-05-08',
        'course' => 'CS 180A',
        'teamSize' => 3,
    ],
    [
        'id' => 3,
        'title' => 'AI Study Assistant',
        'submittedBy' => 'Charlie Brown',
        'submittedDate' => '2025-05-05',
        'course' => 'INF 191A',
        'teamSize' => 5,
    ],
];

$recentActivity = [
    [
        'id' => 1,
        'type' => 'approval',
        'project' => 'Virtual Lab Simulator',
        'date' => '2025-05-09',
        'student' => 'Diana Miller',
    ],
    [
        'id' => 2,
        'type' => 'feedback',
        'project' => 'Sustainable Energy Tracker',
        'date' => '2025-05-07',
        'student' => 'Edward Davis',
    ],
    [
        'id' => 3,
        'type' => 'rejection',
        'project' => 'Social Media Analytics Tool',
        'date' => '2025-05-06',
        'student' => 'Fiona Wilson',
    ],
    [
        'id' => 4,
        'type' => 'approval',
        'project' => 'Augmented Reality Campus Tour',
        'date' => '2025-05-04',
        'student' => 'George Thompson',
    ],
];

$currentCourses = [
    [
        'id' => 1,
        'code' => 'CS 180A',
        'name' => 'Senior Project',
        'quarter' => 'Spring 2025',
        'students' => 24,
        'projects' => 8,
        'pendingApprovals' => 2,
    ],
    [
        'id' => 2,
        'code' => 'INF 191A',
        'name' => 'Senior Project',
        'quarter' => 'Spring 2025',
        'students' => 18,
        'projects' => 5,
        'pendingApprovals' => 1,
    ],
    [
        'id' => 3,
        'code' => 'CS 125',
        'name' => 'Project in AI',
        'quarter' => 'Spring 2025',
        'students' => 30,
        'projects' => 10,
        'pendingApprovals' => 0,
    ],
];

$upcomingDeadlines = [
    [
        'id' => 1,
        'title' => 'Project Proposal Approvals',
        'course' => 'CS 180A',
        'date' => '2025-05-15',
        'daysLeft' => 5,
    ],
    [
        'id' => 2,
        'title' => 'Midterm Evaluations',
        'course' => 'INF 191A',
        'date' => '2025-05-20',
        'daysLeft' => 10,
    ],
    [
        'id' => 3,
        'title' => 'Final Presentations',
        'course' => 'CS 125',
        'date' => '2025-06-05',
        'daysLeft' => 26,
    ],
];
?>

<main class="min-h-screen flex flex-col">
    <div class="flex-1 bg-gray-50">
        <!-- Professor Dashboard Tabs -->
        <div class="bg-white border-b mt-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-2xl font-bold pt-6 pb-4">Professor Dashboard</h1>
                <div class="flex overflow-x-auto">
                    <a href="?page=professor&tab=overview" 
                       class="py-4 px-6 font-medium text-sm whitespace-nowrap <?php echo $activeTab === 'overview' ? 'border-b-2 border-[#4b84c7] text-[#4b84c7]' : 'text-gray-500 hover:text-gray-700'; ?>">
                        Overview
                    </a>
                    <a href="?page=professor&tab=projects" 
                       class="py-4 px-6 font-medium text-sm whitespace-nowrap <?php echo $activeTab === 'projects' ? 'border-b-2 border-[#4b84c7] text-[#4b84c7]' : 'text-gray-500 hover:text-gray-700'; ?>">
                        Student Projects
                    </a>
                    <a href="?page=professor&tab=courses" 
                       class="py-4 px-6 font-medium text-sm whitespace-nowrap <?php echo $activeTab === 'courses' ? 'border-b-2 border-[#4b84c7] text-[#4b84c7]' : 'text-gray-500 hover:text-gray-700'; ?>">
                        My Courses
                    </a>
                </div>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <?php if ($activeTab === 'overview'): ?>
                <!-- Overview Tab -->
                <div>
                    <!-- Welcome Card -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-semibold mb-2">Welcome, Dr. Smith</h2>
                        <p class="text-gray-600 mb-4">
                            You have <?php echo count($pendingProjects); ?> pending project approvals to review.
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <a href="index.php?page=professor&tab=projects" 
                               class="inline-flex items-center px-4 py-2 bg-[#4b84c7] text-white rounded-md hover:bg-[#3b6ba0] transition-colors">
                                Review Pending Projects
                            </a>
                        </div>
                    </div>

                    <!-- Dashboard Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                        <!-- Pending Approvals Card -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="bg-[#4b84c7] px-4 py-3 text-white">
                                <h3 class="font-medium flex items-center">
                                    <i class="fas fa-clock mr-2 h-5 w-5"></i> Pending Approvals
                                </h3>
                            </div>
                            <div class="p-4">
                                <?php if (count($pendingProjects) === 0): ?>
                                    <p class="text-gray-500 italic">No pending approvals.</p>
                                <?php else: ?>
                                    <div class="space-y-3">
                                        <?php foreach ($pendingProjects as $project): ?>
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="font-medium"><?php echo $project['title']; ?></p>
                                                    <p class="text-sm text-gray-500">
                                                        <?php echo $project['course']; ?> • <?php echo $project['submittedBy']; ?> • <?php echo $project['teamSize']; ?> members
                                                    </p>
                                                </div>
                                                <a href="index.php?page=professor-project&id=<?php echo $project['id']; ?>" 
                                                   class="p-1 text-blue-500 hover:bg-blue-50 rounded">
                                                    <i class="fas fa-chevron-right"></i>
                                                </a>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (count($pendingProjects) > 0): ?>
                                    <a href="index.php?page=professor&tab=projects" 
                                       class="mt-4 text-sm text-[#4b84c7] hover:underline flex items-center">
                                        View all pending approvals <i class="fas fa-chevron-right h-4 w-4 ml-1"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Recent Activity Card -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="bg-[#4b84c7] px-4 py-3 text-white">
                                <h3 class="font-medium flex items-center">
                                    <i class="fas fa-comment-alt mr-2 h-5 w-5"></i> Recent Activity
                                </h3>
                            </div>
                            <div class="p-4">
                                <?php if (count($recentActivity) === 0): ?>
                                    <p class="text-gray-500 italic">No recent activity.</p>
                                <?php else: ?>
                                    <div class="space-y-3">
                                        <?php foreach ($recentActivity as $activity): ?>
                                            <div class="flex items-start">
                                                <div class="mt-1 p-1 rounded-full mr-3 flex items-center justify-center w-6 h-6
                                                    <?php 
                                                        if ($activity['type'] === 'approval') echo 'bg-green-100 text-green-600';
                                                        elseif ($activity['type'] === 'rejection') echo 'bg-red-100 text-red-600';
                                                        else echo 'bg-blue-100 text-blue-600';
                                                    ?>">
                                                    <?php if ($activity['type'] === 'approval'): ?>
                                                        <i class="fas fa-check-circle h-4 w-4"></i>
                                                    <?php elseif ($activity['type'] === 'rejection'): ?>
                                                        <i class="fas fa-times h-4 w-4"></i>
                                                    <?php else: ?>
                                                        <i class="fas fa-comment-alt h-4 w-4"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <p class="text-sm">
                                                        <span class="font-medium">
                                                            <?php 
                                                                if ($activity['type'] === 'approval') echo 'Approved';
                                                                elseif ($activity['type'] === 'rejection') echo 'Rejected';
                                                                else echo 'Provided feedback on';
                                                            ?>
                                                        </span>
                                                        <?php echo $activity['project']; ?> by <?php echo $activity['student']; ?>
                                                    </p>
                                                    <p class="text-xs text-gray-500"><?php echo $activity['date']; ?></p>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <a href="index.php?page=professor-activity" 
                                   class="mt-4 text-sm text-[#4b84c7] hover:underline flex items-center">
                                    View all activity <i class="fas fa-chevron-right h-4 w-4 ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Current Courses -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                        <div class="bg-[#4b84c7] px-6 py-4 text-white">
                            <h3 class="font-medium text-lg flex items-center">
                                <i class="fas fa-book-open mr-2 h-5 w-5"></i> Current Courses
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Course
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Quarter
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Students
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Projects
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Pending
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php foreach ($currentCourses as $course): ?>
                                            <tr>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="font-medium"><?php echo $course['code']; ?></div>
                                                    <div class="text-sm text-gray-500"><?php echo $course['name']; ?></div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm"><?php echo $course['quarter']; ?></td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm"><?php echo $course['students']; ?></td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm"><?php echo $course['projects']; ?></td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <?php if ($course['pendingApprovals'] > 0): ?>
                                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                                            <?php echo $course['pendingApprovals']; ?> pending
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                                            None
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm">
                                                    <a href="index.php?page=professor-course&id=<?php echo $course['id']; ?>" class="text-[#4b84c7] hover:underline">
                                                        View Details
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="bg-white rounded-lg shadow-md p-6 flex items-center">
                            <div class="bg-blue-100 p-3 rounded-full mr-4 flex items-center justify-center w-12 h-12">
                                <i class="fas fa-users text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Students</p>
                                <p class="text-2xl font-bold">72</p>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-md p-6 flex items-center">
                            <div class="bg-green-100 p-3 rounded-full mr-4 flex items-center justify-center w-12 h-12">
                                <i class="fas fa-file-alt text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Active Projects</p>
                                <p class="text-2xl font-bold">23</p>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-md p-6 flex items-center">
                            <div class="bg-yellow-100 p-3 rounded-full mr-4 flex items-center justify-center w-12 h-12">
                                <i class="fas fa-clock text-yellow-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Pending Approvals</p>
                                <p class="text-2xl font-bold"><?php echo count($pendingProjects); ?></p>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-md p-6 flex items-center">
                            <div class="bg-purple-100 p-3 rounded-full mr-4 flex items-center justify-center w-12 h-12">
                                <i class="fas fa-check-circle text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Completed Projects</p>
                                <p class="text-2xl font-bold">18</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif ($activeTab === 'projects'): ?>
                <!-- Projects Tab -->
                <div>
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-semibold mb-2">All Student Projects</h2>
                        <p class="text-gray-600 mb-4">
                            This section allows you to view and manage all student projects across all your courses. You can
                            search, filter, and sort projects based on various criteria regardless of which course they belong to.
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <button class="inline-flex items-center px-4 py-2 bg-[#4b84c7] text-white rounded-md hover:bg-[#3b6ba0] transition-colors">
                                View All Projects
                            </button>
                            <button class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200 transition-colors">
                                Pending Approvals
                            </button>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6">
                        <p class="text-center text-gray-500 italic">
                            Project management interface would be displayed here, showing projects across all courses.
                        </p>
                    </div>
                </div>
            <?php elseif ($activeTab === 'courses'): ?>
                <!-- Courses Tab -->
                <div>
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-semibold mb-2">My Courses</h2>
                        <p class="text-gray-600 mb-4">
                            This section allows you to manage your courses, view student rosters, and access course-specific
                            projects. You can organize and manage projects by course, view team formations, and handle course
                            administration tasks.
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <button class="inline-flex items-center px-4 py-2 bg-[#4b84c7] text-white rounded-md hover:bg-[#3b6ba0] transition-colors">
                                View All Courses
                            </button>
                            <button class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200 transition-colors">
                                Current Quarter
                            </button>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6">
                        <p class="text-center text-gray-500 italic">
                            Course management interface would be displayed here, showing courses and their associated projects.
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<!-- Add Font Awesome for icons -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js" integrity="sha512-GWzVrcGlo0TxTRvz9ttioyYJ+Wwk9Ck0G81D+eO63BaqHaJ3YZX9wuqjwgfcV/MrB2PhaVX9DkYVhbFpStnqpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- Tab switching JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // This script handles tab switching if needed
    // The tabs are already handled by PHP, but you can add additional functionality here
});
</script>
