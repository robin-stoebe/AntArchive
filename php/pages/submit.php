<?php
$majors = [
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

// Project tags
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

// Initialize form variables
$description = '';
$professor = '';
$term = '';
$projectLevel = '';
$major = '';
$selectedTags = [];

// Handle form submission
$formSubmitted = false;
$formSuccess = false;
$formError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formSubmitted = true;
    
    // Process form data
    $description = $_POST['description'] ?? '';
    $professor = $_POST['professor'] ?? '';
    $term = $_POST['term'] ?? '';
    $projectLevel = $_POST['projectLevel'] ?? '';
    $major = $_POST['major'] ?? '';
    $selectedTags = $_POST['tags'] ?? [];
    
    // Validate form (basic validation)
    if (empty($description) || empty($professor) || empty($term) || empty($projectLevel) || empty($major)) {
        $formError = 'Please fill in all required fields.';
    } else {
        // In a real application, you would save the data to a database here
        $formSuccess = true;
    }
}

$majorsJson = json_encode($majors);
$projectTagsJson = json_encode($projectTags);
$selectedTagsJson = json_encode($selectedTags);
?>

<!-- Form Content -->
<div class="flex-1 flex flex-col items-center bg-gray-50">
    <div class="w-full max-w-3xl mx-auto py-8 px-6">
        <h2 class="text-2xl font-bold text-center mb-8 text-[#4b84c7]">Submit Your Capstone Project</h2>

        <?php if ($formSubmitted && $formSuccess): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <p class="font-bold">Success!</p>
                <p>Your project has been submitted for approval. You will be notified once it has been reviewed.</p>
            </div>
        <?php endif; ?>

        <?php if ($formSubmitted && !empty($formError)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <p class="font-bold">Error</p>
                <p><?= $formError ?></p>
            </div>
        <?php endif; ?>

        <form method="post" action="index.php?page=submit" class="space-y-10">
            <!-- Project Title -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2">
                <label class="text-base font-semibold text-gray-800">1. What is your capstone project's title?</label>
                <input
                    type="text"
                    name="title"
                    placeholder="Enter your answer..."
                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent text-sm"
                    required
                >
            </div>

            <!-- Project Description -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2">
                <label class="text-base font-semibold text-gray-800">
                    2. Describe your capstone project in 1-2 sentences:
                </label>
                <div class="relative">
                    <textarea
                        name="description"
                        placeholder="Eg: AntArchive is a website that consists of all the past, current, and future capstone projects. It includes various views like the admin, student, and professor view..."
                        class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent h-32"
                        maxlength="200"
                        id="description-textarea"
                        required
                    ><?= htmlspecialchars($description) ?></textarea>
                    <div class="absolute bottom-2 right-2 text-sm text-gray-500">
                        <span id="char-count">0</span>/200
                    </div>
                </div>
            </div>

            <!-- Capstone Professor -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2">
                <label class="text-base font-semibold text-gray-800">
                    3. Who is your capstone project advisor/professor?
                </label>
                <input
                    type="text"
                    name="professor"
                    placeholder="Enter professor's name..."
                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent text-sm"
                    value="<?= htmlspecialchars($professor) ?>"
                    required
                >
            </div>

            <!-- Term and Year -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2">
                <label class="text-base font-semibold text-gray-800">
                    4. Which academic term is this project for?
                </label>
                <input
                    type="text"
                    name="term"
                    placeholder="E.g., Spring 2023"
                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent text-sm"
                    value="<?= htmlspecialchars($term) ?>"
                    required
                >
            </div>

            <!-- Project Degree -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2">
                <label class="text-base font-semibold text-gray-800">
                    5. What degree is this capstone project for?
                </label>
                <div class="flex flex-wrap gap-4">
                    <label class="inline-flex items-center">
                        <input
                            type="radio"
                            name="projectLevel"
                            value="BS"
                            <?= $projectLevel === 'BS' ? 'checked' : '' ?>
                            class="mr-2"
                            required
                        >
                        BS
                    </label>
                    <label class="inline-flex items-center">
                        <input
                            type="radio"
                            name="projectLevel"
                            value="BA"
                            <?= $projectLevel === 'BA' ? 'checked' : '' ?>
                            class="mr-2"
                        >
                        BA
                    </label>
                    <label class="inline-flex items-center">
                        <input
                            type="radio"
                            name="projectLevel"
                            value="Professional Master's"
                            <?= $projectLevel === "Professional Master's" ? 'checked' : '' ?>
                            class="mr-2"
                        >
                        Professional Master's
                    </label>
                    <label class="inline-flex items-center">
                        <input
                            type="radio"
                            name="projectLevel"
                            value="MS"
                            <?= $projectLevel === 'MS' ? 'checked' : '' ?>
                            class="mr-2"
                        >
                        MS
                    </label>
                    <label class="inline-flex items-center">
                        <input
                            type="radio"
                            name="projectLevel"
                            value="PhD"
                            <?= $projectLevel === 'PhD' ? 'checked' : '' ?>
                            class="mr-2"
                        >
                        PhD
                    </label>
                </div>
            </div>

            <!-- Course -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2">
                <label class="text-base font-semibold text-gray-800">6. What is your primary course?</label>
                <div class="relative">
                    <select 
                        name="major" 
                        id="major-select"
                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent text-sm appearance-none"
                        required
                    >
                        <option value="" disabled <?= empty($major) ? 'selected' : '' ?>>Select your course...</option>
                        <?php foreach ($majors as $majorOption): ?>
                            <option value="<?= $majorOption ?>" <?= $major === $majorOption ? 'selected' : '' ?>>
                                <?= $majorOption ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Project Tags -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2">
                <label class="text-base font-semibold text-gray-800">
                    7. Select tags that describe your project:
                </label>

                <!-- Selected tags display -->
                <div id="selected-tags-container" class="flex flex-wrap gap-2 mb-2">
                    <!-- Will be populated by JavaScript -->
                </div>

                <!-- Tags dropdown -->
                <div class="relative">
                    <button
                        type="button"
                        id="tags-dropdown-button"
                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent text-left flex justify-between items-center text-sm"
                    >
                        <span id="tags-dropdown-text">Select tags...</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div id="tags-dropdown" class="hidden absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded shadow-lg max-h-60 overflow-y-auto">
                        <div class="p-2 border-b border-gray-200 sticky top-0 bg-white">
                            <p class="text-sm text-gray-500">Select all that apply</p>
                        </div>
                        <div class="grid grid-cols-2 gap-1 p-2">
                            <?php foreach ($projectTags as $tag): ?>
                                <div class="tag-option text-left px-3 py-2 text-sm rounded hover:bg-gray-100" data-tag="<?= $tag ?>">
                                    <?= $tag ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="p-2 border-t border-gray-200 sticky bottom-0 bg-white">
                            <button
                                type="button"
                                id="tags-done-button"
                                class="w-full py-2 bg-[#4b84c7] text-white rounded text-sm"
                            >
                                Done
                            </button>
                        </div>
                    </div>
                </div>
                <p class="text-sm text-gray-500">
                    Select at least 1 tag and up to 5 tags that best describe your project.
                </p>
                
                <div id="tags-hidden-inputs">
                    
                </div>
            </div>

            <!-- Team Members -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2">
                <label class="text-base font-semibold text-gray-800">8. Enter your team members:</label>

                <div id="team-members-container" class="space-y-4 max-h-96 overflow-y-auto pr-2">
                    <div class="space-y-2 relative border border-gray-200 p-3 pr-10 rounded-md bg-gray-50">
                        <input
                            type="text"
                            name="team_name[]"
                            placeholder="Enter name..."
                            class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent text-sm"
                            required
                        >
                        <input
                            type="text"
                            name="team_role[]"
                            placeholder="Enter role..."
                            class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent text-sm"
                            required
                        >
                        <input
                            type="email"
                            name="team_email[]"
                            placeholder="Enter UCI email..."
                            class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent text-sm"
                            required
                        >
                    </div>
                </div>

                <!-- Team member count -->
                <div class="flex justify-between items-center pt-2">
                    <button
                        type="button"
                        id="add-team-member"
                        class="flex items-center gap-2 bg-[#4b84c7] text-white py-1.5 px-3 rounded-md hover:bg-[#3b6ba0] transition-colors text-sm"
                    >
                        <i class="fas fa-plus"></i> Add a team member
                    </button>
                    <span id="team-count" class="text-sm text-gray-500 bg-white px-3 py-1 rounded-full border">
                        1 team member
                    </span>
                </div>
            </div>

            <!-- Sponsor Information -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2">
                <label class="text-base font-semibold text-gray-800">
                    9. Enter information about your sponsor:
                </label>
                <input
                    type="text"
                    name="sponsor_name"
                    placeholder="Enter name..."
                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent text-sm"
                >
                <input
                    type="text"
                    name="sponsor_org"
                    placeholder="Organization name..."
                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent text-sm"
                >
                <input
                    type="email"
                    name="sponsor_email"
                    placeholder="Sponsor's email..."
                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent text-sm"
                >
            </div>

            <!-- Project Image -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2">
                <label class="text-base font-semibold text-gray-800">
                    10. Choose an image representing your capstone project:
                </label>
                <div class="flex items-center">
                    <label class="flex items-center gap-2 bg-[#4b84c7] text-white py-1.5 px-3 rounded-md hover:bg-[#3b6ba0] transition-colors text-sm cursor-pointer">
                        <i class="fas fa-upload"></i> Choose a file
                        <input type="file" name="project_image" class="hidden" accept="image/*">
                    </label>
                    <span id="file-name" class="ml-3 text-sm text-gray-500">No file chosen</span>
                </div>
            </div>

            <!-- Project Websites -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2">
                <label class="text-base font-semibold text-gray-800">
                    11. Enter any websites used for your capstone project:
                </label>
                <div id="links-container">
                    <div class="flex items-center gap-2 mb-2">
                        <input
                            type="url"
                            name="links[]"
                            placeholder="Enter your answer..."
                            class="flex-1 p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent text-sm"
                        >
                    </div>
                </div>
                <button
                    type="button"
                    id="add-link"
                    class="flex items-center gap-2 bg-[#4b84c7] text-white py-1.5 px-3 rounded-md hover:bg-[#3b6ba0] transition-colors text-sm"
                >
                    <i class="fas fa-plus"></i> Add a link
                </button>
            </div>

            <!-- Project Video -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2">
                <label class="text-base font-semibold text-gray-800">
                    12. Choose a video that represents your capstone project (optional):
                </label>
                <div class="flex items-center">
                    <label class="flex items-center gap-2 bg-[#4b84c7] text-white py-1.5 px-3 rounded-md hover:bg-[#3b6ba0] transition-colors text-sm cursor-pointer">
                        <i class="fas fa-upload"></i> Choose a file
                        <input type="file" name="project_video" class="hidden" accept="video/*">
                    </label>
                    <span id="video-file-name" class="ml-3 text-sm text-gray-500">No file chosen</span>
                </div>
            </div>

            <!-- Project Files -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2">
                <label class="text-base font-semibold text-gray-800">
                    13. Enter up to 5 files about your capstone project:
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-12 flex flex-col items-center justify-center text-center">
                    <i class="fas fa-upload text-[#4b84c7] text-4xl mb-2"></i>
                    <p class="text-gray-700">Upload any file(s)</p>
                    <input type="file" name="project_files[]" multiple class="hidden" id="project-files">
                    <button
                        type="button"
                        id="upload-files-btn"
                        class="mt-4 flex items-center gap-2 bg-[#4b84c7] text-white py-1.5 px-3 rounded-md hover:bg-[#3b6ba0] transition-colors text-sm"
                    >
                        <i class="fas fa-file"></i> Select Files
                    </button>
                    <div id="selected-files" class="mt-4 text-sm text-gray-500"></div>
                </div>
            </div>

            <!-- Submit Form -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2">
                <label class="text-base font-semibold text-gray-800">14. Submit form for approval:</label>
                <div class="flex justify-center">
                    <button
                        type="submit"
                        class="flex items-center justify-center gap-2 bg-[#4b84c7] text-white py-2 px-6 rounded-md hover:bg-[#3b6ba0] transition-colors font-medium"
                    >
                        Submit for Approval <i class="fas fa-check-circle"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const descriptionTextarea = document.getElementById('description-textarea');
    const charCount = document.getElementById('char-count');
    
    descriptionTextarea.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });
    
    charCount.textContent = descriptionTextarea.value.length;
    
    // Tags dropdown functionality
    const tagsDropdownButton = document.getElementById('tags-dropdown-button');
    const tagsDropdown = document.getElementById('tags-dropdown');
    const tagsDoneButton = document.getElementById('tags-done-button');
    const tagsDropdownText = document.getElementById('tags-dropdown-text');
    const selectedTagsContainer = document.getElementById('selected-tags-container');
    const tagsHiddenInputs = document.getElementById('tags-hidden-inputs');
    
    let selectedTags = <?= $selectedTagsJson ?>;
    
    function updateSelectedTags() {
        if (selectedTags.length === 0) {
            tagsDropdownText.textContent = 'Select tags...';
        } else {
            tagsDropdownText.textContent = `${selectedTags.length} tags selected`;
        }
        
        selectedTagsContainer.innerHTML = '';
        tagsHiddenInputs.innerHTML = '';
        
        selectedTags.forEach(tag => {
            const tagElement = document.createElement('div');
            tagElement.className = 'bg-gray-100 rounded-full px-3 py-1 text-sm flex items-center';
            tagElement.innerHTML = `
                ${tag}
                <button type="button" class="ml-2 text-gray-500 hover:text-gray-700" data-tag="${tag}">
                    <i class="fas fa-times text-xs"></i>
                </button>
            `;
            selectedTagsContainer.appendChild(tagElement);
            
            tagElement.querySelector('button').addEventListener('click', function() {
                const tagToRemove = this.getAttribute('data-tag');
                selectedTags = selectedTags.filter(t => t !== tagToRemove);
                updateSelectedTags();
            });
            
            // Create hidden input for form submission
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'tags[]';
            hiddenInput.value = tag;
            tagsHiddenInputs.appendChild(hiddenInput);
        });
    }
    
    tagsDropdownButton.addEventListener('click', function() {
        tagsDropdown.classList.toggle('hidden');
    });
    
    document.addEventListener('click', function(event) {
        if (!tagsDropdownButton.contains(event.target) && !tagsDropdown.contains(event.target)) {
            tagsDropdown.classList.add('hidden');
        }
    });
    
    // Handle tag selection
    document.querySelectorAll('.tag-option').forEach(option => {
        option.addEventListener('click', function() {
            const tag = this.getAttribute('data-tag');
            
            if (selectedTags.includes(tag)) {
                // Remove tag if already selected
                selectedTags = selectedTags.filter(t => t !== tag);
                this.classList.remove('bg-[#4b84c7]', 'text-white');
            } else if (selectedTags.length < 5) {
                // Add tag if limit not reached
                selectedTags.push(tag);
                this.classList.add('bg-[#4b84c7]', 'text-white');
            }
            
            updateSelectedTags();
        });
        
        if (selectedTags.includes(option.getAttribute('data-tag'))) {
            option.classList.add('bg-[#4b84c7]', 'text-white');
        }
    });
    
    tagsDoneButton.addEventListener('click', function() {
        tagsDropdown.classList.add('hidden');
    });
    
    // Initialize selected tags display
    updateSelectedTags();
    
    // Team members functionality
    const teamMembersContainer = document.getElementById('team-members-container');
    const addTeamMemberButton = document.getElementById('add-team-member');
    const teamCountDisplay = document.getElementById('team-count');
    let teamMemberCount = 1;
    
    // Add team member
    addTeamMemberButton.addEventListener('click', function() {
        teamMemberCount++;
        
        const newMember = document.createElement('div');
        newMember.className = 'space-y-2 relative border border-gray-200 p-3 pr-10 rounded-md bg-gray-50';
        newMember.innerHTML = `
            <input
                type="text"
                name="team_name[]"
                placeholder="Enter name..."
                class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent text-sm"
                required
            >
            <input
                type="text"
                name="team_role[]"
                placeholder="Enter role..."
                class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent text-sm"
                required
            >
            <input
                type="email"
                name="team_email[]"
                placeholder="Enter UCI email..."
                class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent text-sm"
                required
            >
            <button
                type="button"
                class="absolute top-3 right-3 p-1 text-gray-500 hover:text-red-500 hover:bg-red-50 rounded-full remove-team-member"
                aria-label="Remove team member"
            >
                <i class="fas fa-times"></i>
            </button>
        `;
        
        teamMembersContainer.appendChild(newMember);
        updateTeamCount();
        
        // Add event listener to remove button
        newMember.querySelector('.remove-team-member').addEventListener('click', function() {
            teamMembersContainer.removeChild(newMember);
            teamMemberCount--;
            updateTeamCount();
        });
    });
    
    // Update team count display
    function updateTeamCount() {
        teamCountDisplay.textContent = `${teamMemberCount} team member${teamMemberCount !== 1 ? 's' : ''}`;
    }
    
    // Project links functionality
    const linksContainer = document.getElementById('links-container');
    const addLinkButton = document.getElementById('add-link');
    
    // Add link
    addLinkButton.addEventListener('click', function() {
        const newLink = document.createElement('div');
        newLink.className = 'flex items-center gap-2 mb-2';
        newLink.innerHTML = `
            <input
                type="url"
                name="links[]"
                placeholder="Enter your answer..."
                class="flex-1 p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent text-sm"
            >
            <button
                type="button"
                class="p-1 text-gray-500 hover:text-gray-700 remove-link"
            >
                <i class="fas fa-times text-xl"></i>
            </button>
        `;
        
        linksContainer.appendChild(newLink);
        
        // Add event listener to remove button
        newLink.querySelector('.remove-link').addEventListener('click', function() {
            linksContainer.removeChild(newLink);
        });
    });
    
    // File upload functionality
    const projectImageInput = document.querySelector('input[name="project_image"]');
    const fileNameDisplay = document.getElementById('file-name');
    
    projectImageInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            fileNameDisplay.textContent = this.files[0].name;
        } else {
            fileNameDisplay.textContent = 'No file chosen';
        }
    });
    
    // Video upload functionality
    const projectVideoInput = document.querySelector('input[name="project_video"]');
    const videoFileNameDisplay = document.getElementById('video-file-name');
    
    projectVideoInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            videoFileNameDisplay.textContent = this.files[0].name;
        } else {
            videoFileNameDisplay.textContent = 'No file chosen';
        }
    });
    
    // Project files functionality
    const projectFilesInput = document.getElementById('project-files');
    const uploadFilesButton = document.getElementById('upload-files-btn');
    const selectedFilesDisplay = document.getElementById('selected-files');
    
    uploadFilesButton.addEventListener('click', function() {
        projectFilesInput.click();
    });
    
    projectFilesInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            selectedFilesDisplay.innerHTML = '';
            for (let i = 0; i < this.files.length; i++) {
                const fileItem = document.createElement('div');
                fileItem.className = 'text-left';
                fileItem.textContent = `${i + 1}. ${this.files[i].name}`;
                selectedFilesDisplay.appendChild(fileItem);
            }
        } else {
            selectedFilesDisplay.textContent = '';
        }
    });
</script>
