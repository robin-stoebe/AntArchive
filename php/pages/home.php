<?php
$featuredProjects = [
    [
        'id' => 1,
        'title' => 'Smart Health Monitoring System',
        'description' => 'A comprehensive health monitoring system for elderly patients using IoT devices and AI.',
        'tags' => ['Healthcare', 'IoT', 'AI'],
        'year' => '2025',
        'quarter' => 'Spring',
    ],
    [
        'id' => 2,
        'title' => 'Sustainable Campus Initiative',
        'description' => 'An application to track and reduce carbon footprint across the UCI campus.',
        'tags' => ['Sustainability', 'Data Science', 'Web Development'],
        'year' => '2025',
        'quarter' => 'Winter',
    ],
    [
        'id' => 3,
        'title' => 'Augmented Reality Learning Platform',
        'description' => 'An AR platform that enhances educational experiences through interactive 3D models.',
        'tags' => ['AR/VR', 'Education', 'Mobile Apps'],
        'year' => '2024',
        'quarter' => 'Fall',
    ],
    [
        'id' => 4,
        'title' => 'Automated Accessibility Tester',
        'description' => 'A tool that evaluates websites for accessibility compliance and suggests improvements.',
        'tags' => ['Accessibility', 'Web Development', 'AI'],
        'year' => '2024',
        'quarter' => 'Summer',
    ],
    [
        'id' => 5,
        'title' => 'Community Resource Mapper',
        'description' => 'A platform connecting underserved communities with local resources and services.',
        'tags' => ['Social Impact', 'Web Development', 'GIS'],
        'year' => '2024',
        'quarter' => 'Spring',
    ],
];

$projectsJson = json_encode($featuredProjects);
?>

<!-- Main Content -->
<div class="flex-1 flex flex-col items-center bg-gray-50">
  <!-- Featured Projects Carousel Section -->
  <div class="bg-[#848484] w-full py-12 px-6">
    <div class="max-w-5xl mx-auto">
      <h2 class="text-3xl font-semibold mb-2 text-white text-center">Capstone Project Archive</h2>
      <p class="text-white text-center mb-6 opacity-90">Featured student projects from our program</p>
      
      <!-- Featured Projects Carousel -->
      <div class="w-full relative">
        <div class="relative bg-white rounded-lg shadow-md overflow-hidden">
          <!-- Project content -->
          <div id="carousel-content" class="grid md:grid-cols-2 gap-4 p-6">
            <!-- Content will be populated by JavaScript -->
          </div>

          <button id="prev-button" class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 rounded-full p-2 shadow-md hover:bg-white" aria-label="Previous project">
            <i class="fas fa-chevron-left text-lg"></i>
          </button>

          <button id="next-button" class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 rounded-full p-2 shadow-md hover:bg-white" aria-label="Next project">
            <i class="fas fa-chevron-right text-lg"></i>
          </button>
        </div>

        <div id="carousel-dots" class="flex justify-center mt-4 gap-2">
          <!-- Dots will be populated by JavaScript -->
        </div>
      </div>
    </div>
  </div>

  <!-- About Section -->
  <div class="w-full max-w-5xl mx-auto py-8 px-6">
    <h2 class="text-2xl font-bold mb-4">About the ICS Capstone Project Program</h2>
    <p class="text-black leading-relaxed">
      Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum enim sapien, auctor sed mollis vehicula,
      placerat quis velit. Sed sagittis odio sed quam porttitor laoreet sit amet a elit. Phasellus gravida, velit
      ut faucibus laoreet, magna ante interdum tortor, a vestibulum mauris massa vel arcu. Integer ultricies velit
      dui, non consectetur dolor feugiat quis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a
      lacinia eros, at posuere augue. Etiam euismod pellentesque commodo. Fusce sed quam rutrum, tincidunt nisi
      ut, vulputate turpis. Pellentesque tincidunt sem vitae massa feugiat, id feugiat neque elementum.
    </p>
  </div>

  <!-- News & Events Section -->
  <div class="w-full bg-white py-12 px-6">
    <div class="max-w-5xl mx-auto">
      <h2 class="text-2xl font-bold mb-2 text-center">News & Events</h2>
      <p class="text-gray-600 text-center mb-8">
        Stay updated with the latest happenings in our capstone program
      </p>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- News Item 1 -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="relative h-48 w-full">
            <img
              src="/placeholder.svg?height=300&width=400&query=Spring Expo 2024"
              alt="Spring Expo 2024"
              class="object-cover w-full h-full"
            />
          </div>
          <div class="p-4">
            <div class="text-sm text-gray-500 mb-2">March 15, 2024</div>
            <h3 class="font-semibold text-lg mb-2">Spring Expo 2024 Registration Open</h3>
            <p class="text-gray-600 mb-4">
              Registration is now open for the Spring 2024 Capstone Project Expo. Don't miss this opportunity to showcase your work!
            </p>
            <a href="#" class="text-[#4b84c7] hover:text-[#3b6ba0] font-medium">Read More →</a>
          </div>
        </div>

        <!-- News Item 2 -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="relative h-48 w-full">
            <img
              src="/placeholder.svg?height=300&width=400&query=Industry Partners Workshop"
              alt="Industry Partners Workshop"
              class="object-cover w-full h-full"
            />
          </div>
          <div class="p-4">
            <div class="text-sm text-gray-500 mb-2">March 10, 2024</div>
            <h3 class="font-semibold text-lg mb-2">Industry Partners Workshop</h3>
            <p class="text-gray-600 mb-4">
              Join us for a workshop with industry partners to learn about current trends and opportunities in tech.
            </p>
            <a href="#" class="text-[#4b84c7] hover:text-[#3b6ba0] font-medium">Read More →</a>
          </div>
        </div>

        <!-- News Item 3 -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="relative h-48 w-full">
            <img
              src="/placeholder.svg?height=300&width=400&query=Project Mentoring"
              alt="Project Mentoring"
              class="object-cover w-full h-full"
            />
          </div>
          <div class="p-4">
            <div class="text-sm text-gray-500 mb-2">March 5, 2024</div>
            <h3 class="font-semibold text-lg mb-2">New Mentoring Program Launched</h3>
            <p class="text-gray-600 mb-4">
              We're excited to announce our new mentoring program connecting students with industry professionals.
            </p>
            <a href="#" class="text-[#4b84c7] hover:text-[#3b6ba0] font-medium">Read More →</a>
          </div>
        </div>
      </div>

      <div class="mt-8 text-center">
        <a
          href="index.php?page=news"
          class="px-6 py-3 bg-[#4b84c7] text-white rounded-md hover:bg-[#3b6ba0] transition-colors"
        >
          View All News & Events
        </a>
      </div>
    </div>
  </div>
