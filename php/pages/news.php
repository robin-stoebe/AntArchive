<?php
// Sample news data - in a real application, this would come from a database
$newsItems = [
    [
        'id' => 1,
        'title' => 'Spring Expo 2024 Registration Open',
        'description' => 'Registration is now open for the Spring 2024 Capstone Project Expo. Don\'t miss this opportunity to showcase your work!',
        'imageUrl' => '/placeholder.svg?height=300&width=400&query=Spring Expo 2024',
        'date' => '2024-03-15',
        'type' => 'Event',
        'category' => 'Expo'
    ],
    [
        'id' => 2,
        'title' => 'Industry Partners Workshop',
        'description' => 'Join us for a workshop with industry partners to learn about current trends and opportunities in tech.',
        'imageUrl' => '/placeholder.svg?height=300&width=400&query=Industry Partners Workshop',
        'date' => '2024-03-10',
        'type' => 'Workshop',
        'category' => 'Professional Development'
    ],
    [
        'id' => 3,
        'title' => 'New Mentoring Program Launched',
        'description' => 'We\'re excited to announce our new mentoring program connecting students with industry professionals.',
        'imageUrl' => '/placeholder.svg?height=300&width=400&query=Project Mentoring',
        'date' => '2024-03-05',
        'type' => 'Announcement',
        'category' => 'Program Updates'
    ],
    [
        'id' => 4,
        'title' => 'Winter 2024 Award Winners Announced',
        'description' => 'Congratulations to all the winners of the Winter 2024 Capstone Project Awards!',
        'imageUrl' => '/placeholder.svg?height=300&width=400&query=Award Ceremony',
        'date' => '2024-02-28',
        'type' => 'Awards',
        'category' => 'Recognition'
    ],
    [
        'id' => 5,
        'title' => 'Tech Industry Panel Discussion',
        'description' => 'Join us for an insightful panel discussion with industry leaders about the future of technology.',
        'imageUrl' => '/placeholder.svg?height=300&width=400&query=Panel Discussion',
        'date' => '2024-02-20',
        'type' => 'Event',
        'category' => 'Professional Development'
    ],
    [
        'id' => 6,
        'title' => 'Project Submission Guidelines Updated',
        'description' => 'We\'ve updated our project submission guidelines for Spring 2024. Please review the changes.',
        'imageUrl' => '/placeholder.svg?height=300&width=400&query=Guidelines',
        'date' => '2024-02-15',
        'type' => 'Announcement',
        'category' => 'Program Updates'
    ]
];

// Get filter parameters
$selectedType = isset($_GET['type']) ? $_GET['type'] : '';
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Get unique types and categories for filters
$types = array_unique(array_column($newsItems, 'type'));
$categories = array_unique(array_column($newsItems, 'category'));

// Filter news items
$filteredNews = $newsItems;

if (!empty($searchTerm)) {
    $filteredNews = array_filter($filteredNews, function($item) use ($searchTerm) {
        return (stripos($item['title'], $searchTerm) !== false || 
                stripos($item['description'], $searchTerm) !== false);
    });
}

if (!empty($selectedType)) {
    $filteredNews = array_filter($filteredNews, function($item) use ($selectedType) {
        return $item['type'] === $selectedType;
    });
}

if (!empty($selectedCategory)) {
    $filteredNews = array_filter($filteredNews, function($item) use ($selectedCategory) {
        return $item['category'] === $selectedCategory;
    });
}

// Sort by date (newest first)
usort($filteredNews, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});

// Pagination
$currentPage = isset($_GET['p']) ? intval($_GET['p']) : 1;
$itemsPerPage = 6;
$totalItems = count($filteredNews);
$totalPages = ceil($totalItems / $itemsPerPage);
$currentPage = max(1, min($currentPage, $totalPages > 0 ? $totalPages : 1));
$currentItems = array_slice($filteredNews, ($currentPage - 1) * $itemsPerPage, $itemsPerPage);
?>

