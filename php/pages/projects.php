<?php
// Sample data 
$courses = [
    "Bioinformatics",
    "Business Information Management",
    "Computer Game Science",
    "Computer Science",
    "Computer Science and Engineering",
    "Data Science",
    "Digital Information Systems",
    "Game Design and Interactive Media",
    "Health Informatics",
    "Human Computer Interaction and Design",
    "Information and Computer Science",
    "Informatics",
    "Networked Systems",
    "Software Engineering",
    "Statistics",
];

// Alphabetized project tags
$projectTags = [
    "Accessibility",
    "Agriculture",
    "AR/VR",
    "Art & Design",
    "Artificial Intelligence",
    "Blockchain",
    "Cybersecurity",
    "Data Science",
    "Education",
    "Entertainment",
    "Environmental",
    "Finance",
    "Fitness",
    "Food Service",
    "Game Development",
    "Healthcare",
    "IoT",
    "Machine Learning",
    "Medical",
    "Mobile Apps",
    "Music",
    "Restaurant",
    "Retail",
    "Science",
    "Social Impact",
    "Sports",
    "Sustainability",
    "Transportation",
    "Travel",
    "Web Development",
];

// Sample professors
$professors = [
    "Dr. Jane Smith",
    "Dr. John Doe",
    "Dr. Emily Johnson",
    "Dr. Michael Brown",
    "Dr. Sarah Williams",
    "Dr. David Miller",
    "Dr. Lisa Davis",
    "Dr. Robert Wilson",
    "Dr. Jennifer Taylor",
    "Dr. Richard Anderson",
];

// Sample partners/sponsors
$partners = [
    "Google",
    "Microsoft",
    "Apple",
    "Amazon",
    "Meta",
    "IBM",
    "Intel",
    "Cisco",
    "Oracle",
    "Adobe",
    "UCI Health",
    "UCI Medical Center",
    "City of Irvine",
    "Orange County",
    "NASA JPL",
];

// Degrees list
$degrees = ["BS", "BA", "Professional Master's", "MS", "PhD"];

// Available years for filtering
$years = ["2025", "2026", "2027"];

// Available quarters for filtering
$quarters = ["Fall", "Winter", "Spring", "Summer"];

// Get filter values from URL parameters
$selectedYear = isset($_GET['year']) ? $_GET['year'] : null;
$selectedQuarter = isset($_GET['quarter']) ? $_GET['quarter'] : null;
$selectedDegree = isset($_GET['degree']) ? $_GET['degree'] : null;
$selectedCourse = isset($_GET['course']) ? $_GET['course'] : null;
$selectedProfessor = isset($_GET['professor']) ? $_GET['professor'] : null;
$selectedPartner = isset($_GET['partner']) ? $_GET['partner'] : null;
$showAwardWinners = isset($_GET['award']) ? true : false;
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'alphabetical';
$selectedTags = isset($_GET['tags']) ? explode(',', $_GET['tags']) : [];

// Generate sample projects
$allProjects = [];
for ($i = 0; $i < 12; $i++) {
    // Assign degrees based on index
    if ($i % 5 === 0) $degree = "BS";
    else if ($i % 5 === 1) $degree = "BA";
    else if ($i % 5 === 2) $degree = "Professional Master's";
    else if ($i % 5 === 3) $degree = "MS";
    else $degree = "PhD";

    $project = [
        'id' => $i + 1,
        'title' => 'Title',
        'description' => 'Project Description: Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        'isAwardWinner' => $i < 3, 
        'rank' => $i < 3 ? $i + 1 : null, 
        'date' => date('Y-m-d', strtotime('2025-' . (($i % 4) + 1) . '-15')), // Random dates in 2025
        'year' => '2025',
        'quarter' => $quarters[$i % 4],
        'degree' => $degree,
        'course' => $courses[$i % count($courses)],
        'professor' => $professors[$i % count($professors)],
        'partner' => $partners[$i % count($partners)],
        'tags' => [
            $projectTags[$i % count($projectTags)], 
            $projectTags[($i + 7) % count($projectTags)]
        ]
    ];
    $allProjects[] = $project;
}

// Apply filters
$filteredProjects = $allProjects;

// Get search term from URL
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Apply award winners filter if selected
if ($showAwardWinners) {
    $filteredProjects = array_filter($filteredProjects, function($project) {
        return $project['isAwardWinner'];
    });
}

// Apply year filter if selected
if ($selectedYear) {
    $filteredProjects = array_filter($filteredProjects, function($project) use ($selectedYear) {
        return $project['year'] === $selectedYear;
    });
}

// Apply quarter filter if selected
if ($selectedQuarter) {
    $filteredProjects = array_filter($filteredProjects, function($project) use ($selectedQuarter) {
        return $project['quarter'] === $selectedQuarter;
    });
}

// Apply degree filter if selected
if ($selectedDegree) {
    $filteredProjects = array_filter($filteredProjects, function($project) use ($selectedDegree) {
        return $project['degree'] === $selectedDegree;
    });
}

// Apply course filter if selected
if ($selectedCourse) {
    $filteredProjects = array_filter($filteredProjects, function($project) use ($selectedCourse) {
        return $project['course'] === $selectedCourse;
    });
}

