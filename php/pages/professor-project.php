<?php
// No need to check authentication here since it's already checked in index.php

// Get project ID from URL parameter
$projectId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Sample project data (in a real app, this would come from a database)
$projectData = [
  'id' => '1',
  'title' => 'Smart Health Monitoring System',
  'description' => 'A comprehensive health monitoring system for elderly patients using IoT devices and AI.',
  'longDescription' => "This capstone project aims to develop a comprehensive health monitoring system for elderly patients, leveraging IoT devices and artificial intelligence to provide real-time health tracking and emergency alerts.

The system will include wearable sensors that monitor vital signs such as heart rate, blood pressure, and body temperature. These sensors will connect to a central hub in the patient's home, which processes the data and sends it to a cloud-based platform for analysis.

Machine learning algorithms will analyze the data to detect anomalies and predict potential health issues before they become critical. The system will also include a mobile application for caregivers and family members, allowing them to monitor the patient's health status remotely and receive alerts in case of emergencies.

The project addresses the growing need for remote healthcare solutions, especially for elderly patients who prefer to age in place. By providing continuous monitoring and early detection of health issues, the system aims to improve patient outcomes, reduce hospitalizations, and provide peace of mind for caregivers.",
  'status' => 'pending', // pending, approved, rejected
  'submittedDate' => '2025-05-10',
  'quarter' => 'Spring',
  'year' => '2025',
  'degree' => 'BS',
  'course' => 'CS 180A',
  'professor' => 'Dr. Smith',
  'teamMembers' => [
    ['name' => 'Alice Johnson', 'role' => 'Team Lead', 'email' => 'alice@uci.edu'],
    ['name' => 'Bob Williams', 'role' => 'Backend Developer', 'email' => 'bob@uci.edu'],
    ['name' => 'Charlie Brown', 'role' => 'Frontend Developer', 'email' => 'charlie@uci.edu'],
    ['name' => 'Diana Miller', 'role' => 'Data Scientist', 'email' => 'diana@uci.edu'],
  ],
  'tags' => ['Healthcare', 'IoT', 'Artificial Intelligence', 'Mobile Apps'],
  'links' => ['https://project-website.com', 'https://github.com/project-repo'],
  'files' => [
    [
      'name' => 'Project Proposal.pdf',
      'type' => 'PDF',
      'size' => '1.2 MB',
      'url' => '#',
    ],
    [
      'name' => 'System Architecture.png',
      'type' => 'Image',
      'size' => '0.8 MB',
      'url' => '#',
    ],
    [
      'name' => 'Preliminary Results.xlsx',
      'type' => 'Excel',
      'size' => '0.5 MB',
      'url' => '#',
    ],
  ],
  'feedback' => [
    [
      'id' => 1,
      'author' => 'Dr. Smith',
      'date' => '2025-05-08',
      'message' => 'The project proposal looks promising. Please provide more details about the machine learning algorithms you plan to use for anomaly detection.',
    ],
    [
      'id' => 2,
      'author' => 'Alice Johnson',
      'date' => '2025-05-09',
      'message' => 'Thank you for the feedback. We plan to use a combination of supervised and unsupervised learning techniques, including isolation forests and autoencoders for anomaly detection.',
    ],
  ],
];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'approve') {
            // In a real app, update the project status in the database
            $projectData['status'] = 'approved';
            // Redirect to avoid form resubmission
            header('Location: index.php?page=professor-project&id=' . $projectId . '&status=approved');
            exit;
        } elseif ($_POST['action'] === 'reject') {
            // In a real app, update the project status in the database
            $projectData['status'] = 'rejected';
            // Redirect to avoid form resubmission
            header('Location: index.php?page=professor-project&id=' . $projectId . '&status=rejected');
            exit;
        } elseif ($_POST['action'] === 'feedback' && !empty($_POST['feedback'])) {
            // In a real app, save the feedback to the database
            $newFeedback = [
                'id' => count($projectData['feedback']) + 1,
                'author' => 'Dr. Smith',
                'date' => date('Y-m-d'),
                'message' => $_POST['feedback'],
            ];
            $projectData['feedback'][] = $newFeedback;
            // Redirect to avoid form resubmission
            header('Location: index.php?page=professor-project&id=' . $projectId . '&feedback=added');
            exit;
        }
    }
}