<main class="min-h-screen flex flex-col">
    <div class="flex-1 bg-gray-50 py-8 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-3xl font-bold mb-2">News & Events</h1>
                    <p class="text-gray-600">Stay updated with the latest happenings in our capstone program</p>
                </div>

                <!-- Search Bar -->
                <form class="w-full md:w-auto" method="GET" action="index.php">
                    <input type="hidden" name="page" value="news">
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="<?php echo htmlspecialchars($searchTerm); ?>"
                            placeholder="Search news and events..."
                            class="w-full md:w-64 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#4b84c7]"
                        >
                        <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                <form method="GET" action="index.php" class="flex flex-wrap gap-4">
                    <input type="hidden" name="page" value="news">
                    <?php if (!empty($searchTerm)): ?>
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>">
                    <?php endif; ?>

                    <!-- Type Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select
                            name="type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#4b84c7]"
                        >
                            <option value="">All Types</option>
                            <?php foreach ($types as $type): ?>
                                <option value="<?php echo htmlspecialchars($type); ?>" <?php echo $selectedType === $type ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($type); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select
                            name="category"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#4b84c7]"
                        >
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo htmlspecialchars($category); ?>" <?php echo $selectedCategory === $category ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button
                            type="submit"
                            class="px-4 py-2 bg-[#4b84c7] text-white rounded-md hover:bg-[#3b6ba0] transition-colors"
                        >
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            <!-- News Grid -->
            <?php if (count($currentItems) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <?php foreach ($currentItems as $item): ?>
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="relative h-48 w-full">
                                <img
                                    src="<?php echo htmlspecialchars($item['imageUrl']); ?>"
                                    alt="<?php echo htmlspecialchars($item['title']); ?>"
                                    class="object-cover w-full h-full"
                                >
                            </div>
                            <div class="p-4">
                                <div class="text-sm text-gray-500 mb-2">
                                    <?php echo date('F j, Y', strtotime($item['date'])); ?>
                                </div>
                                <h3 class="font-semibold text-lg mb-2"><?php echo htmlspecialchars($item['title']); ?></h3>
                                <p class="text-gray-600 mb-4">
                                    <?php echo htmlspecialchars($item['description']); ?>
                                </p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs bg-gray-100 px-2 py-1 rounded-full">
                                        <?php echo htmlspecialchars($item['type']); ?>
                                    </span>
                                    <a href="#" class="text-[#4b84c7] hover:text-[#3b6ba0] font-medium">Read More →</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="flex justify-center items-center mt-8">
                        <a
                            href="<?php echo 'index.php?page=news&p=' . max(1, $currentPage - 1) . 
                                (!empty($selectedType) ? '&type=' . urlencode($selectedType) : '') . 
                                (!empty($selectedCategory) ? '&category=' . urlencode($selectedCategory) : '') . 
                                (!empty($searchTerm) ? '&search=' . urlencode($searchTerm) : ''); ?>"
                            class="p-2 rounded-full <?php echo $currentPage === 1 ? 'text-gray-400 cursor-not-allowed' : 'text-gray-700 hover:bg-gray-100'; ?>"
                            <?php echo $currentPage === 1 ? 'aria-disabled="true"' : ''; ?>
                        >
                            <i class="fas fa-chevron-left w-5 h-5"></i>
                        </a>

                        <div class="flex items-center mx-2">
                            <?php
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($totalPages, $currentPage + 2);
                            
                            if ($startPage > 1) {
                                echo '<a href="index.php?page=news&p=1" class="px-3 py-1 text-gray-700 hover:bg-gray-100 rounded">1</a>';
                                if ($startPage > 2) {
                                    echo '<span class="px-2">...</span>';
                                }
                            }
                            
                            for ($i = $startPage; $i <= $endPage; $i++):
                            ?>
                                <a
                                    href="<?php echo 'index.php?page=news&p=' . $i . 
                                        (!empty($selectedType) ? '&type=' . urlencode($selectedType) : '') . 
                                        (!empty($selectedCategory) ? '&category=' . urlencode($selectedCategory) : '') . 
                                        (!empty($searchTerm) ? '&search=' . urlencode($searchTerm) : ''); ?>"
                                    class="px-3 py-1 <?php echo $currentPage === $i ? 'bg-[#4b84c7] text-white' : 'text-gray-700 hover:bg-gray-100'; ?> rounded"
                                >
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php
                            if ($endPage < $totalPages) {
                                if ($endPage < $totalPages - 1) {
                                    echo '<span class="px-2">...</span>';
                                }
                                echo '<a href="index.php?page=news&p=' . $totalPages . '" class="px-3 py-1 text-gray-700 hover:bg-gray-100 rounded">' . $totalPages . '</a>';
                            }
                            ?>
                        </div>

                        <a
                            href="<?php echo 'index.php?page=news&p=' . min($totalPages, $currentPage + 1) . 
                                (!empty($selectedType) ? '&type=' . urlencode($selectedType) : '') . 
                                (!empty($selectedCategory) ? '&category=' . urlencode($selectedCategory) : '') . 
                                (!empty($searchTerm) ? '&search=' . urlencode($searchTerm) : ''); ?>"
                            class="p-2 rounded-full <?php echo $currentPage === $totalPages ? 'text-gray-400 cursor-not-allowed' : 'text-gray-700 hover:bg-gray-100'; ?>"
                            <?php echo $currentPage === $totalPages ? 'aria-disabled="true"' : ''; ?>
                        >
                            <i class="fas fa-chevron-right w-5 h-5"></i>
                        </a>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <p class="text-gray-500 mb-4">No news or events found matching your search criteria</p>
                    <a
                        href="index.php?page=news"
                        class="px-4 py-2 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors inline-block"
                    >
                        Clear All Filters
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main> 