// Apply professor filter if selected
if ($selectedProfessor) {
    $filteredProjects = array_filter($filteredProjects, function($project) use ($selectedProfessor) {
        return $project['professor'] === $selectedProfessor;
    });
}

// Apply tags filter if selected
if (!empty($selectedTags)) {
    $filteredProjects = array_filter($filteredProjects, function($project) use ($selectedTags) {
        foreach ($selectedTags as $tag) {
            if (in_array($tag, $project['tags'])) {
                return true;
            }
        }
        return false;
    });
}

// Apply partner filter if selected
if ($selectedPartner) {
    $filteredProjects = array_filter($filteredProjects, function($project) use ($selectedPartner) {
        return $project['partner'] === $selectedPartner;
    });
}

// Apply search filter if provided
if (!empty($searchTerm)) {
    $filteredProjects = array_filter($filteredProjects, function($project) use ($searchTerm) {
        // Search in title, description, and tags
        return (
            stripos($project['title'], $searchTerm) !== false || 
            stripos($project['description'], $searchTerm) !== false ||
            count(array_filter($project['tags'], function($tag) use ($searchTerm) {
                return stripos($tag, $searchTerm) !== false;
            })) > 0
        );
    });
}

// Limit to 8 projects when not filtering (for pagination)
if (!$showAwardWinners && !$selectedYear && !$selectedQuarter && !$selectedDegree && 
    !$selectedCourse && !$selectedProfessor && empty($selectedTags) && !$selectedPartner && empty($searchTerm)) {
    $filteredProjects = array_slice($filteredProjects, 0, 8);
}

// Sort projects based on selected sort option
usort($filteredProjects, function($a, $b) use ($sortBy, $showAwardWinners) {
    if ($showAwardWinners && $a['isAwardWinner'] && $b['isAwardWinner']) {
        return ($a['rank'] ?? 999) - ($b['rank'] ?? 999); 
    }

    if ($sortBy === 'newest') return strtotime($b['date']) - strtotime($a['date']);
    if ($sortBy === 'oldest') return strtotime($a['date']) - strtotime($b['date']);
    if ($sortBy === 'alphabetical') return strcmp($a['title'], $b['title']);
    return 0;
});

// Projects Heading 
$projectsHeading = ($selectedYear ? $selectedYear . ' ' : '') . 
                  ($selectedQuarter ? $selectedQuarter . ' ' : '') . 
                  ($selectedDegree ? $selectedDegree . ' ' : '') . 
                  ($showAwardWinners ? 'Award-Winning' : 'Archived') . 
                  ' Capstone Projects';
?>

