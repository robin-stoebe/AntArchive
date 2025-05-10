<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$userRole = isset($_GET['role']) ? $_GET['role'] : 'public';

// Sample data for demonstration
$uciMajors = [
    // Undergraduate Majors (B.S.)
    "Business Information Management (B.S.)",
    "Computer Science (B.S.)",
    "Computer Science and Engineering (B.S.)",
    "Data Science (B.S.)",
    "Game Design and Interactive Media (B.S.)",
    "Information and Computer Science (B.S.)",
    "Informatics (B.S.)",
    "Software Engineering (B.S.)",

    // Master's Programs
    "Master of Computer Science",
    "Master of Data Science",
    "Master of Human Computer Interaction and Design",
    "Master of Software Engineering",

    // Master of Science (M.S.)
    "Computer Science (M.S.)",
    "Data Science (M.S.)",
    "Informatics (M.S.)",
    "Networked Systems (M.S.)",
    "Software Engineering (M.S.)",
    "Statistics (M.S.)",

    // Doctor of Philosophy (Ph.D.)
    "Computer Science (Ph.D.)",
    "Informatics (Ph.D.)",
    "Networked Systems (Ph.D.)",
    "Software Engineering (Ph.D.)",
    "Statistics (Ph.D.)",

    // Minors
    "Bioinformatics (Minor)",
    "Computer Science (Minor)",
    "Digital Information Systems (Minor)",
    "Health Informatics (Minor)",
    "Informatics (Minor)",
    "Information and Computer Science (Minor)",
    "Statistics (Minor)"
];

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

// Degrees list
$degrees = ["BS", "BA", "Professional Master's", "MS", "PhD"];

$projectTags = [
    "AR/VR",
    "Accessibility",
    "Agriculture",
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
    "Web Development"
];

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

// Sample projects data
$allProjects = array_map(function($i) use ($uciMajors, $projectTags, $professors, $partners, $courses) {
    // Assign degrees based on index
    if ($i % 5 === 0) $degree = "BS";
    else if ($i % 5 === 1) $degree = "BA";
    else if ($i % 5 === 2) $degree = "Professional Master's";
    else if ($i % 5 === 3) $degree = "MS";
    else $degree = "PhD";

    return [
        'id' => $i + 1,
        'title' => "Project Title " . ($i + 1),
        'description' => "Project Description: Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
        'isAwardWinner' => $i < 3,
        'rank' => $i < 3 ? $i + 1 : null,
        'date' => date('M Y', strtotime("2025-" . ($i % 12 + 1) . "-15")),
        'year' => "2025",
        'quarter' => ["Winter", "Spring", "Summer", "Fall"][$i % 4],
        'degree' => $degree,
        'course' => $courses[$i % count($courses)],
        'professor' => $professors[$i % count($professors)],
        'partner' => $partners[$i % count($partners)],
        'tags' => [
            $projectTags[$i % count($projectTags)],
            $projectTags[($i + 7) % count($projectTags)]
        ],
    ];
}, range(0, 11));

// State for filters and sorting
$showAwardWinners = isset($_GET['award_winners']) ? true : false;
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'alphabetical';
$selectedYear = isset($_GET['year']) ? $_GET['year'] : null;
$selectedQuarter = isset($_GET['quarter']) ? $_GET['quarter'] : null;
$selectedLevel = isset($_GET['degree']) ? $_GET['degree'] : null;
$selectedMajor = isset($_GET['course']) ? $_GET['course'] : null;
$selectedProfessor = isset($_GET['professor']) ? $_GET['professor'] : null;
$selectedTags = isset($_GET['tags']) ? explode(',', $_GET['tags']) : [];
$selectedPartner = isset($_GET['partner']) ? $_GET['partner'] : null;
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Apply all filters
$filteredProjects = $allProjects;

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

// Apply level filter if selected
if ($selectedLevel) {
    $filteredProjects = array_filter($filteredProjects, function($project) use ($selectedLevel) {
        return $project['degree'] === $selectedLevel;
    });
}

