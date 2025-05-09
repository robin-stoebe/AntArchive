<?php
// No need to check authentication here since it's already checked in index.php

// Sample news data - in a real application, this would come from a database
$newsItems = [
    [
        'id' => 1,
        'title' => 'Spring Expo 2024 Registration Open',
        'description' => 'Registration is now open for the Spring 2024 Capstone Project Expo. Don\'t miss this opportunity to showcase your work!',
        'imageUrl' => '/placeholder.svg?height=300&width=400&query=Spring Expo 2024',
        'date' => '2024-03-15',
        'type' => 'Event',
        'category' => 'Expo',
        'status' => 'Published'
    ],
    [
        'id' => 2,
        'title' => 'Industry Partners Workshop',
        'description' => 'Join us for a workshop with industry partners to learn about current trends and opportunities in tech.',
        'imageUrl' => '/placeholder.svg?height=300&width=400&query=Industry Partners Workshop',
        'date' => '2024-03-10',
        'type' => 'Workshop',
        'category' => 'Professional Development',
        'status' => 'Published'
    ],
    [
        'id' => 3,
        'title' => 'New Mentoring Program Launched',
        'description' => 'We\'re excited to announce our new mentoring program connecting students with industry professionals.',
        'imageUrl' => '/placeholder.svg?height=300&width=400&query=Project Mentoring',
        'date' => '2024-03-05',
        'type' => 'Announcement',
        'category' => 'Program Updates',
        'status' => 'Draft'
    ]
];

// Get filter parameters
$selectedType = isset($_GET['type']) ? $_GET['type'] : '';
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';
$selectedStatus = isset($_GET['status']) ? $_GET['status'] : '';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Get unique types, categories, and statuses for filters
$types = array_unique(array_column($newsItems, 'type'));
$categories = array_unique(array_column($newsItems, 'category'));
$statuses = array_unique(array_column($newsItems, 'status'));

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

if (!empty($selectedStatus)) {
    $filteredNews = array_filter($filteredNews, function($item) use ($selectedStatus) {
        return $item['status'] === $selectedStatus;
    });
}

// Sort by date (newest first)
usort($filteredNews, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});
?>

<main class="min-h-screen flex flex-col">
    <div class="flex-1 bg-gray-50 py-8 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Manage News & Events</h1>
                    <p class="text-gray-600">Add, edit, and manage news items and events</p>
                </div>

                <a
                    href="#"
                    class="px-4 py-2 bg-[#4b84c7] text-white rounded-md hover:bg-[#3b6ba0] transition-colors"
                >
                    Add New Item
                </a>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                <form method="GET" action="index.php" class="space-y-4">
                    <input type="hidden" name="page" value="admin-news">

                    <!-- Search Bar -->
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="<?php echo htmlspecialchars($searchTerm); ?>"
                            placeholder="Search news and events..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#4b84c7]"
                        >
                        <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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

                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select
                                name="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#4b84c7]"
                            >
                                <option value="">All Statuses</option>
                                <?php foreach ($statuses as $status): ?>
                                    <option value="<?php echo htmlspecialchars($status); ?>" <?php echo $selectedStatus === $status ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($status); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="px-4 py-2 bg-[#4b84c7] text-white rounded-md hover:bg-[#3b6ba0] transition-colors"
                        >
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            <!-- News Items Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Title
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Type
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Category
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($filteredNews as $item): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0">
                                            <img
                                                class="h-10 w-10 rounded-md object-cover"
                                                src="<?php echo htmlspecialchars($item['imageUrl']); ?>"
                                                alt=""
                                            >
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars($item['title']); ?>
                                            </div>
                                            <div class="text-sm text-gray-500 line-clamp-1">
                                                <?php echo htmlspecialchars($item['description']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <?php echo htmlspecialchars($item['type']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo htmlspecialchars($item['category']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo date('M j, Y', strtotime($item['date'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $item['status'] === 'Published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                        <?php echo htmlspecialchars($item['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="#" class="text-[#4b84c7] hover:text-[#3b6ba0] mr-3">Edit</a>
                                    <a href="#" class="text-red-600 hover:text-red-900">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main> 