<!-- Projects Content -->
<div class="flex-1 flex flex-col items-center bg-gray-50">
    <div class="w-full max-w-6xl mx-auto py-8 px-6">
        <!-- Search and Sort Row -->
        <div class="flex flex-wrap justify-between items-center mb-6">
            <!-- Search Bar -->
            <div class="relative w-full sm:w-[400px] max-w-full">
                <form method="GET" action="index.php" class="w-full">
                    <input type="hidden" name="page" value="projects">
                    <?php if ($selectedYear) echo '<input type="hidden" name="year" value="' . htmlspecialchars($selectedYear) . '">'; ?>
                    <?php if ($selectedQuarter) echo '<input type="hidden" name="quarter" value="' . htmlspecialchars($selectedQuarter) . '">'; ?>
                    <?php if ($selectedDegree) echo '<input type="hidden" name="degree" value="' . htmlspecialchars($selectedDegree) . '">'; ?>
                    <?php if ($selectedCourse) echo '<input type="hidden" name="course" value="' . htmlspecialchars($selectedCourse) . '">'; ?>
                    <?php if ($selectedProfessor) echo '<input type="hidden" name="professor" value="' . htmlspecialchars($selectedProfessor) . '">'; ?>
                    <?php if ($selectedPartner) echo '<input type="hidden" name="partner" value="' . htmlspecialchars($selectedPartner) . '">'; ?>
                    <?php if ($showAwardWinners) echo '<input type="hidden" name="award" value="1">'; ?>
                    <?php if (!empty($selectedTags)) echo '<input type="hidden" name="tags" value="' . htmlspecialchars(implode(',', $selectedTags)) . '">'; ?>
                    <?php if ($sortBy) echo '<input type="hidden" name="sort" value="' . htmlspecialchars($sortBy) . '">'; ?>
                    
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search"
                            value="<?= htmlspecialchars($searchTerm) ?>"
                            placeholder="Search..." 
                            class="w-full py-2 px-4 pr-10 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#4b84c7]"
                        >
                        <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Sort Dropdown -->
            <div class="relative mt-4 sm:mt-0">
                <form method="GET" action="index.php" class="flex items-center gap-2">
                    <span class="text-sm text-gray-700">Sort by:</span>
                    <input type="hidden" name="page" value="projects">
                    <?php if ($selectedYear) echo '<input type="hidden" name="year" value="' . htmlspecialchars($selectedYear) . '">'; ?>
                    <?php if ($selectedQuarter) echo '<input type="hidden" name="quarter" value="' . htmlspecialchars($selectedQuarter) . '">'; ?>
                    <?php if ($selectedDegree) echo '<input type="hidden" name="degree" value="' . htmlspecialchars($selectedDegree) . '">'; ?>
                    <?php if ($selectedCourse) echo '<input type="hidden" name="course" value="' . htmlspecialchars($selectedCourse) . '">'; ?>
                    <?php if ($selectedProfessor) echo '<input type="hidden" name="professor" value="' . htmlspecialchars($selectedProfessor) . '">'; ?>
                    <?php if ($selectedPartner) echo '<input type="hidden" name="partner" value="' . htmlspecialchars($selectedPartner) . '">'; ?>
                    <?php if ($showAwardWinners) echo '<input type="hidden" name="award" value="1">'; ?>
                    <?php if (!empty($selectedTags)) echo '<input type="hidden" name="tags" value="' . htmlspecialchars(implode(',', $selectedTags)) . '">'; ?>
                    <?php if ($searchTerm) echo '<input type="hidden" name="search" value="' . htmlspecialchars($searchTerm) . '">'; ?>
                    
                    <select name="sort" onchange="this.form.submit()" class="w-[150px] px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#4b84c7] text-sm">
                        <option value="alphabetical" <?= $sortBy === 'alphabetical' ? 'selected' : '' ?>>A-Z</option>
                        <option value="newest" <?= $sortBy === 'newest' ? 'selected' : '' ?>>Newest First</option>
                        <option value="oldest" <?= $sortBy === 'oldest' ? 'selected' : '' ?>>Oldest First</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex flex-wrap gap-3 mb-8 items-center">
            <!-- Year Filter -->
            <div class="relative">
                <form method="GET" action="index.php" class="flex items-center">
                    <input type="hidden" name="page" value="projects">
                    <?php if ($selectedQuarter) echo '<input type="hidden" name="quarter" value="' . htmlspecialchars($selectedQuarter) . '">'; ?>
                    <?php if ($selectedDegree) echo '<input type="hidden" name="degree" value="' . htmlspecialchars($selectedDegree) . '">'; ?>
                    <?php if ($selectedCourse) echo '<input type="hidden" name="course" value="' . htmlspecialchars($selectedCourse) . '">'; ?>
                    <?php if ($selectedProfessor) echo '<input type="hidden" name="professor" value="' . htmlspecialchars($selectedProfessor) . '">'; ?>
                    <?php if ($selectedPartner) echo '<input type="hidden" name="partner" value="' . htmlspecialchars($selectedPartner) . '">'; ?>
                    <?php if ($showAwardWinners) echo '<input type="hidden" name="award" value="1">'; ?>
                    <?php if (!empty($selectedTags)) echo '<input type="hidden" name="tags" value="' . htmlspecialchars(implode(',', $selectedTags)) . '">'; ?>
                    <?php if ($sortBy) echo '<input type="hidden" name="sort" value="' . htmlspecialchars($sortBy) . '">'; ?>
                    <?php if ($searchTerm) echo '<input type="hidden" name="search" value="' . htmlspecialchars($searchTerm) . '">'; ?>
                    
                    <select name="year" onchange="this.form.submit()" class="w-[150px] px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#4b84c7] text-sm">
                        <option value="">All Years</option>
                        <?php foreach ($years as $year): ?>
                            <option value="<?= $year ?>" <?= $selectedYear === $year ? 'selected' : '' ?>>
                                <?= $year ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>

            <!-- Quarter Filter -->
            <div class="relative">
                <form method="GET" action="index.php" class="flex items-center">
                    <input type="hidden" name="page" value="projects">
                    <?php if ($selectedYear) echo '<input type="hidden" name="year" value="' . htmlspecialchars($selectedYear) . '">'; ?>
                    <?php if ($selectedDegree) echo '<input type="hidden" name="degree" value="' . htmlspecialchars($selectedDegree) . '">'; ?>
                    <?php if ($selectedCourse) echo '<input type="hidden" name="course" value="' . htmlspecialchars($selectedCourse) . '">'; ?>
                    <?php if ($selectedProfessor) echo '<input type="hidden" name="professor" value="' . htmlspecialchars($selectedProfessor) . '">'; ?>
                    <?php if ($selectedPartner) echo '<input type="hidden" name="partner" value="' . htmlspecialchars($selectedPartner) . '">'; ?>
                    <?php if ($showAwardWinners) echo '<input type="hidden" name="award" value="1">'; ?>
                    <?php if (!empty($selectedTags)) echo '<input type="hidden" name="tags" value="' . htmlspecialchars(implode(',', $selectedTags)) . '">'; ?>
                    <?php if ($sortBy) echo '<input type="hidden" name="sort" value="' . htmlspecialchars($sortBy) . '">'; ?>
                    <?php if ($searchTerm) echo '<input type="hidden" name="search" value="' . htmlspecialchars($searchTerm) . '">'; ?>
                    
                    <select name="quarter" onchange="this.form.submit()" class="w-[150px] px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#4b84c7] text-sm">
                        <option value="">All Quarters</option>
                        <?php foreach ($quarters as $quarter): ?>
                            <option value="<?= $quarter ?>" <?= $selectedQuarter === $quarter ? 'selected' : '' ?>>
                                <?= $quarter ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>

            <!-- Degree Filter -->
            <div class="relative">
                <form method="GET" action="index.php" class="flex items-center">
                    <input type="hidden" name="page" value="projects">
                    <?php if ($selectedYear) echo '<input type="hidden" name="year" value="' . htmlspecialchars($selectedYear) . '">'; ?>
                    <?php if ($selectedQuarter) echo '<input type="hidden" name="quarter" value="' . htmlspecialchars($selectedQuarter) . '">'; ?>
                    <?php if ($selectedCourse) echo '<input type="hidden" name="course" value="' . htmlspecialchars($selectedCourse) . '">'; ?>
                    <?php if ($selectedProfessor) echo '<input type="hidden" name="professor" value="' . htmlspecialchars($selectedProfessor) . '">'; ?>
                    <?php if ($selectedPartner) echo '<input type="hidden" name="partner" value="' . htmlspecialchars($selectedPartner) . '">'; ?>
                    <?php if ($showAwardWinners) echo '<input type="hidden" name="award" value="1">'; ?>
                    <?php if (!empty($selectedTags)) echo '<input type="hidden" name="tags" value="' . htmlspecialchars(implode(',', $selectedTags)) . '">'; ?>
                    <?php if ($sortBy) echo '<input type="hidden" name="sort" value="' . htmlspecialchars($sortBy) . '">'; ?>
                    <?php if ($searchTerm) echo '<input type="hidden" name="search" value="' . htmlspecialchars($searchTerm) . '">'; ?>
                    
                    <select name="degree" onchange="this.form.submit()" class="w-[150px] px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#4b84c7] text-sm">
                        <option value="">All Degrees</option>
                        <?php foreach ($degrees as $degree): ?>
                            <option value="<?= $degree ?>" <?= $selectedDegree === $degree ? 'selected' : '' ?>>
                                <?= $degree ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>

            <!-- Course Filter -->
            <div class="relative">
                <form method="GET" action="index.php" class="flex items-center">
                    <input type="hidden" name="page" value="projects">
                    <?php if ($selectedYear) echo '<input type="hidden" name="year" value="' . htmlspecialchars($selectedYear) . '">'; ?>
                    <?php if ($selectedQuarter) echo '<input type="hidden" name="quarter" value="' . htmlspecialchars($selectedQuarter) . '">'; ?>
                    <?php if ($selectedDegree) echo '<input type="hidden" name="degree" value="' . htmlspecialchars($selectedDegree) . '">'; ?>
                    <?php if ($selectedProfessor) echo '<input type="hidden" name="professor" value="' . htmlspecialchars($selectedProfessor) . '">'; ?>
                    <?php if ($selectedPartner) echo '<input type="hidden" name="partner" value="' . htmlspecialchars($selectedPartner) . '">'; ?>
                    <?php if ($showAwardWinners) echo '<input type="hidden" name="award" value="1">'; ?>
                    <?php if (!empty($selectedTags)) echo '<input type="hidden" name="tags" value="' . htmlspecialchars(implode(',', $selectedTags)) . '">'; ?>
                    <?php if ($sortBy) echo '<input type="hidden" name="sort" value="' . htmlspecialchars($sortBy) . '">'; ?>
                    <?php if ($searchTerm) echo '<input type="hidden" name="search" value="' . htmlspecialchars($searchTerm) . '">'; ?>
                    
                    <select name="course" onchange="this.form.submit()" class="w-[150px] px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#4b84c7] text-sm">
                        <option value="">All Courses</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= $course ?>" <?= $selectedCourse === $course ? 'selected' : '' ?>>
                                <?= $course ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>

            <!-- Tags Filter -->
            <div class="relative">
                <button data-dropdown="tags-dropdown" class="flex items-center gap-1 py-2 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#4b84c7] text-sm w-[150px] <?= !empty($selectedTags) ? 'bg-[#4b84c7] text-white' : 'bg-white' ?>">
                    Tags: <?= !empty($selectedTags) ? count($selectedTags) . ' selected' : 'All' ?>
                    <i class="fas fa-chevron-down text-xs ml-1"></i>
                </button>

                <div id="tags-dropdown" class="dropdown-content hidden absolute left-0 z-10 mt-1 w-64 origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                    <div class="py-1 max-h-60 overflow-auto">
                        <div class="px-4 py-2 border-b">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium">Select Tags</span>
                                <?php if (!empty($selectedTags)): ?>
                                    <a href="?page=projects<?= $sortBy !== 'alphabetical' ? '&sort='.$sortBy : '' ?><?= $showAwardWinners ? '&award=1' : '' ?><?= $selectedYear ? '&year='.$selectedYear : '' ?><?= $selectedQuarter ? '&quarter='.$selectedQuarter : '' ?><?= $selectedDegree ? '&degree='.$selectedDegree : '' ?><?= $selectedCourse ? '&course='.$selectedCourse : '' ?><?= $selectedProfessor ? '&professor='.$selectedProfessor : '' ?><?= $selectedPartner ? '&partner='.$selectedPartner : '' ?><?= $searchTerm ? '&search='.htmlspecialchars($searchTerm) : '' ?>" class="text-xs text-[#4b84c7] hover:underline">
                                        Clear All
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <form method="GET" action="index.php" id="tags-form">
                            <input type="hidden" name="page" value="projects">
                            <?php if ($selectedYear) echo '<input type="hidden" name="year" value="' . htmlspecialchars($selectedYear) . '">'; ?>
                            <?php if ($selectedQuarter) echo '<input type="hidden" name="quarter" value="' . htmlspecialchars($selectedQuarter) . '">'; ?>
                            <?php if ($selectedDegree) echo '<input type="hidden" name="degree" value="' . htmlspecialchars($selectedDegree) . '">'; ?>
                            <?php if ($selectedCourse) echo '<input type="hidden" name="course" value="' . htmlspecialchars($selectedCourse) . '">'; ?>
                            <?php if ($selectedProfessor) echo '<input type="hidden" name="professor" value="' . htmlspecialchars($selectedProfessor) . '">'; ?>
                            <?php if ($selectedPartner) echo '<input type="hidden" name="partner" value="' . htmlspecialchars($selectedPartner) . '">'; ?>
                            <?php if ($showAwardWinners) echo '<input type="hidden" name="award" value="1">'; ?>
                            <?php if ($sortBy) echo '<input type="hidden" name="sort" value="' . htmlspecialchars($sortBy) . '">'; ?>
                            <?php if ($searchTerm) echo '<input type="hidden" name="search" value="' . htmlspecialchars($searchTerm) . '">'; ?>
                            
                            <?php foreach ($projectTags as $tag): ?>
                                <div class="px-4 py-2 flex items-center hover:bg-gray-50">
                                    <input type="checkbox" 
                                           name="tags[]" 
                                           value="<?= $tag ?>" 
                                           id="tag-<?= $tag ?>"
                                           <?= in_array($tag, $selectedTags) ? 'checked' : '' ?>
                                           onchange="this.form.submit()"
                                           class="mr-2 rounded border-gray-300 text-[#4b84c7] focus:ring-[#4b84c7]">
                                    <label for="tag-<?= $tag ?>" class="text-sm text-gray-700 cursor-pointer">
                                        <?= $tag ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Professor Filter -->
            <div class="relative">
                <form method="GET" action="index.php" class="flex items-center">
                    <input type="hidden" name="page" value="projects">
                    <?php if ($selectedYear) echo '<input type="hidden" name="year" value="' . htmlspecialchars($selectedYear) . '">'; ?>
                    <?php if ($selectedQuarter) echo '<input type="hidden" name="quarter" value="' . htmlspecialchars($selectedQuarter) . '">'; ?>
                    <?php if ($selectedDegree) echo '<input type="hidden" name="degree" value="' . htmlspecialchars($selectedDegree) . '">'; ?>
                    <?php if ($selectedCourse) echo '<input type="hidden" name="course" value="' . htmlspecialchars($selectedCourse) . '">'; ?>
                    <?php if ($selectedPartner) echo '<input type="hidden" name="partner" value="' . htmlspecialchars($selectedPartner) . '">'; ?>
                    <?php if ($showAwardWinners) echo '<input type="hidden" name="award" value="1">'; ?>
                    <?php if (!empty($selectedTags)) echo '<input type="hidden" name="tags" value="' . htmlspecialchars(implode(',', $selectedTags)) . '">'; ?>
                    <?php if ($sortBy) echo '<input type="hidden" name="sort" value="' . htmlspecialchars($sortBy) . '">'; ?>
                    <?php if ($searchTerm) echo '<input type="hidden" name="search" value="' . htmlspecialchars($searchTerm) . '">'; ?>
                    
                    <select name="professor" onchange="this.form.submit()" class="w-[150px] px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#4b84c7] text-sm">
                        <option value="">All Professors</option>
                        <?php foreach ($professors as $professor): ?>
                            <option value="<?= $professor ?>" <?= $selectedProfessor === $professor ? 'selected' : '' ?>>
                                <?= $professor ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>

            <!-- Partners Filter -->
            <div class="relative">
                <form method="GET" action="index.php" class="flex items-center">
                    <input type="hidden" name="page" value="projects">
                    <?php if ($selectedYear) echo '<input type="hidden" name="year" value="' . htmlspecialchars($selectedYear) . '">'; ?>
                    <?php if ($selectedQuarter) echo '<input type="hidden" name="quarter" value="' . htmlspecialchars($selectedQuarter) . '">'; ?>
                    <?php if ($selectedDegree) echo '<input type="hidden" name="degree" value="' . htmlspecialchars($selectedDegree) . '">'; ?>
                    <?php if ($selectedCourse) echo '<input type="hidden" name="course" value="' . htmlspecialchars($selectedCourse) . '">'; ?>
                    <?php if ($selectedProfessor) echo '<input type="hidden" name="professor" value="' . htmlspecialchars($selectedProfessor) . '">'; ?>
                    <?php if ($showAwardWinners) echo '<input type="hidden" name="award" value="1">'; ?>
                    <?php if (!empty($selectedTags)) echo '<input type="hidden" name="tags" value="' . htmlspecialchars(implode(',', $selectedTags)) . '">'; ?>
                    <?php if ($sortBy) echo '<input type="hidden" name="sort" value="' . htmlspecialchars($sortBy) . '">'; ?>
                    <?php if ($searchTerm) echo '<input type="hidden" name="search" value="' . htmlspecialchars($searchTerm) . '">'; ?>
                    
                    <select name="partner" onchange="this.form.submit()" class="w-[150px] px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#4b84c7] text-sm">
                        <option value="">All Partners</option>
                        <?php foreach ($partners as $partner): ?>
                            <option value="<?= $partner ?>" <?= $selectedPartner === $partner ? 'selected' : '' ?>>
                                <?= $partner ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>

            <!-- Award Winners Checkbox -->
            <div class="flex items-center gap-2 ml-2">
                <form method="GET" action="index.php" class="flex items-center">
                    <input type="hidden" name="page" value="projects">
                    <?php if ($selectedYear) echo '<input type="hidden" name="year" value="' . htmlspecialchars($selectedYear) . '">'; ?>
                    <?php if ($selectedQuarter) echo '<input type="hidden" name="quarter" value="' . htmlspecialchars($selectedQuarter) . '">'; ?>
                    <?php if ($selectedDegree) echo '<input type="hidden" name="degree" value="' . htmlspecialchars($selectedDegree) . '">'; ?>
                    <?php if ($selectedCourse) echo '<input type="hidden" name="course" value="' . htmlspecialchars($selectedCourse) . '">'; ?>
                    <?php if ($selectedProfessor) echo '<input type="hidden" name="professor" value="' . htmlspecialchars($selectedProfessor) . '">'; ?>
                    <?php if ($selectedPartner) echo '<input type="hidden" name="partner" value="' . htmlspecialchars($selectedPartner) . '">'; ?>
                    <?php if (!empty($selectedTags)) echo '<input type="hidden" name="tags" value="' . htmlspecialchars(implode(',', $selectedTags)) . '">'; ?>
                    <?php if ($sortBy) echo '<input type="hidden" name="sort" value="' . htmlspecialchars($sortBy) . '">'; ?>
                    <?php if ($searchTerm) echo '<input type="hidden" name="search" value="' . htmlspecialchars($searchTerm) . '">'; ?>
                    
                    <input type="checkbox" name="award" value="1" <?= $showAwardWinners ? 'checked' : '' ?> 
                           onchange="this.form.submit()" class="rounded border-gray-300 text-[#4b84c7] focus:ring-[#4b84c7]">
                    <label class="ml-2 text-sm text-gray-700">Show Award Winners Only</label>
                </form>
            </div>
        </div>

        <!-- Active Filters -->
        <?php if ($selectedYear || $selectedQuarter || $selectedDegree || $selectedCourse || $selectedProfessor || !empty($selectedTags) || $selectedPartner || $searchTerm): ?>
            <div class="flex flex-wrap gap-2 mb-4">
                <span class="text-sm text-gray-500">Active filters:</span>

                <?php if ($selectedYear): ?>
                    <span class="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 text-xs">
                        Year: <?= $selectedYear ?>
                        <a href="?page=projects<?= $sortBy !== 'alphabetical' ? '&sort='.$sortBy : '' ?><?= $showAwardWinners ? '&award=1' : '' ?><?= $selectedQuarter ? '&quarter='.$selectedQuarter : '' ?><?= $selectedDegree ? '&degree='.$selectedDegree : '' ?><?= $selectedCourse ? '&course='.$selectedCourse : '' ?><?= $selectedProfessor ? '&professor='.$selectedProfessor : '' ?><?= !empty($selectedTags) ? '&tags='.implode(',', $selectedTags) : '' ?><?= $selectedPartner ? '&partner='.$selectedPartner : '' ?><?= $searchTerm ? '&search='.htmlspecialchars($searchTerm) : '' ?>" class="ml-1">
                            <i class="fas fa-times text-xs"></i>
                        </a>
                    </span>
                <?php endif; ?>

                <?php if ($selectedQuarter): ?>
                    <span class="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 text-xs">
                        Quarter: <?= $selectedQuarter ?>
                        <a href="?page=projects<?= $sortBy !== 'alphabetical' ? '&sort='.$sortBy : '' ?><?= $showAwardWinners ? '&award=1' : '' ?><?= $selectedYear ? '&year='.$selectedYear : '' ?><?= $selectedDegree ? '&degree='.$selectedDegree : '' ?><?= $selectedCourse ? '&course='.$selectedCourse : '' ?><?= $selectedProfessor ? '&professor='.$selectedProfessor : '' ?><?= !empty($selectedTags) ? '&tags='.implode(',', $selectedTags) : '' ?><?= $selectedPartner ? '&partner='.$selectedPartner : '' ?><?= $searchTerm ? '&search='.htmlspecialchars($searchTerm) : '' ?>" class="ml-1">
                            <i class="fas fa-times text-xs"></i>
                        </a>
                    </span>
                <?php endif; ?>

                <?php if ($selectedDegree): ?>
                    <span class="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 text-xs">
                        Degree: <?= $selectedDegree ?>
                        <a href="?page=projects<?= $sortBy !== 'alphabetical' ? '&sort='.$sortBy : '' ?><?= $showAwardWinners ? '&award=1' : '' ?><?= $selectedYear ? '&year='.$selectedYear : '' ?><?= $selectedQuarter ? '&quarter='.$selectedQuarter : '' ?><?= $selectedCourse ? '&course='.$selectedCourse : '' ?><?= $selectedProfessor ? '&professor='.$selectedProfessor : '' ?><?= !empty($selectedTags) ? '&tags='.implode(',', $selectedTags) : '' ?><?= $selectedPartner ? '&partner='.$selectedPartner : '' ?><?= $searchTerm ? '&search='.htmlspecialchars($searchTerm) : '' ?>" class="ml-1">
                            <i class="fas fa-times text-xs"></i>
                        </a>
                    </span>
                <?php endif; ?>

                <?php if ($selectedCourse): ?>
                    <span class="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 text-xs">
                        Course: <?= $selectedCourse ?>
                        <a href="?page=projects<?= $sortBy !== 'alphabetical' ? '&sort='.$sortBy : '' ?><?= $showAwardWinners ? '&award=1' : '' ?><?= $selectedYear ? '&year='.$selectedYear : '' ?><?= $selectedQuarter ? '&quarter='.$selectedQuarter : '' ?><?= $selectedDegree ? '&degree='.$selectedDegree : '' ?><?= $selectedProfessor ? '&professor='.$selectedProfessor : '' ?><?= !empty($selectedTags) ? '&tags='.implode(',', $selectedTags) : '' ?><?= $selectedPartner ? '&partner='.$selectedPartner : '' ?><?= $searchTerm ? '&search='.htmlspecialchars($searchTerm) : '' ?>" class="ml-1">
                            <i class="fas fa-times text-xs"></i>
                        </a>
                    </span>
                <?php endif; ?>

                <?php if ($selectedProfessor): ?>
                    <span class="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 text-xs">
                        Professor: <?= explode(' ', $selectedProfessor)[1] ?>
                        <a href="?page=projects<?= $sortBy !== 'alphabetical' ? '&sort='.$sortBy : '' ?><?= $showAwardWinners ? '&award=1' : '' ?><?= $selectedYear ? '&year='.$selectedYear : '' ?><?= $selectedQuarter ? '&quarter='.$selectedQuarter : '' ?><?= $selectedDegree ? '&degree='.$selectedDegree : '' ?><?= $selectedCourse ? '&course='.$selectedCourse : '' ?><?= !empty($selectedTags) ? '&tags='.implode(',', $selectedTags) : '' ?><?= $selectedPartner ? '&partner='.$selectedPartner : '' ?><?= $searchTerm ? '&search='.htmlspecialchars($searchTerm) : '' ?>" class="ml-1">
                            <i class="fas fa-times text-xs"></i>
                        </a>
                    </span>
                <?php endif; ?>

                <?php foreach ($selectedTags as $tag): ?>
                    <span class="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 text-xs">
                        Tag: <?= $tag ?>
                        <a href="?page=projects<?= $sortBy !== 'alphabetical' ? '&sort='.$sortBy : '' ?><?= $showAwardWinners ? '&award=1' : '' ?><?= $selectedYear ? '&year='.$selectedYear : '' ?><?= $selectedQuarter ? '&quarter='.$selectedQuarter : '' ?><?= $selectedDegree ? '&degree='.$selectedDegree : '' ?><?= $selectedCourse ? '&course='.$selectedCourse : '' ?><?= $selectedProfessor ? '&professor='.$selectedProfessor : '' ?><?= !empty(array_diff($selectedTags, [$tag])) ? '&tags='.implode(',', array_diff($selectedTags, [$tag])) : '' ?><?= $selectedPartner ? '&partner='.$selectedPartner : '' ?><?= $searchTerm ? '&search='.htmlspecialchars($searchTerm) : '' ?>" class="ml-1">
                            <i class="fas fa-times text-xs"></i>
                        </a>
                    </span>
                <?php endforeach; ?>

                <?php if ($selectedPartner): ?>
                    <span class="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 text-xs">
                        Partner: <?= $selectedPartner ?>
                        <a href="?page=projects<?= $sortBy !== 'alphabetical' ? '&sort='.$sortBy : '' ?><?= $showAwardWinners ? '&award=1' : '' ?><?= $selectedYear ? '&year='.$selectedYear : '' ?><?= $selectedQuarter ? '&quarter='.$selectedQuarter : '' ?><?= $selectedDegree ? '&degree='.$selectedDegree : '' ?><?= $selectedCourse ? '&course='.$selectedCourse : '' ?><?= $selectedProfessor ? '&professor='.$selectedProfessor : '' ?><?= !empty($selectedTags) ? '&tags='.implode(',', $selectedTags) : '' ?><?= $searchTerm ? '&search='.htmlspecialchars($searchTerm) : '' ?>" class="ml-1">
                            <i class="fas fa-times text-xs"></i>
                        </a>
                    </span>
                <?php endif; ?>

                <?php if ($searchTerm): ?>
                    <span class="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 text-xs">
                        Search: <?= htmlspecialchars($searchTerm) ?>
                        <a href="?page=projects<?= $sortBy !== 'alphabetical' ? '&sort='.$sortBy : '' ?><?= $showAwardWinners ? '&award=1' : '' ?><?= $selectedYear ? '&year='.$selectedYear : '' ?><?= $selectedQuarter ? '&quarter='.$selectedQuarter : '' ?><?= $selectedDegree ? '&degree='.$selectedDegree : '' ?><?= $selectedCourse ? '&course='.$selectedCourse : '' ?><?= $selectedProfessor ? '&professor='.$selectedProfessor : '' ?><?= !empty($selectedTags) ? '&tags='.implode(',', $selectedTags) : '' ?><?= $selectedPartner ? '&partner='.$selectedPartner : '' ?>" class="ml-1">
                            <i class="fas fa-times text-xs"></i>
                        </a>
                    </span>
                <?php endif; ?>

                <a href="?page=projects" class="text-xs text-[#4b84c7] hover:underline">
                    Clear all filters
                </a>
            </div>
        <?php endif; ?>

        <!-- Projects Heading -->
        <h2 class="text-2xl font-bold mb-6"><?= $projectsHeading ?></h2>

        <!-- Projects Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <?php foreach ($filteredProjects as $project): ?>
                <a href="index.php?page=project-detail&id=<?= $project['id'] ?>" class="flex flex-col hover:shadow-md transition-shadow rounded-md overflow-hidden">
                    <div class="flex justify-between items-center mb-2 p-2">
                        <h3 class="font-medium"><?= $project['title'] ?></h3>
                        <?php if ($project['isAwardWinner'] && $showAwardWinners): ?>
                            <div class="bg-[#f8e858] text-black px-2 py-1 rounded-full text-xs font-bold">
                                #<?= $project['rank'] ?> Winner
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="bg-[#d9d9d9] aspect-square mb-2 <?= $project['isAwardWinner'] && $showAwardWinners ? 'border-2 border-[#f8e858]' : '' ?>"></div>
                    <div class="p-2">
                        <p class="text-sm mb-2"><?= $project['description'] ?></p>

                        <!-- Project metadata -->
                        <div class="mt-auto">
                            <div class="text-xs text-gray-500 mb-1">
                                <?= $project['course'] ?> • <?= $project['degree'] ?> • <?= date('M Y', strtotime($project['date'])) ?>
                            </div>
                            <div class="flex flex-wrap gap-1">
                                <?php foreach ($project['tags'] as $tag): ?>
                                    <span class="inline-block bg-gray-100 rounded-full px-2 py-1 text-xs font-semibold text-gray-700">
                                        <?= $tag ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Pagination - Only show when not filtering for award winners -->
        <?php if (!$showAwardWinners): ?>
            <div class="flex justify-center gap-2 mt-8">
                <span class="px-3 py-1 font-bold">1</span>
                <?php for ($i = 2; $i <= 6; $i++): ?>
                    <a href="?page=projects&p=<?= $i ?><?= $searchTerm ? '&search='.htmlspecialchars($searchTerm) : '' ?>" class="px-3 py-1 hover:underline">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
                <a href="?page=projects&p=2<?= $searchTerm ? '&search='.htmlspecialchars($searchTerm) : '' ?>" class="px-3 py-1 hover:underline">
                    &gt;
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle dropdowns
    const dropdownButtons = document.querySelectorAll('[data-dropdown]');
    
    dropdownButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const dropdownId = this.getAttribute('data-dropdown');
            const dropdown = document.getElementById(dropdownId);
            
            // Close all other dropdowns
            document.querySelectorAll('.dropdown-content').forEach(content => {
                if (content.id !== dropdownId) {
                    content.classList.add('hidden');
                }
            });
            
            // Toggle current dropdown
            dropdown.classList.toggle('hidden');
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('[data-dropdown]') && !e.target.closest('.dropdown-content')) {
            document.querySelectorAll('.dropdown-content').forEach(content => {
                content.classList.add('hidden');
            });
        }
    });

    // Handle tag checkboxes
    const tagCheckboxes = document.querySelectorAll('input[name="tags[]"]');
    tagCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const form = this.closest('form');
            if (form) {
                form.submit();
            }
        });
    });

    // Handle search functionality
    const searchInput = document.querySelector('input[name="search"]');
    const searchButton = document.querySelector('button[type="submit"]');
    
    if (searchInput && searchButton) {
        searchButton.addEventListener('click', function(e) {
            e.preventDefault();
            performSearch();
        });
        
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                performSearch();
            }
        });
    }
    
    function performSearch() {
        const searchTerm = searchInput.value.trim();
        
        // Build the URL with search parameter
        let url = '?page=projects';
        
        if (searchTerm) {
            url += '&search=' + encodeURIComponent(searchTerm);
        }
        
        if ('<?= $sortBy ?>' !== 'alphabetical') {
            url += '&sort=<?= $sortBy ?>';
        }
        
        if (<?= $showAwardWinners ? 'true' : 'false' ?>) {
            url += '&award=1';
        }
        
        if ('<?= $selectedYear ?>') {
            url += '&year=<?= $selectedYear ?>';
        }
        
        if ('<?= $selectedQuarter ?>') {
            url += '&quarter=<?= $selectedQuarter ?>';
        }
        
        if ('<?= $selectedDegree ?>') {
            url += '&degree=<?= urlencode($selectedDegree) ?>';
        }
        
        if ('<?= $selectedCourse ?>') {
            url += '&course=<?= urlencode($selectedCourse) ?>';
        }
        
        if ('<?= $selectedProfessor ?>') {
            url += '&professor=<?= urlencode($selectedProfessor) ?>';
        }
        
        if ('<?= $selectedPartner ?>') {
            url += '&partner=<?= urlencode($selectedPartner) ?>';
        }
        
        if (<?= !empty($selectedTags) ? 'true' : 'false' ?>) {
            url += '&tags=<?= implode(',', $selectedTags) ?>';
        }
        
        // Navigate to the URL
        window.location.href = url;
    }
});
</script>