// Apply major filter if selected
if ($selectedMajor) {
    $filteredProjects = array_filter($filteredProjects, function($project) use ($selectedMajor) {
        return $project['course'] === $selectedMajor;
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
        return !empty(array_intersect($selectedTags, $project['tags']));
    });
}

// Apply partner filter if selected
if ($selectedPartner) {
    $filteredProjects = array_filter($filteredProjects, function($project) use ($selectedPartner) {
        return $project['partner'] === $selectedPartner;
    });
}

// Sort projects based on selected sort option
usort($filteredProjects, function($a, $b) use ($sortBy, $showAwardWinners) {
    if ($showAwardWinners && $a['isAwardWinner'] && $b['isAwardWinner']) {
        return ($a['rank'] ?? 999) - ($b['rank'] ?? 999);
    }

    if ($sortBy === 'newest') {
        return strtotime($b['date']) - strtotime($a['date']);
    }
    if ($sortBy === 'oldest') {
        return strtotime($a['date']) - strtotime($b['date']);
    }
    if ($sortBy === 'alphabetical') {
        return strcmp($a['title'], $b['title']);
    }
    return 0;
});

// Available years for filtering
$years = ['2025', '2026', '2027'];

// Available quarters for filtering
$quarters = ['Fall', 'Winter', 'Spring', 'Summer'];

