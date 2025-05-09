<?php
// Get project ID from URL
$projectId = isset($_GET['id']) ? intval($_GET['id']) : 0;

$project = [
    'id' => $projectId,
    'title' => 'Smart Health Monitoring System',
    'description' => 'A comprehensive health monitoring system for elderly patients using IoT devices and AI.',
    'longDescription' => "This capstone project developed a comprehensive health monitoring system for elderly patients, leveraging IoT devices and artificial intelligence to provide real-time health tracking and emergency alerts.

The system includes wearable sensors that monitor vital signs such as heart rate, blood pressure, and body temperature. These sensors connect to a central hub in the patient's home, which processes the data and sends it to a cloud-based platform for analysis.

Machine learning algorithms analyze the data to detect anomalies and predict potential health issues before they become critical. The system also includes a mobile application for caregivers and family members, allowing them to monitor the patient's health status remotely and receive alerts in case of emergencies.

The project addresses the growing need for remote healthcare solutions, especially for elderly patients who prefer to age in place. By providing continuous monitoring and early detection of health issues, the system aims to improve patient outcomes, reduce hospitalizations, and provide peace of mind for caregivers.",
    'quarter' => 'Spring',
    'year' => '2025',
    'degree' => 'BS',
    'professor' => 'Dr. Jane Smith',
    'course' => 'Computer Science',
    'sponsor' => [
        'name' => 'John Doe',
        'organization' => 'HealthTech Innovations',
        'email' => 'john.doe@healthtech.com',
    ],
    'teamMembers' => [
        ['name' => 'Alice Johnson', 'role' => 'Team Lead', 'email' => 'alice@uci.edu'],
        ['name' => 'Bob Williams', 'role' => 'Backend Developer', 'email' => 'bob@uci.edu'],
        ['name' => 'Charlie Brown', 'role' => 'Frontend Developer', 'email' => 'charlie@uci.edu'],
        ['name' => 'Diana Miller', 'role' => 'Data Scientist', 'email' => 'diana@uci.edu'],
    ],
    'tags' => ['Healthcare', 'IoT', 'Artificial Intelligence', 'Mobile Apps'],
    'links' => ['https://project-website.com', 'https://github.com/project-repo'],
    'isAwardWinner' => true,
    'rank' => 1,
    'files' => [
        [
            'name' => 'Final Report.pdf',
            'type' => 'PDF',
            'size' => '2.4 MB',
            'url' => '#',
        ],
        [
            'name' => 'Project Presentation.pptx',
            'type' => 'PowerPoint',
            'size' => '5.1 MB',
            'url' => '#',
        ],
        [
            'name' => 'Demo Video.mp4',
            'type' => 'Video',
            'size' => '18.7 MB',
            'url' => '#',
        ],
    ],
];

$paragraphs = explode("\n\n", $project['longDescription']);
?>

