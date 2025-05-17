<?php
// pages/user-management.php
$pageTitle      = 'Activity Review';
$searchTerm     = $_GET['search'] ?? '';
$currentSort    = $_GET['sort']   ?? 'alphabetical';
$selectedCourse = $_GET['course'] ?? '';

// Mock data
$allProfessors = [
    [
        'id'           => 1,
        'name'         => 'Dr. Matt Bietz',
        'email'        => 'mbietz@uci.edu',
        'course'       => 'INF 191A/B',
        'weeks_left'   => 3,
        'course_dates' => '01/10/25 – 06/13/25',
    ],
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

// Pagination
$total       = count($allProfessors);
$perPage     = 3;
$currentPage = max(1, intval($_GET['p'] ?? 1));
$totalPages  = (int) ceil($total / $perPage);
if ($currentPage > $totalPages) {
    $currentPage = $totalPages;
}
$startIndex  = ($currentPage - 1) * $perPage;
$professors  = array_slice($allProfessors, $startIndex, $perPage);
$showingFrom = $startIndex + 1;
$showingTo   = min($total, $startIndex + $perPage);
?>
<main class="min-h-screen flex flex-col">
  <div class="flex-1 bg-gray-50 pt-16">
    <div class="max-w-7xl mx-auto px-4">

      <!-- Back to Dashboard -->
      <div class="-mt-2 mb-4">
        <a href="index.php?page=admin" class="inline-flex items-center text-[#4b84c7] hover:underline text-sm font-medium">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
          </svg>
          Back to Dashboard
        </a>
      </div>

      <!-- Page Title -->
      <h2 class="text-2xl font-bold mb-6">User Management</h2>

      <!-- Search / Filter / Add -->
      <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="flex items-center justify-between space-x-4">

          <!-- Form: Search / Course / Sort -->
          <form method="GET" action="index.php" class="flex items-center space-x-4 flex-nowrap overflow-x-auto">
            <input type="hidden" name="page" value="user-management">
            <!-- Search -->
            <div class="relative flex-none w-[300px]">
              <input type="text" name="search" placeholder="Search Professor…" value="<?= htmlspecialchars($searchTerm) ?>"
                     class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-[#4b84c7]">
              <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <i class="fas fa-search"></i>
              </button>
            </div>
            <!-- Course -->
            <div class="relative flex-none w-[120px]">
              <select name="course" onchange="this.form.submit()"
                      class="w-full px-4 py-2 border rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-[#4b84c7] appearance-none">
                <option value="">Course</option>
                <?php foreach ($courses as $c): ?>
                  <option value="<?= htmlspecialchars($c) ?>" <?= $selectedCourse === $c ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c) ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-2 flex items-center">
                <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
              </div>
            </div>
            <!-- Sort -->
            <div class="relative flex-none w-[120px]">
              <select name="sort" onchange="this.form.submit()"
                      class="w-full px-4 py-2 border rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-[#4b84c7] appearance-none">
                <option value="alphabetical" <?= $currentSort==='alphabetical'?'selected':'' ?>>A–Z</option>
                <option value="newest"       <?= $currentSort==='newest'      ?'selected':'' ?>>Newest</option>
                <option value="oldest"       <?= $currentSort==='oldest'      ?'selected':'' ?>>Oldest</option>
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
              </div>
            </div>
          </form>

          <!-- Add Professor Button -->
          <button onclick="openModal('add')" class="ml-4 inline-flex items-center gap-2 bg-[#6890BD] hover:bg-[#3a75ae] text-white font-medium py-2 px-4 rounded-md transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Professor
          </button>

          <!-- Modal Overlay -->
          <div id="modal-overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden"></div>

          <!-- Add Modal -->
          <div id="add-modal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
              <!-- Header -->
              <div class="flex items-center justify-between px-6 py-4 border-b">
                <h3 class="text-lg font-semibold">Add Professor</h3>
                <button type="button" onclick="closeModal()" class="text-gray-500 hover:text-gray-700">×</button>
              </div>
              <!-- Form -->
              <form id="add-form" class="px-6 py-4 space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Full Name</label>
                  <input type="text" name="name" placeholder="Dr. Bob Smith" required
                         class="mt-1 block w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#6890BD]">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Email</label>
                  <input type="email" name="email" placeholder="bsmith@uci.edu" required
                         class="mt-1 block w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#6890BD]">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Course</label>
                  <select name="course" required class="mt-1 block w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#6890BD] appearance-none">
                    <option value="">Select a Course</option>
                    <?php foreach($courses as $c): ?>
                      <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" name="start_date" required
                           class="mt-1 block w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#6890BD]">
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" name="end_date" required
                           class="mt-1 block w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#6890BD]">
                  </div>
                </div>
              </form>
              <!-- Footer -->
              <div class="px-6 py-4 border-t flex justify-end space-x-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</button>
                <button type="submit" form="add-form" class="px-4 py-2 bg-[#6890BD] text-white rounded-md hover:bg-[#3a75ae]">Add Professor</button>
              </div>
            </div>
          </div>

          <!-- Edit Modal -->
          <div id="edit-modal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
              <!-- Header -->
              <div class="flex items-center justify-between px-6 py-4 border-b">
                <h3 class="text-lg font-semibold">Edit Professor</h3>
                <button type="button" onclick="closeModal()" class="text-gray-500 hover:text-gray-700">×</button>
              </div>
              <!-- Form -->
              <form id="edit-form" class="px-6 py-4 space-y-4">
                <input type="hidden" name="id">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Full Name</label>
                  <input type="text" name="name" required class="mt-1 block w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#6890BD]">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Email</label>
                  <input type="email" name="email" required class="mt-1 block w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#6890BD]">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Course</label>
                  <select name="course" required class="mt-1 block w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#6890BD] appearance-none">
                    <option value="">Select a Course</option>
                    <?php foreach($courses as $c): ?>
                      <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" name="start_date" required class="mt-1 block w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#6890BD]">
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" name="end_date" required class="mt-1 block w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#6890BD]">
                  </div>
                </div>
              </form>
              <!-- Footer -->
              <div class="px-6 py-4 border-t flex justify-end space-x-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</button>
                <button type="submit" form="edit-form" class="px-4 py-2 bg-[#6890BD] text-white rounded-md hover:bg-[#3a75ae]">Save Changes</button>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Professors Table -->
      <table class="min-w-full divide-y divide-gray-200 mb-8">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Course</th>
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Weeks Left</th>
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Course Dates</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <?php foreach ($professors as $prof): ?>
            <tr data-id="<?= $prof['id'] ?>">
              <td class="px-6 py-4 text-sm text-gray-900"><?= htmlspecialchars($prof['name']) ?></td>
              <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($prof['email']) ?></td>
              <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($prof['course']) ?></td>
              <td class="px-6 py-4 text-center text-sm text-gray-500"><?= htmlspecialchars($prof['weeks_left']) ?></td>
              <td class="px-6 py-4 text-center text-sm text-gray-500"><?= htmlspecialchars($prof['course_dates']) ?></td>
              <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                <a href="#" onclick="openEditModal(
                      <?= $prof['id'] ?>,
                      '<?= addslashes($prof['name']) ?>',
                      '<?= addslashes($prof['email']) ?>',
                      '<?= explode(' – ',$prof['course_dates'])[0] ?>',
                      '<?= explode(' – ',$prof['course_dates'])[1] ?>',
                      '<?= addslashes($prof['course']) ?>'
                    )" class="text-[#6890BD] hover:text-[#3a75ae]">
                  <i class="fas fa-pencil-alt"></i>
                </a>
                <a href="index.php?page=delete-user&id=<?= $prof['id'] ?>" class="text-red-600 hover:text-red-900">
                  <i class="fas fa-trash-alt"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="px-6 py-4 border-t flex justify-between items-center text-sm text-gray-600">
        <div>Showing <?= $showingFrom ?>&ndash;<?= $showingTo ?> of <?= $total ?> professors</div>
        <div class="flex items-center space-x-2">
          <a href="?page=user-management&p=<?= max(1, $currentPage-1) ?>"
             class="px-2 py-1 border rounded <?= $currentPage===1?'text-gray-400 border-gray-200 cursor-not-allowed':'hover:bg-gray-100' ?>">
            Previous
          </a>
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=user-management&p=<?= $i ?>"
               class="px-2 py-1 border rounded <?= $i=== $currentPage?'bg-gray-800 text-white':'hover:bg-gray-100' ?>">
              <?= $i ?>
            </a>
          <?php endfor; ?>
          <a href="?page=user-management&p=<?= min($totalPages, $currentPage+1) ?>"
             class="px-2 py-1 border rounded <?= $currentPage===$totalPages?'text-gray-400 border-gray-200 cursor-not-allowed':'hover:bg-gray-100' ?>">
            Next
          </a>
        </div>
      </div>

    </div>
  </div>

  <!-- Modal scripts -->
  <script>
    function openModal(type) {
      document.getElementById('modal-overlay').classList.remove('hidden');
      document.getElementById(type + '-modal').classList.remove('hidden');
    }
    function closeModal() {
      document.getElementById('modal-overlay').classList.add('hidden');
      document.querySelectorAll('[id$="-modal"]').forEach(m => m.classList.add('hidden'));
    }

    // Add Form handler
    document.getElementById('add-form').addEventListener('submit', function(e) {
      e.preventDefault();
      const name      = this.name.value.trim();
      const email     = this.email.value.trim();
      const course    = this.course.value;
      const start     = this.start_date.value;
      const end       = this.end_date.value;
      const dates     = `${start} – ${end}`;
      if (!name||!email||!course||!start||!end) return alert('Fill all fields');
      const tbody = document.querySelector('table tbody');
      const tr = document.createElement('tr');
      tr.setAttribute('data-id', `new-${Date.now()}`);
      tr.innerHTML = `
        <td class="px-6 py-4 text-sm text-gray-900">${name}</td>
        <td class="px-6 py-4 text-sm text-gray-500">${email}</td>
        <td class="px-6 py-4 text-sm text-gray-500">${course}</td>
        <td class="px-6 py-4 text-center text-sm text-gray-500">—</td>
        <td class="px-6 py-4 text-center text-sm text-gray-500">${dates}</td>
        <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
          <a href="#" class="text-[#6890BD] hover:text-[#3a75ae]"><i class="fas fa-pencil-alt"></i></a>
          <a href="#" class="text-red-600 hover:text-red-900"><i class="fas fa-trash-alt"></i></a>
        </td>`;
      tbody.appendChild(tr);
      closeModal();
      this.reset();
    });

    // Edit Form handler
    document.getElementById('edit-form').addEventListener('submit', function(e) {
      e.preventDefault();
      const id    = this.id.value;
      const name  = this.name.value.trim();
      const email = this.email.value.trim();
      const course = this.course.value;
      const start = this.start_date.value;
      const end   = this.end_date.value;
      const dates = `${start} – ${end}`;
      if (!name||!email||!course||!start||!end) return alert('Fill all fields');
      const row = document.querySelector(`table tbody tr[data-id="${id}"]`);
      if (!row) return alert('Row not found');
      const cells = row.children;
      cells[0].textContent = name;
      cells[1].textContent = email;
      cells[2].textContent = course;
      cells[3].textContent = '—';
      cells[4].textContent = dates;
      closeModal();
    });

    document.getElementById('modal-overlay').onclick = closeModal;
  </script>

</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