</div>

<script>
  // Get projects data from PHP
  const projects = <?= $projectsJson ?>;
  let currentIndex = 0;
  let autoplayTimer;

  function renderProject() {
    const project = projects[currentIndex];
    const carouselContent = document.getElementById('carousel-content');
    
    // Create HTML for the current project
    const html = `
      <!-- Project image -->
      <div class="bg-[#d9d9d9] aspect-video rounded-md flex items-center justify-center">
        <span class="text-gray-500 text-sm">Project Image</span>
      </div>

      <!-- Project details -->
      <div class="flex flex-col">
        <div class="mb-2">
          <h3 class="text-xl font-bold">${project.title}</h3>
          <p class="text-sm text-gray-500">
            ${project.quarter} ${project.year}
          </p>
        </div>

        <p class="text-gray-700 mb-4">${project.description}</p>

        <div class="flex flex-wrap gap-2 mb-4">
          ${project.tags.map(tag => `
            <span class="inline-block bg-gray-100 rounded-full px-3 py-1 text-xs font-semibold text-gray-700">
              ${tag}
            </span>
          `).join('')}
        </div>

        <div class="mt-auto">
          <a href="index.php?page=project-detail&id=${project.id}" class="inline-flex items-center justify-center bg-[#4b84c7] text-white py-2 px-4 rounded hover:bg-[#3b6ba0] transition-colors">
            View Project Details
          </a>
        </div>
      </div>
    `;
    
    carouselContent.innerHTML = html;
    
    updateDots();
  }
  
  function updateDots() {
    const dotsContainer = document.getElementById('carousel-dots');
    dotsContainer.innerHTML = '';
    
    for (let i = 0; i < projects.length; i++) {
      const dot = document.createElement('button');
      dot.className = `w-3 h-3 rounded-full ${i === currentIndex ? 'bg-white' : 'bg-white/50'}`;
      dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
      dot.onclick = function() {
        currentIndex = i;
        renderProject();
        resetAutoplay();
      };
      dotsContainer.appendChild(dot);
    }
  }
  
  function nextProject() {
    currentIndex = (currentIndex + 1) % projects.length;
    renderProject();
    resetAutoplay();
  }
  
  function prevProject() {
    currentIndex = (currentIndex - 1 + projects.length) % projects.length;
    renderProject();
    resetAutoplay();
  }
  
  function resetAutoplay() {
    clearInterval(autoplayTimer);
    autoplayTimer = setInterval(nextProject, 5000);
  }
  
  document.getElementById('next-button').addEventListener('click', nextProject);
  document.getElementById('prev-button').addEventListener('click', prevProject);
  
  renderProject();
  resetAutoplay();
</script>