// Available levels for filtering
$levels = ['Undergraduate', 'Graduate'];
?>

        <!-- Projects Content -->
        <div class="flex-1 flex flex-col items-center">
            <div class="w-full max-w-6xl mx-auto py-8 px-6">
                <!-- Search and Sort Row -->
                <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
                    <!-- Search Bar -->
                    <div class="relative w-full sm:w-[400px] max-w-full">
                        <input
                            type="text"
                    id="search-input"
                            placeholder="Search..."
                    value="<?php echo htmlspecialchars($searchTerm); ?>"
                            class="w-full py-2 px-4 pr-10 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#4b84c7]"
                        />
                <button id="search-button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="20"
                            height="20"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.3-4.3"/>
                        </svg>
                </button>
                    </div>

                    <!-- Sort Dropdown -->
                    <div class="relative" id="sortDropdown">
                        <button class="flex items-center gap-1 py-2 px-4 rounded-md border border-gray-300 bg-white" onclick="toggleDropdown('sort')">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                            </svg>
                            Sort by: <?php echo $sortBy === 'newest' ? 'Newest' : ($sortBy === 'oldest' ? 'Oldest' : 'A-Z'); ?>
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div id="sortDropdownContent" class="absolute right-0 z-10 mt-1 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 hidden">
                            <div class="py-1">
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => 'newest'])); ?>" 
                                   class="block px-4 py-2 text-sm w-full text-left <?php echo $sortBy === 'newest' ? 'bg-gray-100 text-gray-900' : 'text-gray-700'; ?>">
                                    Newest First
                                </a>
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => 'oldest'])); ?>" 
                                   class="block px-4 py-2 text-sm w-full text-left <?php echo $sortBy === 'oldest' ? 'bg-gray-100 text-gray-900' : 'text-gray-700'; ?>">
                                    Oldest First
                                </a>
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => 'alphabetical'])); ?>" 
                                   class="block px-4 py-2 text-sm w-full text-left <?php echo $sortBy === 'alphabetical' ? 'bg-gray-100 text-gray-900' : 'text-gray-700'; ?>">
                                    Alphabetical (A-Z)
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="flex flex-wrap gap-3 mb-8 items-center">
                    <!-- Year Filter -->
                    <div class="relative" id="yearDropdown">
                        <button class="flex items-center gap-1 py-2 px-4 rounded-full border <?php echo $selectedYear ? 'bg-[#4b84c7] text-white' : 'border-gray-300 bg-white'; ?>" onclick="toggleDropdown('year')">
                            Year: <?php echo $selectedYear ?: 'All'; ?>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div id="yearDropdownContent" class="absolute left-0 z-10 mt-1 w-40 origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 hidden">
                            <div class="py-1">
                        <a href="?<?php echo http_build_query(array_merge(['page' => 'projects'], array_diff_key($_GET, ['year' => '']))); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">All Years</a>
                                <?php foreach ($years as $year): ?>
                            <a href="?<?php echo http_build_query(array_merge(['page' => 'projects'], $_GET, ['year' => $year])); ?>" 
                                       class="block px-4 py-2 text-sm <?php echo $selectedYear === $year ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50'; ?>">
                                        <?php echo $year; ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Quarter Filter -->
                    <div class="relative" id="quarterDropdown">
                        <button class="flex items-center gap-1 py-2 px-4 rounded-full border <?php echo $selectedQuarter ? 'bg-[#4b84c7] text-white' : 'border-gray-300 bg-white'; ?>" onclick="toggleDropdown('quarter')">
                            Quarter: <?php echo $selectedQuarter ?: 'All'; ?>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div id="quarterDropdownContent" class="absolute left-0 z-10 mt-1 w-40 origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 hidden">
                            <div class="py-1">
                        <a href="?<?php echo http_build_query(array_merge(['page' => 'projects'], array_diff_key($_GET, ['quarter' => '']))); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">All Quarters</a>
                                <?php foreach ($quarters as $quarter): ?>
                            <a href="?<?php echo http_build_query(array_merge(['page' => 'projects'], $_GET, ['quarter' => $quarter])); ?>" 
                                       class="block px-4 py-2 text-sm <?php echo $selectedQuarter === $quarter ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50'; ?>">
                                        <?php echo $quarter; ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Level Filter -->
                    <div class="relative" id="levelDropdown">
                        <button class="flex items-center gap-1 py-2 px-4 rounded-full border <?php echo $selectedLevel ? 'bg-[#4b84c7] text-white' : 'border-gray-300 bg-white'; ?>" onclick="toggleDropdown('level')">
                            Degree: <?php echo $selectedLevel ?: 'All'; ?>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div id="levelDropdownContent" class="absolute left-0 z-10 mt-1 w-40 origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 hidden">
                            <div class="py-1">
                        <a href="?<?php echo http_build_query(array_merge(['page' => 'projects'], array_diff_key($_GET, ['degree' => '']))); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">All Degrees</a>
                                <?php foreach ($degrees as $degree): ?>
                            <a href="?<?php echo http_build_query(array_merge(['page' => 'projects'], $_GET, ['degree' => $degree])); ?>" 
                                       class="block px-4 py-2 text-sm <?php echo $selectedLevel === $degree ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50'; ?>">
                                        <?php echo $degree; ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Major Filter -->
                    <div class="relative" id="majorDropdown">
                        <button class="flex items-center gap-1 py-2 px-4 rounded-full border <?php echo $selectedMajor ? 'bg-[#4b84c7] text-white' : 'border-gray-300 bg-white'; ?>" onclick="toggleDropdown('major')">
                            Course: <?php echo $selectedMajor ? explode(',', $selectedMajor)[0] : 'All'; ?>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div id="majorDropdownContent" class="absolute left-0 z-10 mt-1 w-72 origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 hidden">
                            <div class="py-1 max-h-60 overflow-auto">
                        <a href="?<?php echo http_build_query(array_merge(['page' => 'projects'], array_diff_key($_GET, ['course' => '']))); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">All Courses</a>
                                <?php foreach ($courses as $course): ?>
                            <a href="?<?php echo http_build_query(array_merge(['page' => 'projects'], $_GET, ['course' => $course])); ?>" 
                                       class="block px-4 py-2 text-sm <?php echo $selectedMajor === $course ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50'; ?>">
                                        <?php echo $course; ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Professor Filter -->
                    <div class="relative" id="professorDropdown">
                        <button class="flex items-center gap-1 py-2 px-4 rounded-full border <?php echo $selectedProfessor ? 'bg-[#4b84c7] text-white' : 'border-gray-300 bg-white'; ?>" onclick="toggleDropdown('professor')">
                            Professor: <?php echo $selectedProfessor ? explode(' ', $selectedProfessor)[1] : 'All'; ?>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div id="professorDropdownContent" class="absolute left-0 z-10 mt-1 w-48 origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 hidden">
                            <div class="py-1 max-h-60 overflow-auto">
                        <a href="?<?php echo http_build_query(array_merge(['page' => 'projects'], array_diff_key($_GET, ['professor' => '']))); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">All Professors</a>
                                <?php foreach ($professors as $professor): ?>
                            <a href="?<?php echo http_build_query(array_merge(['page' => 'projects'], $_GET, ['professor' => $professor])); ?>" 
                                       class="block px-4 py-2 text-sm <?php echo $selectedProfessor === $professor ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50'; ?>">
                                        <?php echo $professor; ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Tags Filter -->
                    <div class="relative" id="tagsDropdown">
                        <button class="flex items-center gap-1 py-2 px-4 rounded-full border <?php echo !empty($selectedTags) ? 'bg-[#4b84c7] text-white' : 'border-gray-300 bg-white'; ?>" onclick="toggleDropdown('tags')">
                            Tags: <?php echo !empty($selectedTags) ? count($selectedTags) . ' selected' : 'All'; ?>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div id="tagsDropdownContent" class="absolute left-0 z-10 mt-1 w-64 origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 hidden">
                            <div class="py-1 max-h-60 overflow-auto">
                                <div class="px-4 py-2 border-b">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium">Select Tags</span>
                                        <?php if (!empty($selectedTags)): ?>
                                    <a href="?<?php echo http_build_query(array_merge(['page' => 'projects'], array_diff_key($_GET, ['tags' => '']))); ?>" class="text-xs text-[#4b84c7] hover:underline">Clear All</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php foreach ($projectTags as $tag): ?>
                                    <div class="px-4 py-2 flex items-center">
                                        <input
                                            type="checkbox"
                                            id="tag-<?php echo $tag; ?>"
                                            class="mr-2"
                                            <?php echo in_array($tag, $selectedTags) ? 'checked' : ''; ?>
                                            onchange="toggleTag('<?php echo $tag; ?>')"
                                        >
                                        <label for="tag-<?php echo $tag; ?>" class="text-sm text-gray-700 cursor-pointer">
                                            <?php echo $tag; ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                                <div class="px-4 py-2 border-t">
                                    <button onclick="toggleDropdown('tags')" class="w-full py-1 px-2 bg-[#4b84c7] text-white rounded text-sm">
                                        Apply
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Partners Filter -->
                    <div class="relative" id="partnerDropdown">
                        <button class="flex items-center gap-1 py-2 px-4 rounded-full border <?php echo $selectedPartner ? 'bg-[#4b84c7] text-white' : 'border-gray-300 bg-white'; ?>" onclick="toggleDropdown('partner')">
                            Partner: <?php echo $selectedPartner ?: 'All'; ?>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div id="partnerDropdownContent" class="absolute left-0 z-10 mt-1 w-48 origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 hidden">
                            <div class="py-1 max-h-60 overflow-auto">
                        <a href="?<?php echo http_build_query(array_merge(['page' => 'projects'], array_diff_key($_GET, ['partner' => '']))); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">All Partners</a>
                                <?php foreach ($partners as $partner): ?>
                            <a href="?<?php echo http_build_query(array_merge(['page' => 'projects'], $_GET, ['partner' => $partner])); ?>" 
                                       class="block px-4 py-2 text-sm <?php echo $selectedPartner === $partner ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50'; ?>">
                                        <?php echo $partner; ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Award Winners Checkbox -->
                    <div class="flex items-center gap-2 ml-2">
                        <input
                            type="checkbox"
                            id="award-winners"
                            class="w-4 h-4"
                            <?php echo $showAwardWinners ? 'checked' : ''; ?>
                            onchange="toggleAwardWinners()"
                        >
                        <label for="award-winners" class="text-sm">
                            Show Award Winners
                        </label>
                    </div>
                </div>

                <!-- Active Filters -->
        <?php if ($selectedYear || $selectedQuarter || $selectedLevel || $selectedMajor || $selectedProfessor || !empty($selectedTags) || $selectedPartner || $searchTerm): ?>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="text-sm text-gray-500">Active filters:</span>

                    <?php if ($selectedYear): ?>
                    <span class="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 text-xs">
                        Year: <?php echo $selectedYear; ?>
                <a href="?<?php echo http_build_query(array_merge(['page' => 'projects'], array_diff_key($_GET, ['year' => '']))); ?>" class="ml-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    </span>
                    <?php endif; ?>

                    <?php if ($selectedQuarter): ?>
                    <span class="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 text-xs">
                        Quarter: <?php echo $selectedQuarter; ?>
                <a href="?<?php echo http_build_query(array_merge(['page' => 'projects'], array_diff_key($_GET, ['quarter' => '']))); ?>" class="ml-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    </span>
                    <?php endif; ?>

                    <?php if ($selectedLevel): ?>
                    <span class="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 text-xs">
                        Level: <?php echo $selectedLevel; ?>
                <a href="?<?php echo http_build_query(array_merge(['page' => 'projects'], array_diff_key($_GET, ['degree' => '']))); ?>" class="ml-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    </span>
                    <?php endif; ?>

                    <?php if ($selectedMajor): ?>
                    <span class="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 text-xs">
                        Major: <?php echo explode(',', $selectedMajor)[0]; ?>
                <a href="?<?php echo http_build_query(array_merge(['page' => 'projects'], array_diff_key($_GET, ['course' => '']))); ?>" class="ml-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    </span>
                    <?php endif; ?>

                    <?php if ($selectedProfessor): ?>
                    <span class="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 text-xs">
                        Professor: <?php echo explode(' ', $selectedProfessor)[1]; ?>
                <a href="?<?php echo http_build_query(array_merge(['page' => 'projects'], array_diff_key($_GET, ['professor' => '']))); ?>" class="ml-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    </span>
                    <?php endif; ?>

                    <?php foreach ($selectedTags as $tag): ?>
                    <span class="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 text-xs">
                        Tag: <?php echo $tag; ?>
                        <a href="?<?php 
                            $tags = array_diff($selectedTags, [$tag]);
                    $query = array_merge(['page' => 'projects'], array_diff_key($_GET, ['tags' => '']), ['tags' => implode(',', $tags)]);
                            echo http_build_query($query);
                        ?>" class="ml-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    </span>
                    <?php endforeach; ?>

                    <?php if ($selectedPartner): ?>
                    <span class="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 text-xs">
                        Partner: <?php echo $selectedPartner; ?>
                <a href="?<?php echo http_build_query(array_merge(['page' => 'projects'], array_diff_key($_GET, ['partner' => '']))); ?>" class="ml-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </span>
            <?php endif; ?>

            <?php if ($searchTerm): ?>
                <span class="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 text-xs">
                    Search: <?php echo htmlspecialchars($searchTerm); ?>
                    <a href="?<?php echo http_build_query(array_merge(['page' => 'projects'], array_diff_key($_GET, ['search' => '']))); ?>" class="ml-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    </span>
                    <?php endif; ?>

            <a href="?page=projects" class="text-xs text-[#4b84c7] hover:underline">Clear all filters</a>
                </div>
                <?php endif; ?>

                <!-- Projects Heading -->
                <h2 class="text-2xl font-bold mb-6">
                    <?php 
                    $headingParts = [];
                    if ($selectedYear) {
                        $headingParts[] = $selectedYear;
                    }
                    if ($selectedQuarter) {
                        $headingParts[] = $selectedQuarter;
                    }
                    if ($selectedLevel) {
                        $headingParts[] = $selectedLevel;
                    }
                    if ($showAwardWinners) {
                        $headingParts[] = 'Award-Winning';
                    }
                    $headingParts[] = 'Archived Capstone Projects';
                    echo implode(' ', $headingParts);
                    ?>
                </h2>

                <!-- Project Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <?php foreach ($filteredProjects as $project): ?>
                <a href="index.php?page=project-detail&id=<?php echo $project['id']; ?>" class="flex flex-col hover:shadow-md transition-shadow rounded-md overflow-hidden">
                            <div class="flex justify-between items-center mb-2 p-2">
                                <h3 class="font-medium"><?php echo htmlspecialchars($project['title']); ?></h3>
                                <?php if ($project['isAwardWinner'] && $showAwardWinners): ?>
                                    <div class="bg-[#f8e858] text-black px-2 py-1 rounded-full text-xs font-bold">
                                        #<?php echo $project['rank']; ?> Winner
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="bg-[#d9d9d9] aspect-square mb-2 <?php echo $project['isAwardWinner'] && $showAwardWinners ? 'border-2 border-[#f8e858]' : ''; ?>"></div>
                            <div class="p-2">
                                <p class="text-sm mb-2"><?php echo htmlspecialchars($project['description']); ?></p>
                                <div class="mt-auto">
                                    <div class="text-xs text-gray-500 mb-1">
                                <?php echo $project['course']; ?> • <?php echo $project['degree']; ?> • <?php echo $project['date']; ?>
                                    </div>
                                    <div class="flex flex-wrap gap-1">
                                        <?php foreach ($project['tags'] as $tag): ?>
                                            <span class="inline-block bg-gray-100 rounded-full px-2 py-1 text-xs font-semibold text-gray-700">
                                                <?php echo htmlspecialchars($tag); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if (!$showAwardWinners): ?>
                    <div class="flex justify-center gap-2 mt-8">
                        <span class="px-3 py-1 font-bold">1</span>
                        <?php for ($i = 2; $i <= 6; $i++): ?>
                    <a href="?<?php echo http_build_query(array_merge(['page' => 'projects'], $_GET, ['page' => $i])); ?>" class="px-3 py-1 hover:underline"><?php echo $i; ?></a>
                        <?php endfor; ?>
                <a href="?<?php echo http_build_query(array_merge(['page' => 'projects'], $_GET, ['page' => 2])); ?>" class="px-3 py-1 hover:underline">&gt;</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    <script>
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId + 'DropdownContent');
            const allDropdowns = document.querySelectorAll('[id$="DropdownContent"]');
            
            // Close all other dropdowns
            allDropdowns.forEach(d => {
                if (d !== dropdown) {
                    d.classList.add('hidden');
                }
            });
            
            // Toggle the clicked dropdown
            dropdown.classList.toggle('hidden');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('[id$="DropdownContent"]');
            dropdowns.forEach(dropdown => {
                if (!dropdown.contains(event.target) && !event.target.closest('[id$="Dropdown"]')) {
                    dropdown.classList.add('hidden');
                }
            });
        });

        // Tags functionality
        function toggleTag(tag) {
            const currentTags = new URLSearchParams(window.location.search).get('tags')?.split(',') || [];
            const tagIndex = currentTags.indexOf(tag);
            
            if (tagIndex === -1) {
                currentTags.push(tag);
            } else {
                currentTags.splice(tagIndex, 1);
            }
            
            const params = new URLSearchParams(window.location.search);
            if (currentTags.length > 0) {
                params.set('tags', currentTags.join(','));
            } else {
                params.delete('tags');
            }
            
            window.location.search = params.toString();
        }

        function toggleAwardWinners() {
            const url = new URL(window.location.href);
            const checkbox = document.getElementById('award-winners');
            
            if (checkbox.checked) {
                url.searchParams.set('award_winners', '1');
            } else {
                url.searchParams.delete('award_winners');
            }
            
            window.location.href = url.toString();
        }

        function handleSortChange() {
            const url = new URL(window.location.href);
            const sortSelect = document.getElementById('sort');
            const selectedValue = sortSelect.value;
            
            if (selectedValue) {
                url.searchParams.set('sort', selectedValue);
            } else {
                url.searchParams.delete('sort');
            }
            
            window.location.href = url.toString();
        }

    // Search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        const searchButton = document.getElementById('search-button');
        
        function performSearch() {
            const searchTerm = searchInput.value.trim();
            const url = new URL(window.location.href);
            
            if (searchTerm) {
                url.searchParams.set('search', searchTerm);
            } else {
                url.searchParams.delete('search');
            }
            
            window.location.href = url.toString();
        }
        
        if (searchButton) {
            searchButton.addEventListener('click', performSearch);
        }
        
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });
        }
    });
    </script>