// Check for status message from redirect
$statusMessage = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'approved') {
        $statusMessage = 'Project has been approved successfully.';
    } elseif ($_GET['status'] === 'rejected') {
        $statusMessage = 'Project has been rejected.';
    }
} elseif (isset($_GET['feedback']) && $_GET['feedback'] === 'added') {
    $statusMessage = 'Feedback has been added successfully.';
}
?>

<div class="flex-1 bg-gray-50">
  <!-- Back button -->
  <div class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
      <a href="index.php?page=professor" class="flex items-center text-[#4b84c7] hover:underline">
        <i class="fas fa-arrow-left mr-1"></i> Back to Projects
      </a>
    </div>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <?php if (!empty($statusMessage)): ?>
      <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
        <p><?= $statusMessage ?></p>
      </div>
    <?php endif; ?>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <!-- Project header -->
      <div class="bg-[#4b84c7] text-white p-6">
        <div class="flex flex-wrap justify-between items-start">
          <div>
            <h1 class="text-3xl font-bold mb-2"><?= $projectData['title'] ?></h1>
            <p class="text-lg opacity-90 mb-4"><?= $projectData['description'] ?></p>
            <div class="flex flex-wrap gap-2 items-center text-sm">
              <span class="flex items-center">
                <i class="fas fa-calendar mr-1"></i> <?= $projectData['quarter'] ?> <?= $projectData['year'] ?>
              </span>
              <span class="flex items-center">
                <i class="fas fa-book-open mr-1"></i> <?= $projectData['course'] ?>
              </span>
              <span class="flex items-center">
                <i class="fas fa-graduation-cap mr-1"></i> <?= $projectData['degree'] ?>
              </span>
              <span class="flex items-center">
                <i class="fas fa-users mr-1"></i> <?= count($projectData['teamMembers']) ?> Team Members
              </span>
            </div>
          </div>
          <div class="mt-2 sm:mt-0">
            <div
              class="px-4 py-2 rounded-full font-bold flex items-center <?php
                if ($projectData['status'] === 'approved') {
                  echo 'bg-green-100 text-green-800';
                } elseif ($projectData['status'] === 'rejected') {
                  echo 'bg-red-100 text-red-800';
                } else {
                  echo 'bg-yellow-100 text-yellow-800';
                }
              ?>"
            >
              <?php if ($projectData['status'] === 'approved'): ?>
                <i class="fas fa-check-circle mr-1"></i> Approved
              <?php elseif ($projectData['status'] === 'rejected'): ?>
                <i class="fas fa-times mr-1"></i> Rejected
              <?php else: ?>
                <i class="fas fa-exclamation-triangle mr-1"></i> Pending Review
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Project content -->
      <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Main content - 2/3 width on desktop -->
          <div class="md:col-span-2">
            <!-- Project image -->
            <div class="bg-[#d9d9d9] aspect-video mb-6 rounded-md"></div>

            <!-- Project description -->
            <section class="mb-8">
              <h2 class="text-xl font-bold mb-4">Project Description</h2>
              <div class="prose max-w-none">
                <?php 
                  $paragraphs = explode("\n\n", $projectData['longDescription']);
                  foreach ($paragraphs as $paragraph): 
                ?>
                  <p class="mb-4"><?= $paragraph ?></p>
                <?php endforeach; ?>
              </div>
            </section>

            <!-- Project files -->
            <section class="mb-8">
              <h2 class="text-xl font-bold mb-4">Project Files</h2>
              <div class="space-y-3">
                <?php foreach ($projectData['files'] as $file): ?>
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
            <?php if (count($projectData['links']) > 0): ?>
              <section class="mb-8">
                <h2 class="text-xl font-bold mb-4">Project Links</h2>
                <div class="space-y-2">
                  <?php foreach ($projectData['links'] as $link): ?>
                    <a
                      href="<?= $link ?>"
                      target="_blank"
                      rel="noopener noreferrer"
                      class="flex items-center text-[#4b84c7] hover:underline"
                    >
                      <i class="fas fa-external-link-alt mr-2"></i> <?= $link ?>
                    </a>
                  <?php endforeach; ?>
                </div>
              </section>
            <?php endif; ?>

            <!-- Feedback and Discussion -->
            <section class="mb-8">
              <h2 class="text-xl font-bold mb-4">Feedback & Discussion</h2>
              <div class="space-y-4 mb-6">
                <?php foreach ($projectData['feedback'] as $item): ?>
                  <div class="p-4 border rounded-md">
                    <div class="flex justify-between items-center mb-2">
                      <span class="font-medium"><?= $item['author'] ?></span>
                      <span class="text-sm text-gray-500"><?= $item['date'] ?></span>
                    </div>
                    <p class="text-gray-700"><?= $item['message'] ?></p>
                  </div>
                <?php endforeach; ?>
              </div>

              <form method="post" action="">
                <input type="hidden" name="action" value="feedback">
                <label for="feedback" class="block font-medium mb-2">
                  Add Feedback
                </label>
                <textarea
                  id="feedback"
                  name="feedback"
                  rows="4"
                  class="w-full p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-[#4b84c7]"
                  placeholder="Enter your feedback here..."
                  required
                ></textarea>
                <button
                  type="submit"
                  class="mt-2 px-4 py-2 bg-[#4b84c7] text-white rounded-md hover:bg-[#3b6ba0] transition-colors"
                >
                  Submit Feedback
                </button>
              </form>
            </section>
          </div>

          <!-- Sidebar - 1/3 width on desktop -->
          <div>
            <!-- Approval Actions -->
            <section class="mb-6 p-4 border rounded-md">
              <h2 class="text-lg font-bold mb-3">Project Approval</h2>
              <p class="text-sm text-gray-600 mb-4">
                Review the project details and provide your decision below.
              </p>
              <div class="space-y-2">
                <form method="post" action="">
                  <input type="hidden" name="action" value="approve">
                  <button
                    type="submit"
                    <?= $projectData['status'] === 'approved' ? 'disabled' : '' ?>
                    class="w-full py-2 px-4 rounded-md flex items-center justify-center <?=
                      $projectData['status'] === 'approved'
                        ? 'bg-green-100 text-green-800 cursor-not-allowed'
                        : 'bg-green-600 text-white hover:bg-green-700'
                    ?>"
                  >
                    <i class="fas fa-check-circle mr-2"></i>
                    <?= $projectData['status'] === 'approved' ? 'Approved' : 'Approve Project' ?>
                  </button>
                </form>
                
                <form method="post" action="">
                  <input type="hidden" name="action" value="reject">
                  <button
                    type="submit"
                    <?= $projectData['status'] === 'rejected' ? 'disabled' : '' ?>
                    class="w-full py-2 px-4 rounded-md flex items-center justify-center <?=
                      $projectData['status'] === 'rejected'
                        ? 'bg-red-100 text-red-800 cursor-not-allowed'
                        : 'bg-red-600 text-white hover:bg-red-700'
                    ?>"
                  >
                    <i class="fas fa-times mr-2"></i>
                    <?= $projectData['status'] === 'rejected' ? 'Rejected' : 'Reject Project' ?>
                  </button>
                </form>
              </div>
            </section>

            <!-- Tags -->
            <section class="mb-6">
              <h2 class="text-lg font-bold mb-3">Project Tags</h2>
              <div class="flex flex-wrap gap-2">
                <?php foreach ($projectData['tags'] as $tag): ?>
                  <span class="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 text-sm font-medium text-gray-800">
                    <i class="fas fa-tag mr-1"></i> <?= $tag ?>
                  </span>
                <?php endforeach; ?>
              </div>
            </section>

            <!-- Team members -->
            <section class="mb-6">
              <h2 class="text-lg font-bold mb-3">Team Members</h2>
              <div class="space-y-3">
                <?php foreach ($projectData['teamMembers'] as $member): ?>
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

            <!-- Submission Info -->
            <section class="mb-6">
              <h2 class="text-lg font-bold mb-3">Submission Information</h2>
              <div class="p-3 border rounded-md">
                <div class="flex justify-between mb-2">
                  <span class="text-sm text-gray-500">Submitted On:</span>
                  <span class="text-sm font-medium"><?= $projectData['submittedDate'] ?></span>
                </div>
                <div class="flex justify-between mb-2">
                  <span class="text-sm text-gray-500">Course:</span>
                  <span class="text-sm font-medium"><?= $projectData['course'] ?></span>
                </div>
                <div class="flex justify-between mb-2">
                  <span class="text-sm text-gray-500">Term:</span>
                  <span class="text-sm font-medium">
                    <?= $projectData['quarter'] ?> <?= $projectData['year'] ?>
                  </span>
                </div>
                <div class="flex justify-between">
                  <span class="text-sm text-gray-500">Degree:</span>
                  <span class="text-sm font-medium"><?= $projectData['degree'] ?></span>
                </div>
              </div>
            </section>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