<div class="flex-1 bg-gray-50">
    <!-- Back button -->
    <div class="bg-white border-b">
        <div class="max-w-6xl mx-auto px-6 py-3">
            <a href="index.php?page=projects" class="flex items-center text-[#4b84c7] hover:underline">
                <i class="fas fa-arrow-left text-sm mr-1"></i> Back to Projects
            </a>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-6 py-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Project header -->
            <div class="bg-[#4b84c7] text-white p-6">
                <div class="flex flex-wrap justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold mb-2"><?= $project['title'] ?></h1>
                        <p class="text-lg opacity-90 mb-4"><?= $project['description'] ?></p>
                        <div class="flex flex-wrap gap-2 items-center text-sm">
                            <span class="flex items-center">
                                <i class="fas fa-calendar mr-1"></i> <?= $project['quarter'] ?> <?= $project['year'] ?>
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-book-open mr-1"></i> <?= $project['course'] ?>
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-graduation-cap mr-1"></i> <?= $project['degree'] ?>
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-users mr-1"></i> <?= count($project['teamMembers']) ?> Team Members
                            </span>
                        </div>
                    </div>
                    <?php if ($project['isAwardWinner']): ?>
                        <div class="bg-[#f8e858] text-black px-4 py-2 rounded-full font-bold flex items-center mt-2 sm:mt-0">
                            <i class="fas fa-award mr-1"></i> #<?= $project['rank'] ?> Award Winner
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Project content -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="md:col-span-2">
                        <!-- Project image -->
                        <div class="bg-[#d9d9d9] aspect-video mb-6 rounded-md"></div>

                        <!-- Project description -->
                        <section class="mb-8">
                            <h2 class="text-xl font-bold mb-4">Project Description</h2>
                            <div class="prose max-w-none">
                                <?php foreach ($paragraphs as $paragraph): ?>
                                    <p class="mb-4"><?= $paragraph ?></p>
                                <?php endforeach; ?>
                            </div>
                        </section>

                        <!-- Project files -->
                        <section class="mb-8">
                            <h2 class="text-xl font-bold mb-4">Project Files</h2>
                            <div class="space-y-3">
                                <?php foreach ($project['files'] as $file): ?>
                                    <div class="flex items-center justify-between p-3 border rounded-md hover:bg-gray-50">
                                        <div>
                                            <p class="font-medium"><?= $file['name'] ?></p>
                                            <p class="text-sm text-gray-500">
                                                <?= $file['type'] ?> • <?= $file['size'] ?>
                                            </p>
                                        </div>
                                        <a href="<?= $file['url'] ?>" class="text-[#4b84c7] hover:text-[#3b6ba0] flex items-center" download>
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </section>

                        <!-- Project links -->
                        <?php if (!empty($project['links'])): ?>
                            <section class="mb-8">
                                <h2 class="text-xl font-bold mb-4">Project Links</h2>
                                <div class="space-y-2">
                                    <?php foreach ($project['links'] as $link): ?>
                                        <a href="<?= $link ?>" target="_blank" rel="noopener noreferrer" class="flex items-center text-[#4b84c7] hover:underline">
                                            <i class="fas fa-external-link-alt mr-2"></i> <?= $link ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </section>
                        <?php endif; ?>
                    </div>

                    <div>
                        <!-- Tags -->
                        <section class="mb-6">
                            <h2 class="text-lg font-bold mb-3">Project Tags</h2>
                            <div class="flex flex-wrap gap-2">
                                <?php foreach ($project['tags'] as $tag): ?>
                                    <span class="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 text-sm font-medium text-gray-800">
                                        <i class="fas fa-tag text-xs mr-1"></i> <?= $tag ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </section>

                        <!-- Team members -->
                        <section class="mb-6">
                            <h2 class="text-lg font-bold mb-3">Team Members</h2>
                            <div class="space-y-3">
                                <?php foreach ($project['teamMembers'] as $member): ?>
                                    <div class="p-3 border rounded-md">
                                        <p class="font-medium"><?= $member['name'] ?></p>
                                        <p class="text-sm text-gray-500"><?= $member['role'] ?></p>
                                        <a href="mailto:<?= $member['email'] ?>" class="text-sm text-[#4b84c7] hover:underline">
                                            <?= $member['email'] ?>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </section>

                        <!-- Professor -->
                        <section class="mb-6">
                            <h2 class="text-lg font-bold mb-3">Faculty Advisor</h2>
                            <div class="p-3 border rounded-md">
                                <p class="font-medium"><?= $project['professor'] ?></p>
                                <p class="text-sm text-gray-500">Faculty Advisor</p>
                            </div>
                        </section>

                        <!-- Sponsor -->
                        <section class="mb-6">
                            <h2 class="text-lg font-bold mb-3">Project Sponsor</h2>
                            <div class="p-3 border rounded-md">
                                <p class="font-medium"><?= $project['sponsor']['name'] ?></p>
                                <p class="text-sm text-gray-500"><?= $project['sponsor']['organization'] ?></p>
                                <a href="mailto:<?= $project['sponsor']['email'] ?>" class="text-sm text-[#4b84c7] hover:underline">
                                    <?= $project['sponsor']['email'] ?>
                                </a>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
