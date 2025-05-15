<?php
// pages/admin-approve-deny.php
$pageTitle   = 'Activity Review';
$searchTerm  = $_GET['search'] ?? '';
$currentSort = isset($_GET['sort'])   ? $_GET['sort']   : 'alphabetical';

$allProjects = [  
    [
      'id'          => 1,
      'title'       => 'AntArchive',
      'description' => 'A website that does…',
      'status'      => 'Pending',
      'date'        => '2025-04-11',   // YYYY-MM-DD 
    ],
    [
      'id'          => 2,
      'title'       => 'Capstone Project Name',
      'description' => 'Description…',
      'status'      => 'Denied',
      'date'        => '2025-03-28',
    ],
    [
      'id'          => 3,
      'title'       => 'Another Project',
      'description' => '…',
      'status'      => 'Approved',
      'date'        => '2025-02-14',
    ],
];

//Paginate the allProjects array
$total       = count($allProjects);
$perPage     = 3;
$currentPage = max(1, intval($_GET['p'] ?? 1));
$totalPages  = (int) ceil($total / $perPage);
if ($currentPage > $totalPages) $currentPage = $totalPages;

$startIndex  = ($currentPage - 1) * $perPage;
$projects    = array_slice($allProjects, $startIndex, $perPage);

$showingFrom = $startIndex + 1;
$showingTo   = min($total, $startIndex + $perPage);

?>

<main class="content">
  <div class="max-w-7xl mx-auto px-4">
    <div class="-mt-2 mb-4">
      <a href="index.php?page=admin" class="inline-flex items-center text-[#4b84c7] hover:underline text-sm font-medium">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        Back to Dashboard
      </a>
    </div>

    <!-- Page Title -->
    <h2 class="section-title font-bold mb-6">Manage Projects</h2>
    <!--Search & Sort -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
      <form method="GET" action="index.php" class="flex flex-wrap items-center gap-4">
        <input type="hidden" name="page" value="admin-approve-deny">
        <div class="relative flex-1 min-w-[200px]">
          <input type="text" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>" placeholder="Search projects…" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#4b84c7]">
          <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
            <i class="fas fa-search"></i>
          </button>
        </div>

        <div class="relative min-w-[150px]">
          <select name="sort" onchange="this.form.submit()" class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-[#4b84c7] appearance-none">
            <option value="alphabetical" <?php if($currentSort==='alphabetical') echo 'selected'; ?>>A–Z</option>
            <option value="newest"       <?php if($currentSort==='newest')       echo 'selected'; ?>>Newest First</option>
            <option value="oldest"       <?php if($currentSort==='oldest')       echo 'selected'; ?>>Oldest First</option>
          </select>

          <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19 9l-7 7-7-7"/>
            </svg>
          </div>
        </div>
      </form>
    </div>

   <!-- Projects Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
      
      <!-- Header -->
      <div class="px-6 py-4 border-b">
        <h3 class="text-lg font-semibold">Projects (<?php echo $total; ?>)</h3>
      </div>

     <!-- Project Section-->
<?php foreach ($projects as $proj): ?>
  <div class="px-6 py-4 border-b last:border-b-0 flex flex-col justify-between">
    <!-- Top: Title & Status -->
    <div class="flex justify-between items-start mb-2">
      <h4 class="font-medium text-base"><?php echo htmlspecialchars($proj['title']); ?></h4>
      <?php if ($proj['status'] === 'Pending'): ?>
        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pending</span>
      <?php elseif ($proj['status'] === 'Approved'): ?>
        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Approved</span>
      <?php else: ?>
        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Denied</span>
      <?php endif; ?>
    </div>

    <!-- Text Section -->
    <p class="text-sm text-gray-600 mb-4">
      <?php echo htmlspecialchars($proj['description']); ?>
    </p>

    <!-- Bottom: Review & Date -->
    <div class="flex justify-between items-center">
      <a href="index.php?page=admin-review&project_id=<?php echo $proj['id']; ?>" class="px-3 py-1 bg-white text-black border border-black rounded-md text-xs font-semibold hover:bg-gray-100 transition"> Review </a>
      <span class="text-xs text-gray-500"> Submitted <?php echo date('M j, Y', strtotime($proj['date'])); ?> </span>
    </div>
  </div>
<?php endforeach; ?>

      <!-- Pages -->
      <div class="px-6 py-4 border-t flex justify-between items-center text-sm text-gray-600">
        <div> Showing <?php echo $showingFrom; ?>–<?php echo $showingTo; ?> of <?php echo $total; ?> projects </div>
        <div class="flex items-center space-x-2">
          <!-- Previous -->
          <a href="?page=admin-approve-deny&p=<?php echo max(1, $currentPage-1); ?>" class="px-2 py-1 border rounded <?php echo $currentPage===1?'text-gray-400 border-gray-200 cursor-not-allowed':'hover:bg-gray-100'; ?>"> Previous </a>
          <!-- Page Numbers -->
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=admin-approve-deny&p=<?php echo $i; ?>" class="px-2 py-1 border rounded <?php echo $i === $currentPage ? 'bg-gray-800 text-white' : 'hover:bg-gray-100'; ?>">
              <?php echo $i; ?>
            </a>
          <?php endfor; ?>
          <!-- Next -->
          <a href="?page=admin-approve-deny&p=<?php echo min($totalPages, $currentPage+1); ?>" class="px-2 py-1 border rounded <?php echo $currentPage===$totalPages?'text-gray-400 border-gray-200 cursor-not-allowed':'hover:bg-gray-100'; ?>"> Next </a>
        </div>
      </div>
    </div>
  </div>
</main>

<?php include 'includes/footer.php'; ?>
